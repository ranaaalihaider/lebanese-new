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

    public function saved(Product $product)
    {
        // Dispatch background job to handle translations
        // This ensures the user doesn't have to wait for the API call
        \App\Jobs\TranslateProduct::dispatch($product);
    }
}
