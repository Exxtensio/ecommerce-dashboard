<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Exxtensio\EcommerceDashboard\Models\Activity;

trait CausesActivity
{
    public function actions(): MorphMany
    {
        return $this->morphMany(Activity::class, 'causer');
    }
}
