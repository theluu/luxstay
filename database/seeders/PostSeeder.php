<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin  = User::where('email', 'admin@luxestay.com')->first();
        $travel = PostCategory::where('slug', 'travel-tips')->first();
        $dining = PostCategory::where('slug', 'dining')->first();
        $wellness = PostCategory::where('slug', 'wellness-spa')->first();
        $events = PostCategory::where('slug', 'events')->first();
        $destination = PostCategory::where('slug', 'destination')->first();

        $posts = [
            [
                'post_category_id' => $travel->id,
                'author_id'        => $admin->id,
                'title'            => '10 Essential Tips for Luxury Travel in 2026',
                'slug'             => '10-essential-tips-for-luxury-travel-2026',
                'excerpt'          => 'Planning the ultimate getaway? Our travel experts share the insider secrets that transform a good trip into an unforgettable experience.',
                'content'          => '<p>Luxury travel has evolved far beyond thread counts and champagne on arrival. Today\'s most discerning travelers seek authentic, curated experiences that connect them deeply to a destination. Here are the ten principles our concierge team swear by.</p><h3>1. Book the Shoulder Season</h3><p>Peak season means crowds. Visiting in May or October grants you the same sublime weather with far fewer guests and often significantly better rates — leaving more budget for experiences.</p><h3>2. Communicate Your Preferences in Advance</h3><p>A great property cannot surprise and delight you if it doesn\'t know you. Send a brief note before arrival: dietary needs, preferred pillow firmness, anniversary details. The best hotels maintain guest preference profiles.</p><h3>3. Invest in a Suite for at Least One Night</h3><p>Even if you stay in a standard room for the bulk of your trip, splurging on a suite for the first or last night reframes your entire experience of the property.</p>',
                'thumbnail'        => 'images/blog-list_1.png',
                'type'             => 'standard',
                'status'           => 'published',
                'published_at'     => now()->subDays(5),
            ],
            [
                'post_category_id' => $dining->id,
                'author_id'        => $admin->id,
                'title'            => 'A Chef\'s Tour of Our Signature Restaurant',
                'slug'             => 'chefs-tour-signature-restaurant',
                'excerpt'          => 'Executive Chef Marco Bellini takes us behind the kitchen pass to reveal the philosophy and produce powering LuxeStay\'s celebrated tasting menu.',
                'content'          => '<p>Every dish on our seasonal tasting menu begins not in the kitchen, but in conversation with local farmers. Chef Marco visits the market at dawn three times a week, allowing the morning\'s finest produce to dictate the day\'s menu — a practice he calls "cooking backwards."</p><p>The signature Charred Lobster with Champagne Velouté uses lobsters sourced from a single family-run operation forty miles up the coast. "I\'ve eaten their lobsters for twelve years," Marco says. "I trust them the way I trust my brigade."</p><p>Reservations for the eight-course tasting menu are available Thursday through Sunday. We recommend booking four weeks in advance.</p>',
                'thumbnail'        => 'images/blog-list_2.png',
                'type'             => 'standard',
                'status'           => 'published',
                'published_at'     => now()->subDays(10),
            ],
            [
                'post_category_id' => $wellness->id,
                'author_id'        => $admin->id,
                'title'            => 'The Art of Doing Nothing: A Guide to Restorative Rest',
                'slug'             => 'art-of-doing-nothing-restorative-rest',
                'excerpt'          => 'In a world obsessed with productivity, our wellness director makes the case for the radical act of genuine rest — and shares our most effective techniques.',
                'content'          => '<p>Niksen — the Dutch concept of purposeful idleness — is not laziness. It is, our wellness team believe, the deepest form of self-care available to the modern traveler. Here is how to practice it properly.</p><p>Begin your morning without a screen. Step onto your balcony, feel the air, listen. Resist the urge to optimize your day. Our spa team can curate a "non-itinerary" — a loose sequence of possibilities rather than appointments — for guests who want structure without rigidity.</p>',
                'thumbnail'        => 'images/blog-list_3.png',
                'type'             => 'standard',
                'status'           => 'published',
                'published_at'     => now()->subDays(14),
            ],
            [
                'post_category_id' => $events->id,
                'author_id'        => $admin->id,
                'title'            => 'Garden Wedding at LuxeStay: A Complete Guide',
                'slug'             => 'garden-wedding-luxestay-complete-guide',
                'excerpt'          => 'From intimate gatherings of twenty to grand celebrations of three hundred, our events team has perfected every detail of the LuxeStay wedding experience.',
                'content'          => '<p>LuxeStay has hosted over four hundred weddings across eighteen years. In that time, our events team has learned one universal truth: the details guests remember most are rarely the ones you obsessed over longest.</p><p>Our tropical garden ceremony space accommodates up to 300 guests beneath a canopy of century-old frangipani trees. The adjacent Crystal Ballroom — with its fourteen-meter ceilings and hand-painted murals — transforms seamlessly for receptions of any scale.</p>',
                'thumbnail'        => 'images/blog-list_4.png',
                'type'             => 'standard',
                'status'           => 'published',
                'published_at'     => now()->subDays(20),
            ],
            [
                'post_category_id' => $destination->id,
                'author_id'        => $admin->id,
                'title'            => 'Hidden Trails: Exploring the Coastline on Foot',
                'slug'             => 'hidden-trails-exploring-coastline',
                'excerpt'          => 'Beyond the resort gates lies a world of dramatic coastal scenery accessible only on foot. Our outdoor guide reveals the trails worth your energy.',
                'content'          => '<p>The five-kilometer cliff path that begins at our northern gate may be the best-kept secret on the peninsula. Carved by the resort\'s founding family in 1962 as a morning walk, it remains off most maps and leads to a hidden cove accessible only at low tide.</p><p>Our outdoor team leads guided walks at 6:30am Tuesday and Saturday, returning in time for breakfast. Boot rentals, trail snacks, and photography assistance are included.</p>',
                'thumbnail'        => 'images/blog-list_5.png',
                'type'             => 'standard',
                'status'           => 'published',
                'published_at'     => now()->subDays(25),
            ],
            [
                'post_category_id' => $wellness->id,
                'author_id'        => $admin->id,
                'title'            => 'Video: Inside Our Award-Winning Spa Sanctuary',
                'slug'             => 'video-inside-award-winning-spa',
                'excerpt'          => 'Take a guided visual journey through the seven treatment rooms, hydrotherapy pool, and rooftop meditation garden of our internationally recognised spa.',
                'content'          => '<p>Our spa received the Forbes Five-Star Award for the third consecutive year in 2025. In this exclusive film, spa director Amara walks us through the space and philosophy that earned this distinction.</p>',
                'thumbnail'        => 'images/blog-grid2.png',
                'type'             => 'video',
                'status'           => 'published',
                'published_at'     => now()->subDays(30),
            ],
            [
                'post_category_id' => $travel->id,
                'author_id'        => $admin->id,
                'title'            => 'Draft: Sustainable Luxury — Our 2026 Commitments',
                'slug'             => 'sustainable-luxury-2026-commitments',
                'excerpt'          => 'How LuxeStay is redefining what it means to travel responsibly without compromising on excellence.',
                'content'          => '<p>Draft content pending final review by sustainability team...</p>',
                'thumbnail'        => 'images/blog-grid3.png',
                'type'             => 'standard',
                'status'           => 'draft',
                'published_at'     => null,
            ],
        ];

        foreach ($posts as $post) {
            Post::firstOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
