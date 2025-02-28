<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'dashboard:test';
    protected $description = 'Command description';

    public function handle(): void
    {


//        $collection = collect([
//            'global' => ['viewDashboard'],
//            'activity' => [
//                'Activity viewAny',
//                'Activity view'
//            ],
//            'cart' => [
//                'Cart viewAny',
//                'Cart view'
//            ],
//            'order' => [
//                'Order viewAny',
//                'Order view'
//            ],
//            'country' => [
//                'Country viewAny',
//                'Country view',
//                'Country create',
//                'Country update'
//            ],
//            'currency' => [
//                'Currency viewAny',
//                'Currency view',
//                'Currency create',
//                'Currency update'
//            ],
//            'permission' => [
//                'Permission viewAny',
//                'Permission view',
//                'Permission create',
//                'Permission update',
//                'Permission delete',
//                'Permission restore',
//                'Permission forceDelete',
//            ],
//            'product' => [
//                'Product viewAny',
//                'Product view',
//                'Product create',
//                'Product update',
//                'Product delete',
//                'Product restore',
//                'Product forceDelete',
//            ],
//            'Models\ProductAttribute' => [
//                'Models\ProductAttribute viewAny',
//                'Models\ProductAttribute view',
//                'Models\ProductAttribute create',
//                'Models\ProductAttribute update',
//                'Models\ProductAttribute delete',
//                'Models\ProductAttribute restore',
//                'Models\ProductAttribute forceDelete',
//            ],
//            'productBrand' => [
//                'ProductBrand viewAny',
//                'ProductBrand view',
//                'ProductBrand create',
//                'ProductBrand update',
//                'ProductBrand delete',
//                'ProductBrand restore',
//                'ProductBrand forceDelete',
//            ],
//            'productCategory' => [
//                'ProductCategory viewAny',
//                'ProductCategory view',
//                'ProductCategory create',
//                'ProductCategory update',
//                'ProductCategory delete',
//                'ProductCategory restore',
//                'ProductCategory forceDelete',
//            ],
//            'productReview' => [
//                'ProductReview viewAny',
//                'ProductReview view',
//                'ProductReview update',
//                'ProductReview delete',
//                'ProductReview restore',
//                'ProductReview forceDelete',
//            ],
//            'role' => [
//                'Role viewAny',
//                'Role view',
//                'Role create',
//                'Role update',
//                'Role delete',
//                'Role restore',
//                'Role forceDelete',
//            ],
//            'user' => [
//                'User viewAny',
//                'User view',
//                'User create',
//                'User update',
//                'User delete',
//                'User restore',
//                'User forceDelete',
//            ]
//        ]);
//
//        $collection->map(function ($item, $group) {
//            collect($item)->map(function ($i) use ($group) {
//                Permission::firstOrCreate(
//                    ['name' => $i],
//                    ['group' => $group]
//                );
//            });
//        });
    }
}
