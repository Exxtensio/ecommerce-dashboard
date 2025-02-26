<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class Activities extends Field
{
    use Related;

    public string $component = 'activities-field';
    public string $foreignKey;
    public string $morphType;
    public bool $showOnIndex = false;
    public bool $showOnCreation = false;
    public bool $showOnColumns = false;
    public bool $relatable = true;
    public bool $readonly = true;
    public string $panel = 'activities';
    public ?string $width = 'col-span-2';
}
