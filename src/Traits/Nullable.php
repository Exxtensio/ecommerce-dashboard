<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Nullable
{
    public bool $nullable = false;

    public function nullable(): static
    {
        $this->nullable = true;

        return $this;
    }
}
