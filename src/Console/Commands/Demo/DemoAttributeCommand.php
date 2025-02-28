<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Exxtensio\EcommerceDashboard\Models\ProductAttribute;

class DemoAttributeCommand extends Command
{
    protected $signature = 'dashboard:demo-attribute';

    public function handle(): void
    {
        $this->info('Step 6: Processing attributes data...');

        ProductAttribute::factory()->count(17)->sequence(
            ['key' => 'Color', 'value' => 'Black'],
            ['key' => 'Color', 'value' => 'White'],
            ['key' => 'Color', 'value' => 'Gray'],
            ['key' => 'Color', 'value' => 'Brown'],
            ['key' => 'Color', 'value' => 'Beige'],
            ['key' => 'Color', 'value' => 'Red'],
            ['key' => 'Color', 'value' => 'Blue'],
            ['key' => 'Color', 'value' => 'Yellow'],
            ['key' => 'Color', 'value' => 'Green'],
            ['key' => 'Color', 'value' => 'Orange'],
            ['key' => 'Color', 'value' => 'Purple'],
            ['key' => 'Color', 'value' => 'Cyan'],
            ['key' => 'Color', 'value' => 'Magenta'],
            ['key' => 'Color', 'value' => 'Lime'],
            ['key' => 'Color', 'value' => 'Teal'],
            ['key' => 'Color', 'value' => 'Amber'],
            ['key' => 'Color', 'value' => 'Violet']
        )->create();
        ProductAttribute::factory()->count(7)->sequence(
            ['key' => 'Clothing Size', 'value' => 'XS'],
            ['key' => 'Clothing Size', 'value' => 'S'],
            ['key' => 'Clothing Size', 'value' => 'M'],
            ['key' => 'Clothing Size', 'value' => 'L'],
            ['key' => 'Clothing Size', 'value' => 'XL'],
            ['key' => 'Clothing Size', 'value' => '2XL'],
            ['key' => 'Clothing Size', 'value' => '3XL']
        )->create();
        ProductAttribute::factory()->count(24)->sequence(
            ['key' => 'Shoe Size (US)', 'value' => '0'],
            ['key' => 'Shoe Size (US)', 'value' => '1'],
            ['key' => 'Shoe Size (US)', 'value' => '2'],
            ['key' => 'Shoe Size (US)', 'value' => '3'],
            ['key' => 'Shoe Size (US)', 'value' => '4'],
            ['key' => 'Shoe Size (US)', 'value' => '4.5'],
            ['key' => 'Shoe Size (US)', 'value' => '5'],
            ['key' => 'Shoe Size (US)', 'value' => '5.5'],
            ['key' => 'Shoe Size (US)', 'value' => '6'],
            ['key' => 'Shoe Size (US)', 'value' => '6.5'],
            ['key' => 'Shoe Size (US)', 'value' => '7'],
            ['key' => 'Shoe Size (US)', 'value' => '7.5'],
            ['key' => 'Shoe Size (US)', 'value' => '8'],
            ['key' => 'Shoe Size (US)', 'value' => '8.5'],
            ['key' => 'Shoe Size (US)', 'value' => '9'],
            ['key' => 'Shoe Size (US)', 'value' => '9.5'],
            ['key' => 'Shoe Size (US)', 'value' => '10'],
            ['key' => 'Shoe Size (US)', 'value' => '10.5'],
            ['key' => 'Shoe Size (US)', 'value' => '11'],
            ['key' => 'Shoe Size (US)', 'value' => '11.5'],
            ['key' => 'Shoe Size (US)', 'value' => '12'],
            ['key' => 'Shoe Size (US)', 'value' => '13'],
            ['key' => 'Shoe Size (US)', 'value' => '14'],
            ['key' => 'Shoe Size (US)', 'value' => '15']
        )->create();
        ProductAttribute::factory()->count(4)->sequence(
            ['key' => 'General Size', 'value' => 'Small'],
            ['key' => 'General Size', 'value' => 'Medium'],
            ['key' => 'General Size', 'value' => 'Large'],
            ['key' => 'General Size', 'value' => 'Extra Large']
        )->create();
        ProductAttribute::factory()->count(4)->sequence(
            ['key' => 'Packaging Size', 'value' => 'Mini'],
            ['key' => 'Packaging Size', 'value' => 'Standard'],
            ['key' => 'Packaging Size', 'value' => 'Jumbo'],
            ['key' => 'Packaging Size', 'value' => 'Mega']
        )->create();
        ProductAttribute::factory()->count(18)->sequence(
            ['key' => 'Weight', 'value' => '100'],
            ['key' => 'Weight', 'value' => '150'],
            ['key' => 'Weight', 'value' => '200'],
            ['key' => 'Weight', 'value' => '250'],
            ['key' => 'Weight', 'value' => '300'],
            ['key' => 'Weight', 'value' => '350'],
            ['key' => 'Weight', 'value' => '400'],
            ['key' => 'Weight', 'value' => '450'],
            ['key' => 'Weight', 'value' => '500'],
            ['key' => 'Weight', 'value' => '550'],
            ['key' => 'Weight', 'value' => '600'],
            ['key' => 'Weight', 'value' => '650'],
            ['key' => 'Weight', 'value' => '700'],
            ['key' => 'Weight', 'value' => '750'],
            ['key' => 'Weight', 'value' => '800'],
            ['key' => 'Weight', 'value' => '850'],
            ['key' => 'Weight', 'value' => '900'],
            ['key' => 'Weight', 'value' => '950']
        )->create();
        ProductAttribute::factory()->count(36)->sequence(
            ['key' => 'Material', 'value' => 'Cotton'],
            ['key' => 'Material', 'value' => 'Linen'],
            ['key' => 'Material', 'value' => 'Silk'],
            ['key' => 'Material', 'value' => 'Wool'],
            ['key' => 'Material', 'value' => 'Polyester'],
            ['key' => 'Material', 'value' => 'Nylon'],
            ['key' => 'Material', 'value' => 'Velvet'],
            ['key' => 'Material', 'value' => 'Denim'],
            ['key' => 'Material', 'value' => 'Leather'],
            ['key' => 'Material', 'value' => 'Suede'],
            ['key' => 'Material', 'value' => 'Tweed'],
            ['key' => 'Material', 'value' => 'Wood'],
            ['key' => 'Material', 'value' => 'Bamboo'],
            ['key' => 'Material', 'value' => 'Cork'],
            ['key' => 'Material', 'value' => 'Rattan'],
            ['key' => 'Material', 'value' => 'Paper'],
            ['key' => 'Material', 'value' => 'Steel'],
            ['key' => 'Material', 'value' => 'Aluminum'],
            ['key' => 'Material', 'value' => 'Brass'],
            ['key' => 'Material', 'value' => 'Copper'],
            ['key' => 'Material', 'value' => 'Gold'],
            ['key' => 'Material', 'value' => 'Silver'],
            ['key' => 'Material', 'value' => 'Titanium'],
            ['key' => 'Material', 'value' => 'Brick'],
            ['key' => 'Material', 'value' => 'Concrete'],
            ['key' => 'Material', 'value' => 'Glass'],
            ['key' => 'Material', 'value' => 'Marble'],
            ['key' => 'Material', 'value' => 'Granite'],
            ['key' => 'Material', 'value' => 'Plastic'],
            ['key' => 'Material', 'value' => 'Rubber'],
            ['key' => 'Material', 'value' => 'Foam'],
            ['key' => 'Material', 'value' => 'Faux Leather'],
            ['key' => 'Material', 'value' => 'Microfiber'],
            ['key' => 'Material', 'value' => 'Velour'],
            ['key' => 'Material', 'value' => 'Acrylic'],
            ['key' => 'Material', 'value' => 'Mesh']
        )->create();
        ProductAttribute::factory()->count(3)->sequence(
            ['key' => 'Gender', 'value' => 'Male'],
            ['key' => 'Gender', 'value' => 'Female'],
            ['key' => 'Gender', 'value' => 'Unisex']
        )->create();
        ProductAttribute::factory()->count(16)->sequence(
            ['key' => 'Lining', 'value' => 'Cotton lining'],
            ['key' => 'Lining', 'value' => 'Silk lining'],
            ['key' => 'Lining', 'value' => 'Wool lining'],
            ['key' => 'Lining', 'value' => 'Linen lining'],
            ['key' => 'Lining', 'value' => 'Polyester lining'],
            ['key' => 'Lining', 'value' => 'Viscose lining'],
            ['key' => 'Lining', 'value' => 'Nylon lining'],
            ['key' => 'Lining', 'value' => 'Fleece lining'],
            ['key' => 'Lining', 'value' => 'Satin lining'],
            ['key' => 'Lining', 'value' => 'Foam lining'],
            ['key' => 'Lining', 'value' => 'Mesh lining'],
            ['key' => 'Lining', 'value' => 'Velvet lining'],
            ['key' => 'Lining', 'value' => 'Suede lining'],
            ['key' => 'Lining', 'value' => 'Faux fur lining'],
            ['key' => 'Lining', 'value' => 'Shearling lining'],
            ['key' => 'Lining', 'value' => 'Taffeta lining']
        )->create();
        ProductAttribute::factory()->count(14)->sequence(
            ['key' => 'Country of Origin', 'value' => 'China'],
            ['key' => 'Country of Origin', 'value' => 'United States'],
            ['key' => 'Country of Origin', 'value' => 'Germany'],
            ['key' => 'Country of Origin', 'value' => 'Japan'],
            ['key' => 'Country of Origin', 'value' => 'South Korea'],
            ['key' => 'Country of Origin', 'value' => 'India'],
            ['key' => 'Country of Origin', 'value' => 'Mexico'],
            ['key' => 'Country of Origin', 'value' => 'Canada'],
            ['key' => 'Country of Origin', 'value' => 'United Kingdom'],
            ['key' => 'Country of Origin', 'value' => 'France'],
            ['key' => 'Country of Origin', 'value' => 'Italy'],
            ['key' => 'Country of Origin', 'value' => 'Brazil'],
            ['key' => 'Country of Origin', 'value' => 'Indonesia'],
            ['key' => 'Country of Origin', 'value' => 'Turkey']
        )->create();
        ProductAttribute::factory()->count(3)->sequence(
            ['key' => 'Shipping Methods', 'value' => 'DHL'],
            ['key' => 'Shipping Methods', 'value' => 'UPS'],
            ['key' => 'Shipping Methods', 'value' => 'FedEx']
        )->create();
        ProductAttribute::factory()->count(3)->sequence(
            ['key' => 'Sport Type', 'value' => 'Running'],
            ['key' => 'Sport Type', 'value' => 'Yoga'],
            ['key' => 'Sport Type', 'value' => 'Gym']
        )->create();
        ProductAttribute::factory()->count(2)->sequence(
            ['key' => 'Waterproof', 'value' => 'Yes'],
            ['key' => 'Waterproof', 'value' => 'No']
        )->create();
        ProductAttribute::factory()->count(2)->sequence(
            ['key' => 'Hypoallergenic', 'value' => 'Yes'],
            ['key' => 'Hypoallergenic', 'value' => 'No']
        )->create();
    }
}
