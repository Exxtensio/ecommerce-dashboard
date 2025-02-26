<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Exxtensio\EcommerceDashboard\Database\Factories\UserFactory;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Relations;

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

    public function reviews(): Relations\HasMany
    {
        return $this->hasMany(
            ProductReview::class,
            'user_id'
        );
    }

    public function orders(): Relations\HasMany
    {
        return $this->hasMany(
            Order::class,
            'user_id'
        );
    }

    public function cart(): Relations\HasOne
    {
        return $this->hasOne(
            Cart::class,
            'user_id'
        );
    }
}
