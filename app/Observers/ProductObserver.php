<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\AutoTranslationService;

class ProductObserver
{
    protected $translator;

    public function __construct(AutoTranslationService $translator)
    {
        $this->translator = $translator;
    }

    public function saving(Product $product)
    {
        $locales = ['en', 'ar', 'hy', 'fr'];

        foreach (['name', 'description'] as $field) {
            $translations = $product->getTranslations($field);

            // If empty, skip
            if (empty($translations))
                continue;

            // Find a source text (prefer valid locale)
            $sourceLocale = array_key_first($translations);
            $sourceText = $translations[$sourceLocale];

            // If source is missing, skip
            if (empty($sourceText))
                continue;

            $hasChanged = false;
            foreach ($locales as $locale) {
                if (!isset($translations[$locale]) || empty($translations[$locale])) {
                    // Translate
                    $translations[$locale] = $this->translator->translate($sourceText, $locale);
                    $hasChanged = true;
                }
            }

            if ($hasChanged) {
                $product->setTranslations($field, $translations);
            }
        }
    }
}
