<?php

namespace App\Observers;

use App\Jobs\TranslateModel;

class AutoTranslateObserver
{
    public function saved($model)
    {
        // Dispatch generic job for any model that uses translations
        TranslateModel::dispatch($model);
    }
}
