<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        Activity::truncate();

        $activities = [
            [
                'type'        => 'event',
                'title'       => 'Event & Wedding',
                'slug'        => 'event-wedding',
                'thumbnail'   => 'images/event-slide1.png',
                'hero_image'  => 'images/event-wedding-hero_img.png',
                'is_featured' => true,
                'sort_order'  => 1,
                'content'     => '<p>Whether you envision an intimate ceremony for twenty or a grand celebration for three hundred, LuxeStay\'s event spaces and dedicated planning team will bring your vision to life with impeccable precision.</p><p>Our tropical garden — shaded by century-old frangipani trees — provides a naturally beautiful setting for outdoor ceremonies. The Crystal Ballroom, with its 14-meter ceilings, hand-painted murals, and state-of-the-art AV, transforms for receptions of any scale.</p><p>Our team of specialist wedding and events planners have coordinated over 400 events across 18 years. From floral arrangements sourced from our own gardens to a dedicated pastry team for custom cake design, every detail is handled in-house to the highest standard.</p>',
            ],
            [
                'type'        => 'fitness',
                'title'       => 'Fitness and Wellness',
                'slug'        => 'fitness-and-wellness',
                'thumbnail'   => 'images/fitness-joreny_to_img.png',
                'hero_image'  => 'images/fitness-wellness_hero_img.png',
                'is_featured' => false,
                'sort_order'  => 2,
                'content'     => '<p>Our state-of-the-art 600 sqm fitness centre is open 24 hours to all guests. Featuring Technogym equipment, functional training zones, and floor-to-ceiling ocean views, it\'s a space where working out feels like a reward rather than a task.</p><p>The centre includes: cardio suite (treadmills, bikes, rowers, ellipticals), strength zone (free weights up to 60kg, cable machines, Smith machine), functional training area, and a dedicated stretch and foam-rolling zone. Personal training sessions with certified trainers are available from 6:00am to 9:00pm by appointment.</p>',
            ],
            [
                'type'        => 'golf',
                'title'       => 'Golf Courses',
                'slug'        => 'golf-courses',
                'thumbnail'   => 'images/golf_image-1.png',
                'hero_image'  => 'images/golf-courses_hero_img.png',
                'is_featured' => true,
                'sort_order'  => 3,
                'content'     => '<p>Designed by three-time major champion architect Sir David Harrington, our 18-hole championship course is consistently ranked among the top ten resort courses in Southeast Asia. Playing at 6,800 yards from the back tees, it rewards strategic thinking as much as raw power.</p><p>Signature holes include the par-3 15th, where players drive across a natural inlet with the ocean as backdrop, and the infamous 18th, a dogleg par-5 that has decided many a friendly wager. Morning tee times from 6:00am allow play before the day\'s heat builds; afternoon twilight rounds carry a reduced green fee.</p><p>The Golf Academy offers private tuition from PGA-certified instructors for all levels. Club rental, caddies, and golf carts are available at the pro shop.</p>',
            ],
            [
                'type'        => 'hiking',
                'title'       => 'Hiking and Trekking',
                'slug'        => 'hiking-and-trekking',
                'thumbnail'   => 'images/hiking-book-bg.png',
                'hero_image'  => 'images/hiking-trekking-hero_img.png',
                'is_featured' => false,
                'sort_order'  => 4,
                'content'     => '<p>The peninsula surrounding LuxeStay is a landscape of extraordinary natural beauty — dramatic limestone cliffs, old-growth forest, and hidden coves accessible only on foot. Our outdoor guides have spent years mapping and maintaining trails that reveal this landscape at its most spectacular.</p><p>The flagship Cliff Path Walk (5km, moderate) departs at 6:30am and leads to a hidden sunrise viewpoint known only to locals. The Forest Immersion Trail (8km, challenging) passes through primary rainforest with guided naturalist commentary on native flora and fauna. All guided walks include trail snacks, hydration, and photography assistance.</p>',
            ],
            [
                'type'        => 'leisure',
                'title'       => 'Leisure and Entertainment',
                'slug'        => 'leisure-and-entertainment',
                'thumbnail'   => 'images/leisure_img.png',
                'hero_image'  => 'images/leisure-and-entertainment-hero_img.png',
                'is_featured' => false,
                'sort_order'  => 5,
                'content'     => '<p>From the moment you arrive, LuxeStay surrounds you with an array of leisure pursuits designed to suit every mood. Whether you\'re looking to unwind poolside with a cocktail, challenge friends to a friendly game of tennis, or explore our curated selection of cultural excursions, there\'s always something wonderful to discover.</p><p>Our entertainment calendar features live music, cultural performances, themed dining nights, and seasonal events. The Kids\' Club runs daily from 9am to 6pm, allowing parents to enjoy activities at their own pace while children enjoy age-appropriate adventures under professional supervision.</p>',
            ],
            [
                'type'        => 'nature',
                'title'       => 'Nature and Exploration',
                'slug'        => 'nature-and-exploration',
                'thumbnail'   => 'images/nature-exploration-book_now_bg.png',
                'hero_image'  => 'images/nature-exploration-hero_img.png',
                'is_featured' => false,
                'sort_order'  => 6,
                'content'     => '<p>LuxeStay is situated within a protected coastal reserve, giving guests rare access to ecosystems of remarkable biodiversity. Our naturalist team leads small-group eco-tours that combine environmental education with genuine adventure.</p><p>Signature experiences include: mangrove kayaking at dawn, bioluminescent bay night tour, sea turtle nesting beach walk (seasonal, May–August), bird-watching tour with an ornithologist, and a full-day island-hopping excursion by private speedboat. All tours adhere to strict environmental protocols.</p>',
            ],
            [
                'type'        => 'skiing',
                'title'       => 'Ski & Snowboarding',
                'slug'        => 'ski-snowboarding',
                'thumbnail'   => 'images/skiing-img1.png',
                'hero_image'  => 'images/ski-snowboarding_hero_img.png',
                'is_featured' => false,
                'sort_order'  => 7,
                'content'     => '<p>LuxeStay\'s alpine property sits at 2,200 metres elevation with direct access to 45 groomed runs across three mountain faces. Whether you\'re clicking into bindings for the first time or carving confident lines down black diamond terrain, our mountain concierge will craft the perfect day on snow.</p><p>The ski school employs 28 PSIA-certified instructors teaching group and private lessons for all ages from 3 years. Equipment rental includes premium Atomic and Salomon gear fitted by qualified boot fitters. The ski valet service ensures your equipment is ready at the slope each morning.</p>',
            ],
            [
                'type'        => 'spa',
                'title'       => 'Spa & Wellness',
                'slug'        => 'spa-wellness',
                'thumbnail'   => 'images/spa-wellness-img1.png',
                'hero_image'  => 'images/spa-wellness-hero_img.png',
                'is_featured' => true,
                'sort_order'  => 8,
                'content'     => '<p>Surrender to a world of holistic healing at the LuxeStay Spa — a 2,000 sqm sanctuary dedicated to restoring balance between body, mind, and spirit. Our menu of over forty treatments draws from ancient Asian healing traditions and the latest evidence-based techniques.</p><p>Begin your journey in the hydrotherapy pool, where jets and temperature variations prime your circulation. Progress through our steam room and sauna circuit before settling into the treatment rooms for your chosen therapy — from our signature 120-minute LuxeStay Journey to a targeted 60-minute deep tissue massage.</p>',
            ],
            [
                'type'        => 'unique',
                'title'       => 'Unique Experiences',
                'slug'        => 'unique-experiences',
                'thumbnail'   => 'images/unique_story_img.png',
                'hero_image'  => 'images/unique-experiences-hero_img.png',
                'is_featured' => false,
                'sort_order'  => 9,
                'content'     => '<p>Beyond the expected, LuxeStay offers a collection of extraordinary experiences designed to create memories that last long after you\'ve returned home. From private helicopter sunrise tours over the archipelago to underwater photography masterclasses with resident marine biologists, these are the moments that define a truly exceptional stay.</p><p>Other curated experiences include: private chef\'s table dinner in the spice garden, star-gazing evenings with a resident astronomer, traditional craft workshops with local artisans, and dawn hot-air balloon flights over the valley. Advance booking is strongly recommended — many experiences have limited availability.</p>',
            ],
            [
                'type'        => 'water_sports',
                'title'       => 'Water Sports',
                'slug'        => 'water-sports',
                'thumbnail'   => 'images/water-sport-img1.png',
                'hero_image'  => 'images/watersport_hero_img.png',
                'is_featured' => true,
                'sort_order'  => 10,
                'content'     => '<p>The warm, clear waters surrounding our private beach are a playground for adventure. LuxeStay Water Sports offers a comprehensive program of activities for every level of experience — from first-time snorkelers to certified divers seeking challenging dive sites.</p><p>Offerings include: stand-up paddleboarding, kayaking, windsurfing, kitesurfing lessons, jet skiing, parasailing, snorkeling tours to the coral garden, scuba diving (PADI certified instruction available), and private sunset sailing charters aboard our 52-foot catamaran.</p>',
            ],
            [
                'type'        => 'hiking',
                'title'       => 'Winter Hiking',
                'slug'        => 'winter-hiking',
                'thumbnail'   => 'images/winter-1.png',
                'hero_image'  => 'images/winter-hiking_hero_img.png',
                'is_featured' => false,
                'sort_order'  => 11,
                'content'     => '<p>When snow blankets the trails, our mountain landscape transforms into a wonderland of quiet beauty. Winter hiking at LuxeStay offers a profoundly different experience from summer trekking — the hushed forests, crisp air, and dramatic snow-covered peaks create an atmosphere of extraordinary serenity.</p><p>Our certified winter guides lead small groups (maximum 8) on half-day and full-day routes suited to the season\'s conditions. Snowshoe hire, thermal layers, and hot chocolate at the summit are all included. The après-hike experience — warm soup, a log fire, and complimentary mulled wine in the mountain lodge — is not to be missed.</p>',
            ],
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}
