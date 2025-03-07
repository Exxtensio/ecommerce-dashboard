<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Exxtensio\EcommerceDashboard\Models;

trait DashboardTotalOptions
{
    protected function getTotalUsersResponse($query, $dates, $diffInDays): array
    {
        $currentPeriod = Models\User::query()
            ->whereBetween('created_at', $dates)
            ->whereHas('roles', function ($q) use ($query) {
                $q->where('name', $query[1]);
            })->count();

        $pastPeriod = Models\User::query()
            ->whereBetween('created_at', $diffInDays)
            ->whereHas('roles', function ($q) use ($query) {
                $q->where('name', $query[1]);
            })->count();

        return [
            'title' => __('New Users'),
            'column' => $query[1],
            'buttonLink' => 'admin/users',
            'buttonName' => __('View all users'),
            'icon' => 'UsersIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalBrandsResponse($dates, $diffInDays): array
    {
        $currentPeriod = Models\ProductBrand::query()
            ->whereBetween('created_at', $dates)
            ->count();

        $pastPeriod = Models\ProductBrand::query()
            ->whereBetween('created_at', $diffInDays)
            ->count();

        return [
            'title' => __('New Brands'),
            'column' => 'all brands',
            'buttonLink' => 'admin/brands',
            'buttonName' => __('View all brands'),
            'icon' => 'TagIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalCategoriesResponse($dates, $diffInDays): array
    {
        $currentPeriod = Models\ProductCategory::query()
            ->whereBetween('created_at', $dates)
            ->count();

        $pastPeriod = Models\ProductCategory::query()
            ->whereBetween('created_at', $diffInDays)
            ->count();

        return [
            'title' => __('New Categories'),
            'column' => 'all categories',
            'buttonLink' => 'admin/categories',
            'buttonName' => __('View all categories'),
            'icon' => 'SquaresPlusIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalAttributesResponse($dates, $diffInDays): array
    {
        $currentPeriod = Models\ProductAttribute::query()
            ->whereBetween('created_at', $dates)
            ->count();

        $pastPeriod = Models\ProductAttribute::query()
            ->whereBetween('created_at', $diffInDays)
            ->count();

        return [
            'title' => __('New Attributes'),
            'column' => 'all attributes',
            'buttonLink' => 'admin/attributes',
            'buttonName' => __('View all attributes'),
            'icon' => 'SwatchIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalReviewsResponse($query, $dates, $diffInDays): array
    {
        $currentPeriod = Models\ProductReview::query()
            ->whereBetween('created_at', $dates)
            ->where('rating', $query[1])
            ->count();

        $pastPeriod = Models\ProductReview::query()
            ->whereBetween('created_at', $diffInDays)
            ->where('rating', $query[1])
            ->count();

        return [
            'title' => __('New Reviews'),
            'column' => $query[1],
            'buttonLink' => 'admin/reviews',
            'buttonName' => __('View all reviews'),
            'icon' => 'StarIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalProductsResponse($query, $dates, $diffInDays): array
    {
        $currentPeriod = Models\Product::query()
            ->whereBetween('created_at', $dates)
            ->when($query[1] !== 'all', function ($q) use ($query) {
                $q->where('status', $query[1]);
            })
            ->count();

        $pastPeriod = Models\Product::query()
            ->whereBetween('created_at', $diffInDays)
            ->when($query[1] !== 'all', function ($q) use ($query) {
                $q->where('status', $query[1]);
            })
            ->count();

        return [
            'title' => __('New Products'),
            'column' => $query[1] ?? 'all products',
            'buttonLink' => 'admin/products',
            'buttonName' => __('View all products'),
            'icon' => 'BuildingStorefrontIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalCartsResponse($query, $dates, $diffInDays): array
    {
        $currentPeriod = Models\Cart::query()
            ->whereBetween('created_at', $dates)
            ->where('country', $query[1])
            ->count();

        $pastPeriod = Models\Cart::query()
            ->whereBetween('created_at', $diffInDays)
            ->where('country', $query[1])
            ->count();

        return [
            'title' => __('New Carts'),
            'column' => $query[1],
            'buttonLink' => 'admin/carts',
            'buttonName' => __('View all carts'),
            'icon' => 'ShoppingCartIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }

    protected function getTotalOrdersResponse($query, $dates, $diffInDays): array
    {
        $currentPeriod = Models\Order::query()
            ->whereBetween('created_at', $dates)
            ->where('country', $query[1])
            ->when(!empty($query[2]) && !empty($query[3]), function ($q) use ($query) {
                $q->where($query[2], $query[3]);
            })
            ->count();

        $pastPeriod = Models\Order::query()
            ->whereBetween('created_at', $diffInDays)
            ->where('country', $query[1])
            ->when(!empty($query[2]) && !empty($query[3]), function ($q) use ($query) {
                $q->where($query[2], $query[3]);
            })
            ->count();

        return [
            'title' => __('New Orders'),
            'column' => "$query[1] ($query[2] = $query[3])",
            'buttonLink' => 'admin/orders',
            'buttonName' => __('View all orders'),
            'icon' => 'CreditCardIcon',
            'current' => $currentPeriod,
            'past' => $pastPeriod,
        ];
    }
}
