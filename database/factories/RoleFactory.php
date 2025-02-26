<?php

namespace Exxtensio\EcommerceDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exxtensio\EcommerceDashboard\Models\Role;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [];
    }
}
