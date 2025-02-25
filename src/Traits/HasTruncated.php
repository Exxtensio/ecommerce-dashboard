<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasTruncated
{
    public bool $truncated = false;

    public function truncate(): static
    {
        $this->truncated = true;

        return $this;
    }
}
