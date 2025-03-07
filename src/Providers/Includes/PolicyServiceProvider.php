<?php

namespace Exxtensio\EcommerceDashboard\Providers\Includes;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Exxtensio\EcommerceDashboard\Models;
use Exxtensio\EcommerceDashboard\Policies;

class PolicyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Models\Activity::class, Policies\ActivityPolicy::class);
        Gate::policy(Models\Cart::class, Policies\CartPolicy::class);
        Gate::policy(Models\Country::class, Policies\CountryPolicy::class);
        Gate::policy(Models\Currency::class, Policies\CurrencyPolicy::class);
        Gate::policy(Models\Order::class, Policies\OrderPolicy::class);
        Gate::policy(Models\Permission::class, Policies\PermissionPolicy::class);
        Gate::policy(Models\Product::class, Policies\ProductPolicy::class);
        Gate::policy(Models\ProductAttribute::class, Policies\ProductAttributePolicy::class);
        Gate::policy(Models\ProductBrand::class, Policies\ProductBrandPolicy::class);
        Gate::policy(Models\ProductCategory::class, Policies\ProductCategoryPolicy::class);
        Gate::policy(Models\ProductReview::class, Policies\ProductReviewPolicy::class);
        Gate::policy(Models\Role::class, Policies\RolePolicy::class);
        Gate::policy(Models\User::class, Policies\UserPolicy::class);
    }

    public function register(): void {}
}
