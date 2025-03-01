<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Separatable
{
    public bool $separatable = false;

    public function separatable(): static
    {
        $this->separatable = true;

        return $this;
    }
}
