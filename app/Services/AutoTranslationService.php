<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\File;

class AutoTranslationService
{
    protected $tr;

    public function __construct()
    {
        $this->tr = new GoogleTranslate();
    }

    public function translate(string $text, string $targetLocale): string
    {
        // Custom mapping for 'hy' if needed, Google Translate uses 'hy' for Armenian
        if ($targetLocale === 'hy') {
            // Lebanese Armenian might prefer a different flavor, but standard HY is what we have.
        }

        $this->tr->setSource('en');
        $this->tr->setTarget($targetLocale);

        try {
            return $this->tr->translate($text);
        } catch (\Exception $e) {
            return $text; // Fallback to original
        }
    }

    /**
     * Scan views and auto-translate strings to JSON files
     */
    public function generateTranslations()
    {
        $locales = ['ar', 'hy', 'fr'];
        $strings = $this->findStringsInViews();

        foreach ($locales as $locale) {
            $path = lang_path("$locale.json");
            $current = File::exists($path) ? json_decode(File::get($path), true) : [];
            $modified = false;

            foreach ($strings as $string) {
                if (!isset($current[$string])) {
                    $current[$string] = $this->translate($string, $locale);
                    $modified = true;
                }
            }

            if ($modified) {
                File::put($path, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
    }

    protected function findStringsInViews()
    {
        $strings = [];
        $files = File::allFiles(resource_path('views'));

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php')
                continue;

            $content = File::get($file);
            // Match __('string') and @lang('string')
            preg_match_all("/__\(['\"](.+?)['\"]\)/", $content, $matches1);
            preg_match_all("/@lang\(['\"](.+?)['\"]\)/", $content, $matches2);

            $strings = array_merge($strings, $matches1[1], $matches2[1]);
        }

        return array_unique($strings);
    }
}
