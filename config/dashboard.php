<?php

return [
    'storage_disk' => 'public',
    'validation_rules' => [
        'brands' => [
            'src' => [
                'dimensions:min_width=200,min_height=200',
                'mimes:jpg,png',
                'max:2048' // 2048 KB = 2 MB
            ]
        ],
        'categories' => [
            'src' => [
                'dimensions:min_width=200,min_height=200',
                'mimes:jpg,png',
                'max:2048' // 2048 KB = 2 MB
            ]
        ],
        'products' => [
            'gallery' => [
                'dimensions:min_width=200,min_height=200',
                'mimes:jpg,png',
                'max:2048' // 2048 KB = 2 MB
            ]
        ]
    ],
    /*
    |---------------------------------------------------------------------------------
    | Type
    |---------------------------------------------------------------------------------
    |
    | This option defines the product type.
    |
    | Available types: "digital", "quantity" (default), "weight", "volume", "length"
    | Digital ("dig"): product that involves downloading some content
    | Quantity ("qty"): quantitative product
    | Weight ("wt"): weight product
    | Volume ("vol"): liquid
    | Length ("len"): product that is measured by length
    |
    |---------------------------------------------------------------------------------
    | Digital & Quantity Unit
    |---------------------------------------------------------------------------------
    |
    | Available unit: one ("o")
    |
    |---------------------------------------------------------------------------------
    | Weight Unit
    |---------------------------------------------------------------------------------
    |
    | Available unit:
    | Megaton ("mt"), Ton ("t"), Kilogram ("kg"), Gram ("g"), Milligram ("mg"),
    | Microgram ("mcg"), Long ton ("lt"), Short ton ("st"), Pound ("lb"),
    | Ounce ("oz"), Drachm ("dr"), Grain ("gr"), Carat ("ct")
    |
    |---------------------------------------------------------------------------------
    | Volume Unit
    |---------------------------------------------------------------------------------
    |
    | Available unit:
    | Cubic Meter ("m3"), Liter ("l"), Milliliter ("ml"), Cubic Centimeter ("cm3"),
    | Cubic Decimeter ("dm3"), Cubic Foot ("ft3"), Cubic Inch ("in3"),
    | Imperial Gallon ("imp-gal"), US Gallon ("gal"), Imperial Quart ("imp-qt"),
    | US Quart ("qt"), Imperial Pint ("imp-pt"), US Pint ("pt"),
    | Imperial Fluid Ounce ("imp-fl-oz"), US Fluid Ounce ("fl-oz")
    |
    |---------------------------------------------------------------------------------
    | Length Unit
    |---------------------------------------------------------------------------------
    |
    | Available unit:
    | Kilometer ("km"), Meter ("m"), Centimeter ("cm"), Millimeter ("mm"),
    | Micrometer ("mcm"), Nanometer ("nm"), Mile ("mi"), Yard ("yd"), Foot ("ft")
    | Inch ("in"), Nautical Mile ("nmi")
    |
    */
    'defaults' => [
        'product_type' => null,
        'product_unit' => null,
        'product_places' => 0,
        'product_step' => 1,
        'product_min' => 1,
        'product_max' => 99,
        'product_status' => 'active',
        'order_statuses' => 'new',
        'order_payment_statuses' => 'processing',
    ],
    'product_statuses' => [
        'active' => 'green', // Active (the product is available for sale)
        'inactive' => 'yellow', // Inactive (temporarily disabled, not displayed)
        'draft' => 'red', // Draft (the product is not yet published)
        'pre_order' => 'yellow', // Pre-Order (the product is not yet available for sale but can be ordered)
        'archived' => 'red', // Archived (removed from the catalog but not deleted)
        'discontinued' => 'red', // Discontinued (the product is no longer being sold)
    ],
    'order_statuses' => [
        'new' => 'yellow', // New (Order has just been processed)
        'pending' => 'yellow', // Pending (Order created but not yet processed)
        'processing' => 'yellow', // Processing (Order is confirmed but not shipped yet)
        'on_hold' => 'yellow', // On Hold (Waiting for payment, verification, or confirmation)
        'completed' => 'yellow', // Completed (Order has been fulfilled and delivered)
        'shipped' => 'yellow', // Shipped (Order has been dispatched)
        'delivered' => 'green', // Delivered (Customer has received the order)
        'canceled' => 'red', // Canceled (Order was canceled by the user or admin)
        'failed' => 'red', // Failed (Order processing failed)
        'refunded' => 'red', // Refunded (Full refund was issued)
        'partially_refunded' => 'red', // Partially Refunded (Partial refund was issued)
        'returned' => 'red', // Returned (The product has been returned by the customer)
        'rejected' => 'red', // Rejected (Order was declined by the store)
    ],
    'order_payment_statuses' => [
        'processing' => 'yellow', // Processing (Awaiting payment)
        'paid' => 'green', // Paid (Payment received successfully)
        'failed' => 'red', // Failed (Payment attempt failed)
        'refunded' => 'red', // Refunded (Full refund issued)
        'partially_refunded' => 'red', // Partially Refunded (Partial refund issued)
        'canceled' => 'red', // Canceled (Payment was not completed)
        'expired' => 'red', // Expired (Payment window has expired)
        'chargeback' => 'red', // Chargeback (Funds were reversed by the bank)
        'on_hold' => 'yellow', // On Hold (Payment temporarily frozen)
        'authorized' => 'yellow', // Authorized (Payment is approved but not captured yet)
    ],
    'key' => env('SELLEXX_DASHBOARD_KEY'),
    'secret' => env('SELLEXX_DASHBOARD_SECRET'),
    'language' => env('SELLEXX_DASHBOARD_LANGUAGE', 'en'),
    'exchangerateApiKey' => env('ECOMMERCE_EXCHANGERATE_API_KEY', '376fcc872c85403fd0f3704b'),
];
