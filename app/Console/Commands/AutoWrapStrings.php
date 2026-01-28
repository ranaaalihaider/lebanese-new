<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AutoWrapStrings extends Command
{
    protected $signature = 'translations:autowrap {path? : Specific path to scan inside views} {--dry-run : Show what would be changed without saving}';
    protected $description = 'Automatically wrap static text in Blade files with @trans directive';

    protected $ignoredTags = ['script', 'style', 'code', 'pre'];
    protected $stats = ['files' => 0, 'wrapped' => 0];

    public function handle()
    {
        $path = $this->argument('path')
            ? resource_path('views/' . $this->argument('path'))
            : resource_path('views');

        if (!File::exists($path)) {
            $this->error("Path does not exist: $path");
            return 1;
        }

        $files = File::isDirectory($path) ? File::allFiles($path) : [new \SplFileInfo($path)];

        $this->info("Scanning " . count($files) . " files...");

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php')
                continue;

            $this->processFile($file);
        }

        $this->newLine();
        $this->info("Done! Processed {$this->stats['files']} files and wrapped {$this->stats['wrapped']} strings.");

        if ($this->option('dry-run')) {
            $this->warn("This was a DRY RUN. No files were modified.");
        }
    }

    protected function processFile($file)
    {
        $content = File::get($file);
        $originalContent = $content;
        $modified = false;

        // Smart Regex to match text between tags
        // captures: 1=opening tag closing >, 2=text content, 3=closing tag opening <
        // Excludes matches with blade syntax {{ }} or {!! !!} inside
        $pattern = '/(>)\s*([^<>{}\r\n]+?)\s*(<)/s';

        $newContent = preg_replace_callback($pattern, function ($matches) {
            $text = trim($matches[2]);

            // Validation rules to skip bad matches
            if (
                empty($text) ||
                is_numeric($text) ||
                strlen($text) < 2 ||
                str_contains($text, '@') ||
                str_contains($text, '$') ||
                str_contains($text, 'function(') ||
                str_contains($text, 'return ') ||
                str_contains($text, '->') ||      // Arrow syntax
                str_contains($text, '==') ||      // Comparison
                str_contains($text, '!=') ||      // Comparison
                str_ends_with($text, ')') ||      // Likely end of function call
                str_ends_with($text, '}') ||      // Likely end of block
                preg_match('/^[0-9\s\W]+$/', $text) // Only numbers and symbols
            ) {
                return $matches[0];
            }

            // Skip if it looks like code or attributes
            if (preg_match('/^[a-zA-Z0-9_\-]+=[a-zA-Z0-9_\-]+$/', $text))
                return $matches[0];

            $this->stats['wrapped']++;
            if ($this->option('dry-run')) {
                $this->line("  Found: '$text'");
            }

            // Preserve whitespace structure but wrap the text
            // $matches[0] is the full match string ">  Text  <"
            // We want to replace "Text" with "@trans('Text')"
            return str_replace($text, "@trans('" . addslashes($text) . "')", $matches[0]);

        }, $content);

        if ($newContent !== $originalContent) {
            $this->stats['files']++;
            if (!$this->option('dry-run')) {
                File::put($file, $newContent);
                $this->info("✓ Updated: " . $file->getFilename());
            } else {
                $this->info("~ Would update: " . $file->getFilename());
            }
        }
    }
}
