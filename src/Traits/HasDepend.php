<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasDepend
{
    public ?string $dependOn = null;
    public ?array $dependOnOptions = null;

    public function dependOn(string $attribute, array $dependOnOptions): static
    {
        $this->dependOn = $attribute;
        $this->dependOnOptions = $dependOnOptions;

        return $this;
    }
}
