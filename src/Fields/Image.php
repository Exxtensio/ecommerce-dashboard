<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Storable;

class Image extends Field
{
    use Storable;

    public string $component = 'image-field';
}
