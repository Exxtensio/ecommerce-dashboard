<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Exxtensio\EcommerceCore\Traits\HasAnotherPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasAnotherPrimaryKey;

    protected $fillable = [
        'name',
        'name_local',
        'code'
    ];
}
