<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Exxtensio\EcommerceCore\Traits\HasAnotherPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasAnotherPrimaryKey;

    protected $fillable = [
        'model_type',
        'model_id',
        'locale',
        'column',
        'value'
    ];
}
