<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasHelpText
{
    public ?string $helpText = null;

    public function help(string $text): static
    {
        $this->helpText = $text;

        return $this;
    }

    public function getHelpText(): string
    {
        return $this->helpText;
    }
}
