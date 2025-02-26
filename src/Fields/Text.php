<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Text extends Field
{
    use Traits\HasCopyable,
        Traits\HasPlaceholder,
        Traits\HasTruncated;

    public string $component = 'text-field';
}
