<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Exxtensio\EcommerceDashboard\Database\Factories\UserFactory;
use Exxtensio\EcommerceDashboard\Traits;

class User extends \App\Models\User
{
    use HasApiTokens,
        HasFactory,
        Traits\HasRoles,
        Traits\HasActivity,
        Traits\CausesActivity;

    protected $table = 'users';
    public string $guard_name = 'web';

    protected static function newFactory(): Factory|UserFactory|null
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];
}
