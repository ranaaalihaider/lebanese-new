<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutoTranslationService;

class GenerateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:generate
                            {--clear-cache : Clear translation cache before generating}
                            {--stats : Show translation statistics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all Blade templates and generate translations using Google Translate API';

    protected $translationService;

    /**
     * Create a new command instance.
     */
    public function __construct(AutoTranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌍 Starting translation generation...');
        $this->newLine();

        // Clear cache if requested
        if ($this->option('clear-cache')) {
            $this->translationService->clearCache();
            $this->info('✓ Translation cache cleared');
            $this->newLine();
        }

        // Show stats if requested
        if ($this->option('stats')) {
            $this->showStats();
            return 0;
        }

        // Create progress bar
        $progressBar = null;

        // Progress callback
        $progressCallback = function ($current, $total, $locale, $string) use (&$progressBar) {
            if (!$progressBar) {
                $progressBar = $this->output->createProgressBar($total);
                $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %message%');
            }

            $progressBar->setMessage("Translating to {$locale}: " . substr($string, 0, 50) . '...');
            $progressBar->advance();
        };

        // Generate translations
        try {
            $result = $this->translationService->generateTranslations($progressCallback);

            if ($progressBar) {
                $progressBar->finish();
                $this->newLine(2);
            }

            // Show results
            $this->info('✓ Translation generation completed!');
            $this->newLine();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Total Strings', $result['total_strings']],
                    ['Total Translations', $result['total_translations']],
                    ['Languages', implode(', ', $result['locales'])],
                ]
            );

            $this->newLine();
            $this->showStats();

            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Translation generation failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Show translation statistics
     */
    protected function showStats()
    {
        $stats = $this->translationService->getStats();

        $this->info('📊 Translation Statistics:');
        $this->newLine();

        $tableData = [];
        foreach ($stats as $locale => $count) {
            $flag = match ($locale) {
                'ar' => '🇱🇧',
                'hy' => '🇦🇲',
                'fr' => '🇫🇷',
                default => '🏳️',
            };

            $language = match ($locale) {
                'ar' => 'Arabic',
                'hy' => 'Armenian',
                'fr' => 'French',
                default => $locale,
            };

            $tableData[] = [$flag . ' ' . $language, $count . ' translations'];
        }

        $this->table(['Language', 'Count'], $tableData);
    }
}
