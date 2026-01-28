<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\AutoTranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $locales = ['en', 'ar', 'hy', 'fr'];

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(AutoTranslationService $translator): void
    {
        Log::info("Starting background translation for Product ID: {$this->product->id}");

        $hasUpdates = false;

        foreach (['name', 'description'] as $field) {
            // Get current translations directly from the model attributes to avoid caching issues
            // We reload instance to ensure freshness
            $translations = $this->product->getTranslations($field);

            if (empty($translations))
                continue;

            // Determine source text (first available)
            $sourceLocale = array_key_first($translations);
            $sourceText = $translations[$sourceLocale];

            if (empty($sourceText))
                continue;

            $fieldChanged = false;
            foreach ($this->locales as $locale) {
                // Only translate if missing or empty
                if (!isset($translations[$locale]) || empty($translations[$locale])) {
                    try {
                        $translated = $translator->translate($sourceText, $locale);
                        if (!empty($translated)) {
                            $translations[$locale] = $translated;
                            $fieldChanged = true;
                            $hasUpdates = true;
                        }
                        // Small delay to be nice to the API
                        usleep(100000); // 100ms
                    } catch (\Exception $e) {
                        Log::error("Failed to translate product {$this->product->id} field {$field} to {$locale}: " . $e->getMessage());
                    }
                }
            }

            if ($fieldChanged) {
                $this->product->setTranslations($field, $translations);
            }
        }

        if ($hasUpdates) {
            // Save quietly to avoid triggering the observer recursively (if we had logic there)
            // But here our observer listens to 'saving', so saving again WOULD trigger it.
            // However, our observer will see that fields are now filled, so it will do nothing.
            // So just save() is fine.
            $this->product->saveQuietly();
            Log::info("Updated translations for Product ID: {$this->product->id}");
        }
    }
}
