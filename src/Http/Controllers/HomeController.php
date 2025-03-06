<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Exxtensio\EcommerceDashboard\Models;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $range = Cache::get('sellexx.dashboard.range') ?? [
            today()->subDays(6)->startOfDay()->toISOString(),
            today()->startOfDay()->toISOString()
        ];

        Models\Dashboard::all()->each(function ($i) use ($range) {
            $i->start = $range[0];
            $i->end = $range[1];
            $i->save();
        });

        return Inertia::render('Dashboard/Index', [
            'currentRange' => $range,
            'currentData' => Models\Dashboard::all()->toArray(),
            'canDelete' => $request->user()->hasRole('artisan') || $request->user()->hasPermissionTo('deleteDashboard'),
            'canCreate' => $request->user()->hasRole('artisan') || $request->user()->hasPermissionTo('createDashboard'),
            'canViewAny' => $request->user()->hasRole('artisan') || $request->user()->hasPermissionTo('viewAnyDashboard'),
            'options' => [
                'roles' => Models\Role::pluck('name')
                    ->map(fn($role) => ['name' => Str::title($role), 'value' => $role])
                    ->toArray(),
                'productStatuses' => collect(Models\Product::getStatusesMap())
                    ->keys()
                    ->map(fn($status) => ['name' => Str::title(str_replace('_', ' ', $status)), 'value' => $status])
                    ->prepend(['name' => 'All', 'value' => 'all'])
                    ->toArray(),
                'ratings' => collect([1,2,3,4,5])->map(fn($r) => ['name' => $r, 'value' => $r])
                    ->toArray(),
                'countries' => Models\Country::where('active', 1)
                    ->pluck('name', 'code')
                    ->map(fn($v, $k) => ['name' => $v, 'value' => $k])
                    ->values(),
                'orderTypes' => [
                    ['name' => 'Status', 'value' => 'status'],
                    ['name' => 'Payment Status', 'value' => 'payment_status']
                ],
                'orderStatuses' => collect(Models\Order::getStatusesMap())
                    ->keys()
                    ->map(fn($status) => ['name' => Str::title(str_replace('_', ' ', $status)), 'value' => $status])
                    ->prepend(['name' => 'All', 'value' => 'all'])
                    ->toArray(),
                'orderPaymentStatuses' => collect(Models\Order::getPaymentStatusesMap())
                    ->keys()
                    ->map(fn($status) => ['name' => Str::title(str_replace('_', ' ', $status)), 'value' => $status])
                    ->prepend(['name' => 'All', 'value' => 'all'])
                    ->toArray(),
                'xAxis' => [
                    ['name' => 'Amount', 'value' => 'amount'],
                    ['name' => 'Quantity', 'value' => 'quantity']
                ],
                'yAxis' => [
                    ['name' => 'Brands', 'value' => 'brand'],
                    ['name' => 'Categories', 'value' => 'categories'],
                    ['name' => 'Attributes', 'value' => 'attributes']
                ],
                'yAxisKeys' => Models\ProductAttribute::pluck('key')
                    ->unique()
                    ->values()
                    ->map(fn($key) => ['name' => $key, 'value' => $key])
                    ->toArray(),
                'horizontalCharts' => [
                    ['name' => 'Simple Horizontal Bar', 'value' => 'exx-simple-horizontal-bar-card'],
                    ['name' => 'Simple Line', 'value' => 'exx-simple-line-card']
                ],
                'pieCharts' => [
                    ['name' => 'Simple Pie', 'value' => 'exx-simple-pie-card'],
                    ['name' => 'Pie with Variable Radius', 'value' => 'exx-pie-with-variable-radius-card'],
                    ['name' => 'Simple Donut', 'value' => 'exx-simple-donut-card'],
                ],
            ]
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'title' => [
                Rule::requiredIf($request->get('model') === 'orders' && ($request->get('type') === 'horizontal' || $request->get('type') === 'pie'))
            ],
            'chart' => [
                Rule::requiredIf($request->get('model') === 'orders' && ($request->get('type') === 'horizontal' || $request->get('type') === 'pie'))
            ],
            'model' => 'required',
            'value' => [Rule::requiredIf(in_array($request->get('model'), ['users', 'products', 'reviews', 'carts', 'orders']))],
            'column' => [Rule::requiredIf($request->get('model') === 'orders')],
            'column_value' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'total')],
            'x_axis' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'horizontal')],
            'by_value' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'pie')],
            'y_axis' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'horizontal')],
            'relation' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'pie')],
            'y_axis_key' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'horizontal' && $request->get('y_axis') === 'attributes')],
            'relation_key' => [Rule::requiredIf($request->get('model') === 'orders' && $request->get('type') === 'pie' && $request->get('relation') === 'attributes')],
            'type' => 'required',
            'position' => 'required',
        ]);

        $model = Models\Dashboard::where('type', $request->get('type'))
            ->where('position', $request->get('position'))
            ->first();

        $query = "{$request->get('model')}:{$request->get('value')}:{$request->get('column')}:{$request->get('column_value')}";
        if ($request->get('type') === 'horizontal') {
            $query = "{$request->get('model')}:{$request->get('value')}:{$request->get('column')}:{$request->get('x_axis')}:{$request->get('y_axis')}:{$request->get('y_axis_key')}";
        } else if ($request->get('type') === 'pie') {
            $query = "{$request->get('model')}:{$request->get('value')}:{$request->get('column')}:{$request->get('by_value')}:{$request->get('relation')}:{$request->get('relation_key')}";
        }

        if($model) {
            $model->update([
                'start' => $request->get('range')[0],
                'end' => $request->get('range')[1],
                'query' => $query
            ]);
        } else {
            Models\Dashboard::create([
                'title' => $request->get('title'),
                'chart' => $request->get('chart'),
                'type' => $request->get('type'),
                'position' => $request->get('position'),
                'start' => $request->get('range')[0],
                'end' => $request->get('range')[1],
                'query' => $query
            ]);
        }

        return response()->json(
            Models\Dashboard::all()
        );
    }

    public function delete(Request $request): JsonResponse
    {
        Models\Dashboard::destroy($request->route('id'));
        return response()->json(
            Models\Dashboard::all()
        );
    }

    public function updateRange(Request $request): JsonResponse
    {
        Cache::put('sellexx.dashboard.range', $request->get('range'), now()->endOfDay());
        $data = Models\Dashboard::all()->each(function ($i) use ($request) {
            $i->start = $request->get('range')[0];
            $i->end = $request->get('range')[1];
            $i->save();
        });
        return response()->json(
            $data
        );
    }
}
