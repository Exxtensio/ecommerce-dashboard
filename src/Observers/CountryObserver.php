<?php

namespace Exxtensio\EcommerceDashboard\Observers;

use Exxtensio\EcommerceDashboard\Models\Country as ObserverModel;
use Exception;

class CountryObserver
{
    /** @throws Exception */
    public function creating(ObserverModel $model): void
    {
        $this->reviseActive($model);
    }

    /** @throws Exception */
    public function updating(ObserverModel $model): void
    {
        $this->reviseActive($model);
    }

    public function deleted(ObserverModel $model): void
    {
        //
    }

    public function restored(ObserverModel $model): void
    {
        //
    }

    public function forceDeleted(ObserverModel $model): void
    {
        //
    }

    /** @throws Exception */
    protected function reviseActive(ObserverModel $model): void
    {
        if(!$model->getAttribute('active')) {
            $exists = ObserverModel::query()->where('id', '!=', $model->id)
                    ->where('active', true)
                    ->exists();
            if(!$exists)
                throw new Exception('At least one active country is required.');
            request()->request->remove('currency');
        }
    }
}
