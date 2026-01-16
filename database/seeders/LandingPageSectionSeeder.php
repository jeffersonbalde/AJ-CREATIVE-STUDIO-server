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
            // Hero Slider Section
            [
                'title' => 'Hero Slider',
                'section_type' => 'hero',
                'source_type' => null,
                'source_value' => null,
                'product_count' => 0,
                'display_style' => 'grid',
                'is_active' => true,
                'display_order' => 1,
                'description' => 'Main hero slider section for landing page',
                'status' => 'published',
                'published_at' => now(),
                'config' => [
                    'slides' => [
                        [
                            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1920&h=1080&fit=crop',
                            'title' => 'Premium Digital Products',
                            'subtitle' => 'Download instantly. Use immediately. Create amazing work with our digital assets, templates, and resources.',
                            'buttonText' => 'Browse Products',
                            'buttonLink' => '/all-products',
                            'buttonColor' => '#0066CC',
                            'textColor' => '#000000',
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1920&h=1080&fit=crop',
                            'title' => 'New Digital Templates',
                            'subtitle' => 'Fresh templates, graphics, and digital resources added weekly. Get instant access after purchase.',
                            'buttonText' => 'View New Arrivals',
                            'buttonLink' => '/all-products',
                            'buttonColor' => '#000000',
                            'textColor' => '#000000',
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=1920&h=1080&fit=crop',
                            'title' => 'Instant Downloads',
                            'subtitle' => 'No shipping. No waiting. Get your digital products immediately after purchase. Start creating today!',
                            'buttonText' => 'Shop Digital Products',
                            'buttonLink' => '/all-products',
                            'buttonColor' => '#28a745',
                            'textColor' => '#000000',
                        ],
                    ],
                    'autoplay' => true,
                    'autoplayDelay' => 5000,
                    'showNavigation' => true,
                    'showPagination' => true,
                    'backgroundColor' => '#FFFFFF',
                ],
            ],
            // Product Grid Sections
            [
                'title' => 'Our New Arrivals',
                'section_type' => 'product_grid',
                'source_type' => 'collection',
                'source_value' => 'new-arrivals',
                'product_count' => 4,
                'display_style' => 'grid',
                'is_active' => true,
                'display_order' => 2,
                'description' => 'Latest products recently added to the store',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Our Best Sellers',
                'section_type' => 'product_grid',
                'source_type' => 'collection',
                'source_value' => 'best-sellers',
                'product_count' => 8,
                'display_style' => 'grid',
                'is_active' => true,
                'display_order' => 3,
                'description' => 'Top-selling products based on customer popularity',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Featured Products',
                'section_type' => 'product_grid',
                'source_type' => 'collection',
                'source_value' => 'featured-products',
                'product_count' => 6,
                'display_style' => 'grid',
                'is_active' => false, // Disabled by default
                'display_order' => 4,
                'description' => 'Hand-picked featured products',
                'status' => 'draft',
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

