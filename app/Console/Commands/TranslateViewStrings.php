<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutoTranslationService;

class TranslateViewStrings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:translate-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan views and auto-translate strings to supported locales';

    /**
     * Execute the console command.
     */
    public function handle(AutoTranslationService $service)
    {
        $this->info('Scanning views and generating translations...');
        $service->generateTranslations();
        $this->info('Translations generated successfully!');
    }
}
