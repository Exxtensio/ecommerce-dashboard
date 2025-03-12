<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;
use Exxtensio\EcommerceDashboard\Traits\Storable;

class Gallery extends Field
{
    use Related, Storable;

    public string $component = 'gallery-field';
}
