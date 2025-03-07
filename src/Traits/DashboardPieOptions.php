<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Exxtensio\EcommerceDashboard\Models\Country;
use Illuminate\Support\Facades\DB;

trait DashboardPieOptions
{
    protected function getPieCardResponse($query, $dates, $count): array
    {
        $data = DB::table('orders')
            ->whereBetween('orders.created_at', $dates)
            ->where('orders.country', $query[1])
            ->when($query[2] !== 'all', function ($q) use ($query) {
                $q->where('orders.status', $query[2]);
            })
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->when($query[4] === 'brand', function ($q) use ($query) {
                $q->leftJoin('product_brands', 'products.product_brand_id', '=', 'product_brands.id');
            })
            ->when($query[4] === 'categories', function ($q) use ($query) {
                $q->leftJoin('product_category', 'products.id', '=', 'product_category.product_id')
                    ->leftJoin('product_categories', 'product_category.product_category_id', '=', 'product_categories.id');
            })
            ->when($query[4] === 'attributes', function ($q) use ($query) {
                $q->leftJoin('product_attribute', 'products.id', '=', 'product_attribute.product_id')
                    ->leftJoin('product_attributes', 'product_attribute.product_attribute_id', '=', 'product_attributes.id')
                    ->where('product_attributes.key', $query[5]);
            })
            ->select(collect([
                $query[4] === 'brand' ? 'product_brands.name as name' : null,
                $query[4] === 'categories' ? 'product_categories.name as name' : null,
                $query[4] === 'attributes' ? 'product_attributes.value as name' : null,
                $query[3] === 'amount' ? DB::raw('cast(sum(order_items.price * order_items.quantity) as unsigned) as value') : null,
                $query[3] === 'quantity' ? DB::raw('cast(sum(order_items.quantity) as unsigned) as value') : null,
            ])->filter()->toArray())
            ->groupBy(collect([
                $query[4] === 'brand' ? 'product_brands.id' : null,
                $query[4] === 'categories' ? 'product_categories.id' : null,
                $query[4] === 'attributes' ? 'product_attributes.id' : null,
            ])->filter()->toArray())
            ->orderBy('value', 'desc')
            ->take($count);

        return [
            'title' => $this->title,
            'country' => Country::findByCode($query[1])->only('name', 'code', 'flag'),
            'by' => $query[3],
            'data' => $data->get()
        ];
    }
}
