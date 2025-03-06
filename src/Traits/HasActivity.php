<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Exxtensio\EcommerceDashboard\LogOptions;

trait HasActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
