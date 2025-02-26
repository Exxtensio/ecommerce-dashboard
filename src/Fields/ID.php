<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class ID extends Field
{
    use Traits\HasCopyable,
        Traits\HasPlaceholder;

    public string $component = 'id-field';
    public bool $locked = false;
    public bool $sortable = true;
    public bool $readonly = true;
    public bool $showOnCreation = false;
    public bool $showOnUpdate = false;
    public bool $showOnDetail = false;

    public function hide(): static
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }

    public function show(): static
    {
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnCreation = true;
        $this->showOnUpdate = true;

        return $this;
    }
}
