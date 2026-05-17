<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $skincare    = ProductCategory::where('slug', 'skincare')->first();
        $aroma       = ProductCategory::where('slug', 'aromatherapy')->first();
        $bath        = ProductCategory::where('slug', 'bath-body')->first();
        $wellness    = ProductCategory::where('slug', 'wellness')->first();
        $gifts       = ProductCategory::where('slug', 'gift-sets')->first();

        $products = [
            [
                'product_category_id' => $skincare->id,
                'name'        => 'Luminous Hydration Serum',
                'slug'        => 'luminous-hydration-serum',
                'description' => 'Our signature serum harnesses the power of hyaluronic acid, vitamin C, and rare deep-sea algae extract to deliver 72-hour hydration and a visible luminosity lift from the first application.',
                'price'       => 89.00,
                'stock'       => 50,
                'thumbnail'   => 'images/product1.png',
                'gallery'     => ['images/product_1.png', 'images/product_2.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $skincare->id,
                'name'        => 'Rejuvenating Night Cream',
                'slug'        => 'rejuvenating-night-cream',
                'description' => 'Formulated with retinol, peptides, and botanical squalane, this overnight cream works while you sleep to reduce fine lines and restore a youthful, rested complexion by morning.',
                'price'       => 72.00,
                'stock'       => 35,
                'thumbnail'   => 'images/product2.png',
                'gallery'     => ['images/product_3.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $aroma->id,
                'name'        => 'Serenity Essential Oil Blend',
                'slug'        => 'serenity-essential-oil-blend',
                'description' => 'A masterfully balanced blend of lavender, bergamot, and vetiver. Add a few drops to your diffuser to recreate the calming atmosphere of our spa treatment rooms at home.',
                'price'       => 45.00,
                'stock'       => 80,
                'thumbnail'   => 'images/product3.png',
                'gallery'     => ['images/product_4.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $aroma->id,
                'name'        => 'LuxeStay Signature Candle',
                'slug'        => 'luxestay-signature-candle',
                'description' => 'Hand-poured from 100% soy wax and scented with our iconic hotel fragrance — a blend of white jasmine, sandalwood, and warm amber — this candle burns cleanly for over 60 hours.',
                'price'       => 58.00,
                'stock'       => 60,
                'thumbnail'   => 'images/product4.png',
                'gallery'     => ['images/product_5.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $bath->id,
                'name'        => 'Mineral Bath Salts Collection',
                'slug'        => 'mineral-bath-salts-collection',
                'description' => 'Six hand-crafted bath salt blends infused with Himalayan pink salt, Dead Sea minerals, and botanical botanicals. Each blend targets a different wellness intention: detox, relax, energise, sleep, balance, and revive.',
                'price'       => 65.00,
                'stock'       => 45,
                'thumbnail'   => 'images/product5.png',
                'gallery'     => ['images/product_6.png', 'images/product_7.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $bath->id,
                'name'        => 'Exfoliating Body Scrub',
                'slug'        => 'exfoliating-body-scrub',
                'description' => 'Used exclusively in our spa body treatments, this award-winning scrub combines brown sugar, sweet almond oil, and vitamin E to buff skin to a silky, glowing finish.',
                'price'       => 48.00,
                'stock'       => 55,
                'thumbnail'   => 'images/product6.png',
                'gallery'     => ['images/product_8.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $wellness->id,
                'name'        => 'Silk Sleep Mask',
                'slug'        => 'silk-sleep-mask',
                'description' => 'Crafted from 22 momme mulberry silk, our sleep mask blocks 100% of light while the gentle weave prevents creasing and keeps skin hydrated through the night.',
                'price'       => 38.00,
                'stock'       => 100,
                'thumbnail'   => 'images/product7.png',
                'gallery'     => ['images/product_9.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $wellness->id,
                'name'        => 'Weighted Meditation Pillow',
                'slug'        => 'weighted-meditation-pillow',
                'description' => 'Filled with organic buckwheat hulls and lavender buds, this ergonomically shaped pillow provides gentle cervical support during seated meditation, reducing tension and improving posture.',
                'price'       => 55.00,
                'stock'       => 30,
                'thumbnail'   => 'images/product8.png',
                'gallery'     => ['images/product1.png'],
                'is_active'   => true,
            ],
            [
                'product_category_id' => $gifts->id,
                'name'        => 'The LuxeStay Wellness Gift Box',
                'slug'        => 'luxestay-wellness-gift-box',
                'description' => 'A curated collection of our five bestselling products presented in a handcrafted bamboo box with tissue paper, a hand-written card, and a personalised ribbon. The perfect gift for any occasion.',
                'price'       => 220.00,
                'stock'       => 25,
                'thumbnail'   => 'images/product9.png',
                'gallery'     => ['images/product_1.png', 'images/product_2.png'],
                'is_active'   => true,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
