<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasPlaceholder
{
    public ?string $placeholder = null;

    public function placeholder(string $text): static
    {
        $this->placeholder = $text;

        return $this;
    }
}
