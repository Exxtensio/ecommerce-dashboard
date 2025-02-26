<?php

namespace Exxtensio\EcommerceDashboard;

class ActivityLogStatus
{
    protected bool $enabled = true;

    public function enable(): bool
    {
        return $this->enabled = true;
    }

    public function disable(): bool
    {
        return $this->enabled = false;
    }

    public function disabled(): bool
    {
        return $this->enabled === false;
    }
}
