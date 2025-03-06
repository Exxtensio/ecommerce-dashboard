<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

abstract class FieldElement
{
    use Traits\Metable,
        Traits\Makeable;

    public string $component;

    public bool $showOnColumns = true;
    public bool $showOnIndex = true;
    public bool $showOnDetail = true;
    public bool $showOnCreation = true;
    public bool $showOnUpdate = true;

    public function hideFromIndex(): static
    {
        $this->showOnIndex = false;

        return $this;
    }

    public function hideFromColumns(): static
    {
        $this->showOnColumns = false;

        return $this;
    }

    public function hideFromDetail(): static
    {
        $this->showOnDetail = false;

        return $this;
    }

    public function hideWhenCreating(): static
    {
        $this->showOnCreation = false;

        return $this;
    }

    public function hideWhenUpdating(): static
    {
        $this->showOnUpdate = false;

        return $this;
    }

    public function hideEveryWhere(): static
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }

    public function showOnIndex(bool $bool = true): static
    {
        $this->showOnIndex = $bool;

        return $this;
    }

    public function showOnDetail(bool $bool = true): static
    {
        $this->showOnDetail = $bool;

        return $this;
    }

    public function showOnCreating(bool $bool = true): static
    {
        $this->showOnCreation = $bool;

        return $this;
    }

    public function showOnUpdating(bool $bool = true): static
    {
        $this->showOnUpdate = $bool;

        return $this;
    }

    public function onlyOnIndex(): static
    {
        $this->showOnIndex = true;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }

    public function onlyOnDetail(): static
    {
        $this->showOnIndex = false;
        $this->showOnDetail = true;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }

    public function onlyOnForms(): static
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = true;
        $this->showOnUpdate = true;

        return $this;
    }

    public function exceptOnForms(): static
    {
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }
}
