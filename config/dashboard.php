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
        'product_status' => 'published',
    ],
    'product_statuses' => [
        'pending' => 'yellow',
        'published' => 'green',
        'draft' => 'red'
    ],
    'key' => env('SELLEXX_DASHBOARD_KEY'),
    'secret' => env('SELLEXX_DASHBOARD_SECRET'),
    'language' => env('SELLEXX_DASHBOARD_LANGUAGE', 'en'),
    'exchangerateApiKey' => env('ECOMMERCE_EXCHANGERATE_API_KEY', '376fcc872c85403fd0f3704b'),
];
