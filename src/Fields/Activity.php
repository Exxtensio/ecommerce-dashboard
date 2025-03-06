<?php

namespace Exxtensio\EcommerceDashboard\Fields;

class Activity extends KeyValue
{
    public string $component = 'activity-field';
    public bool $readonly = true;
    public bool $canAddRow = false;
    public bool $canDeleteRow = false;
}
