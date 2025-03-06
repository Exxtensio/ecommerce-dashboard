<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Exxtensio\EcommerceDashboard\Observers\CountryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Exxtensio\EcommerceDashboard\Traits;

#[ObservedBy([CountryObserver::class])]
class Country extends \Exxtensio\EcommerceCore\Models\Geo\Country
{
    use Traits\HasActivity;

    protected $appends = [
        'flag'
    ];

    public function getFlagAttribute(): ?string
    {
        $flagSVG = strtolower($this->code).'.svg';
        $flagPath = "vendor-dashboard-data/flags/4x3/$flagSVG";
        return file_exists(public_path($flagPath)) ? asset($flagPath) : null;
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
