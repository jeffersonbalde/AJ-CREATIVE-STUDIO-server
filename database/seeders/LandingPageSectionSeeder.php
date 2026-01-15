<?php

namespace Database\Seeders;

use App\Models\LandingPageSection;
use Illuminate\Database\Seeder;

class LandingPageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'title' => 'Our New Arrivals',
                'source_type' => 'collection',
                'source_value' => 'new-arrivals',
                'product_count' => 4,
                'display_style' => 'grid',
                'is_active' => true,
                'display_order' => 1,
                'description' => 'Latest products recently added to the store',
            ],
            [
                'title' => 'Our Best Sellers',
                'source_type' => 'collection',
                'source_value' => 'best-sellers',
                'product_count' => 8,
                'display_style' => 'grid',
                'is_active' => true,
                'display_order' => 2,
                'description' => 'Top-selling products based on customer popularity',
            ],
            [
                'title' => 'Featured Products',
                'source_type' => 'collection',
                'source_value' => 'featured-products',
                'product_count' => 6,
                'display_style' => 'grid',
                'is_active' => false, // Disabled by default
                'display_order' => 3,
                'description' => 'Hand-picked featured products',
            ],
        ];

        foreach ($sections as $section) {
            LandingPageSection::updateOrCreate(
                ['title' => $section['title']],
                $section
            );
        }
    }
}

