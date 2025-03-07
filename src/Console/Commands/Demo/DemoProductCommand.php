<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Exxtensio\EcommerceDashboard\Models;

class DemoProductCommand extends Command
{
    protected $signature = 'dashboard:demo-product';

    public function handle(): void
    {
        $this->info('Step 7: Processing products data...');
        $brands = Models\ProductBrand::all();
        $categories = Models\ProductCategory::all();

        $attributesColor = Models\ProductAttribute::where('key', 'Color')->get();
        $attributesClothingSize = Models\ProductAttribute::where('key', 'Clothing Size')->get();
        $attributesShoeSizeUS = Models\ProductAttribute::where('key', 'Shoe Size (US)')->get();
        $attributesGeneralSize = Models\ProductAttribute::where('key', 'General Size')->get();
        $attributesPackagingSize = Models\ProductAttribute::where('key', 'Packaging Size')->get();
        $attributesWeight = Models\ProductAttribute::where('key', 'Weight')->get();
        $attributesMaterial = Models\ProductAttribute::where('key', 'Material')->get();
        $attributesGender = Models\ProductAttribute::where('key', 'Gender')->get();
        $attributesLining = Models\ProductAttribute::where('key', 'Lining')->get();
        $attributesCountryOfOrigin = Models\ProductAttribute::where('key', 'Country of Origin')->get();
        $attributesShippingMethods = Models\ProductAttribute::where('key', 'Shipping Methods')->get();
        $attributesSportType = Models\ProductAttribute::where('key', 'Sport Type')->get();
        $attributesWaterproof = Models\ProductAttribute::where('key', 'Waterproof')->get();
        $attributesHypoallergenic = Models\ProductAttribute::where('key', 'Hypoallergenic')->get();

        Models\Product::factory()
            ->count(500)
            ->active()
            ->state(new Sequence(fn (Sequence $sequence) => ['product_brand_id' => $brands->random()->id]))
            ->has(Models\ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'US']), 'prices')
            ->has(Models\ProductStock::factory()->state(fn(array $attributes) => ['country' => 'US']), 'stocks')
            ->afterCreating(function (Models\Product $product) use (
                $attributesColor,
                $attributesClothingSize,
                $attributesShoeSizeUS,
                $attributesGeneralSize,
                $attributesPackagingSize,
                $attributesWeight,
                $attributesMaterial,
                $attributesGender,
                $attributesLining,
                $attributesCountryOfOrigin,
                $attributesShippingMethods,
                $attributesSportType,
                $attributesWaterproof,
                $attributesHypoallergenic,
                $categories
            ) {
                $product->categories()->sync([$categories->random()->id]);
                $product->attributes()->sync([
                    $categories->random()->id,
                    $attributesColor->random()->id,
                    $attributesClothingSize->random()->id,
                    $attributesShoeSizeUS->random()->id,
                    $attributesGeneralSize->random()->id,
                    $attributesPackagingSize->random()->id,
                    $attributesWeight->random()->id,
                    $attributesMaterial->random()->id,
                    $attributesGender->random()->id,
                    $attributesLining->random()->id,
                    $attributesCountryOfOrigin->random()->id,
                    $attributesShippingMethods->random()->id,
                    $attributesSportType->random()->id,
                    $attributesWaterproof->random()->id,
                    $attributesHypoallergenic->random()->id,
                ]);
            })
            ->create();

        Models\Product::factory()
            ->count(100)
            ->draft()
            ->state(new Sequence(fn (Sequence $sequence) => ['product_brand_id' => $brands->random()->id]))
            ->has(Models\ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'US']), 'prices')
            ->has(Models\ProductStock::factory()->state(fn(array $attributes) => ['country' => 'US']), 'stocks')
            ->afterCreating(function (Models\Product $product) use (
                $attributesColor,
                $attributesClothingSize,
                $attributesShoeSizeUS,
                $attributesGeneralSize,
                $attributesPackagingSize,
                $attributesWeight,
                $attributesMaterial,
                $attributesGender,
                $attributesLining,
                $attributesCountryOfOrigin,
                $attributesShippingMethods,
                $attributesSportType,
                $attributesWaterproof,
                $attributesHypoallergenic,
                $categories
            ) {
                $product->categories()->sync([$categories->random()->id]);
                $product->attributes()->sync([
                    $categories->random()->id,
                    $attributesColor->random()->id,
                    $attributesClothingSize->random()->id,
                    $attributesShoeSizeUS->random()->id,
                    $attributesGeneralSize->random()->id,
                    $attributesPackagingSize->random()->id,
                    $attributesWeight->random()->id,
                    $attributesMaterial->random()->id,
                    $attributesGender->random()->id,
                    $attributesLining->random()->id,
                    $attributesCountryOfOrigin->random()->id,
                    $attributesShippingMethods->random()->id,
                    $attributesSportType->random()->id,
                    $attributesWaterproof->random()->id,
                    $attributesHypoallergenic->random()->id,
                ]);
            })
            ->create();

        Models\Product::factory()
            ->count(100)
            ->inactive()
            ->state(new Sequence(fn (Sequence $sequence) => ['product_brand_id' => $brands->random()->id]))
            ->has(Models\ProductPrice::factory()->state(fn(array $attributes) => ['country' => 'US']), 'prices')
            ->has(Models\ProductStock::factory()->state(fn(array $attributes) => ['country' => 'US']), 'stocks')
            ->afterCreating(function (Models\Product $product) use (
                $attributesColor,
                $attributesClothingSize,
                $attributesShoeSizeUS,
                $attributesGeneralSize,
                $attributesPackagingSize,
                $attributesWeight,
                $attributesMaterial,
                $attributesGender,
                $attributesLining,
                $attributesCountryOfOrigin,
                $attributesShippingMethods,
                $attributesSportType,
                $attributesWaterproof,
                $attributesHypoallergenic,
                $categories
            ) {
                $product->categories()->sync([$categories->random()->id]);
                $product->attributes()->sync([
                    $categories->random()->id,
                    $attributesColor->random()->id,
                    $attributesClothingSize->random()->id,
                    $attributesShoeSizeUS->random()->id,
                    $attributesGeneralSize->random()->id,
                    $attributesPackagingSize->random()->id,
                    $attributesWeight->random()->id,
                    $attributesMaterial->random()->id,
                    $attributesGender->random()->id,
                    $attributesLining->random()->id,
                    $attributesCountryOfOrigin->random()->id,
                    $attributesShippingMethods->random()->id,
                    $attributesSportType->random()->id,
                    $attributesWaterproof->random()->id,
                    $attributesHypoallergenic->random()->id,
                ]);
            })
            ->create();
    }
}
