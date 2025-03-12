<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Text extends Field
{
    use Traits\HasCopyable,
        Traits\HasPlaceholder,
        Traits\HasTruncated,
        Traits\HasTranslatable;

    public string $component = 'text-field';
}
