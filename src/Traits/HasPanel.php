<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasPanel
{
    public string $panel = 'overview';

    public function panel($name): static
    {
        $this->panel = $name;

        return $this;
    }
}
