<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasWidth
{
    public ?string $width = null;

    public function fullWidth(): static
    {
        $this->width = 'col-span-2';

        return $this;
    }
}
