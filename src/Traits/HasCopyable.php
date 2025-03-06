<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasCopyable
{
    public bool $copyable = false;

    public function copyable(): static
    {
        $this->copyable = true;

        return $this;
    }
}
