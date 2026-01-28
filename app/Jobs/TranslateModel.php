<?php

namespace App\Jobs;

use App\Services\AutoTranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TranslateModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $locales = ['en', 'ar', 'hy', 'fr'];

    /**
     * Create a new job instance.
     *
     * @param Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     */
    public function handle(AutoTranslationService $translator): void
    {
        // Safety check: verify model uses Spatie Translatable
        if (!property_exists($this->model, 'translatable')) {
            return;
        }

        Log::info("Starting background translation for Model: " . get_class($this->model) . " ID: {$this->model->id}");

        $hasUpdates = false;

        foreach ($this->model->translatable as $field) {
            // Get current translations directly from the model attributes
            $translations = $this->model->getTranslations($field);

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
                        Log::error("Failed to translate " . get_class($this->model) . " {$this->model->id} field {$field} to {$locale}: " . $e->getMessage());
                    }
                }
            }

            if ($fieldChanged) {
                $this->model->setTranslations($field, $translations);
            }
        }

        if ($hasUpdates) {
            $this->model->saveQuietly();
            Log::info("Updated translations for " . get_class($this->model) . " ID: {$this->model->id}");
        }
    }
}
