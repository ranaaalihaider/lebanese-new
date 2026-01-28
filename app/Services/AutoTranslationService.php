<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    protected $tr;
    protected $cachePrefix = 'translation_';
    protected $cacheDuration = 86400; // 24 hours

    public function __construct()
    {
        $this->tr = new GoogleTranslate();
    }

    /**
     * Translate a single string with caching
     */
    public function translate(string $text, string $targetLocale): string
    {
        // Return original if target is English
        if ($targetLocale === 'en') {
            return $text;
        }

        // Check cache first
        $cacheKey = $this->cachePrefix . md5($text . $targetLocale);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Translate
        $this->tr->setSource('en');
        $this->tr->setTarget($targetLocale);

        try {
            $translated = $this->tr->translate($text);

            // Cache the result
            Cache::put($cacheKey, $translated, $this->cacheDuration);

            return $translated;
        } catch (\Exception $e) {
            Log::error("Translation failed for '{$text}' to {$targetLocale}: " . $e->getMessage());
            return $text; // Fallback to original
        }
    }

    /**
     * Batch translate multiple strings
     */
    public function batchTranslate(array $strings, string $targetLocale): array
    {
        $translations = [];

        foreach ($strings as $string) {
            $translations[$string] = $this->translate($string, $targetLocale);

            // Rate limiting: sleep for 100ms between translations
            usleep(100000);
        }

        return $translations;
    }

    /**
     * Scan views and auto-translate strings to JSON files
     */
    public function generateTranslations(?callable $progressCallback = null)
    {
        $locales = ['ar', 'hy', 'fr'];
        $strings = $this->findStringsInViews();

        $totalStrings = count($strings);
        $totalTranslations = $totalStrings * count($locales);
        $currentProgress = 0;

        foreach ($locales as $locale) {
            $path = lang_path("$locale.json");
            $current = File::exists($path) ? json_decode(File::get($path), true) : [];
            $modified = false;

            foreach ($strings as $string) {
                if (!isset($current[$string])) {
                    $current[$string] = $this->translate($string, $locale);
                    $modified = true;
                }

                $currentProgress++;

                if ($progressCallback) {
                    $progressCallback($currentProgress, $totalTranslations, $locale, $string);
                }
            }

            if ($modified) {
                // Sort by key for better readability
                ksort($current);
                File::put($path, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        return [
            'total_strings' => $totalStrings,
            'total_translations' => $totalTranslations,
            'locales' => $locales,
        ];
    }

    /**
     * Find all translatable strings in views
     */
    protected function findStringsInViews()
    {
        $strings = [];
        $files = File::allFiles(resource_path('views'));

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php')
                continue;

            $content = File::get($file);

            // Match __('string') and __("string")
            preg_match_all("/__\(['\"](.+?)['\"]\)/", $content, $matches1);

            // Match @lang('string') and @lang("string")
            preg_match_all("/@lang\(['\"](.+?)['\"]\)/", $content, $matches2);

            // Match {{ trans('string') }}
            preg_match_all("/trans\(['\"](.+?)['\"]\)/", $content, $matches3);

            $strings = array_merge($strings, $matches1[1], $matches2[1], $matches3[1]);
        }

        return array_unique($strings);
    }

    /**
     * Clear translation cache
     */
    public function clearCache()
    {
        Cache::flush();
    }

    /**
     * Get translation statistics
     */
    public function getStats(): array
    {
        $stats = [];
        $locales = ['ar', 'hy', 'fr'];

        foreach ($locales as $locale) {
            $path = lang_path("$locale.json");

            if (File::exists($path)) {
                $translations = json_decode(File::get($path), true);
                $stats[$locale] = count($translations);
            } else {
                $stats[$locale] = 0;
            }
        }

        return $stats;
    }
}

