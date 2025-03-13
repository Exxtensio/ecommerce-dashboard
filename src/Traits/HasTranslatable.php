<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasTranslatable
{
    public bool $translatable = false;
    public array $translations = [];

    public function translatable(): static
    {
        $this->translatable = true;

        return $this;
    }
}
