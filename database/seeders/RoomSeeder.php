<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $deluxe      = RoomType::where('slug', 'deluxe-room')->first();
        $suite       = RoomType::where('slug', 'suite')->first();
        $villa       = RoomType::where('slug', 'villa')->first();
        $penthouse   = RoomType::where('slug', 'penthouse')->first();
        $presidential = RoomType::where('slug', 'presidential-suite')->first();

        $rooms = [
            [
                'room_type_id'   => $deluxe->id,
                'name'           => 'Ocean Deluxe King',
                'slug'           => 'ocean-deluxe-king',
                'description'    => 'Wake up to breathtaking ocean panoramas from this elegantly appointed king room featuring handcrafted furnishings, a marble en-suite, and a private balcony overlooking the resort gardens.',
                'price_per_night'=> 320.00,
                'max_guests'     => 2,
                'size_sqm'       => 45,
                'thumbnail'      => 'images/room-image-1.png',
                'gallery'        => ['images/room-slider-image1.png', 'images/room-slider-image2.png', 'images/room-slider-image3.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Ocean View', 'Balcony', 'Safe Box'],
            ],
            [
                'room_type_id'   => $deluxe->id,
                'name'           => 'Garden Deluxe Twin',
                'slug'           => 'garden-deluxe-twin',
                'description'    => 'Set among lush tropical gardens, this spacious twin room offers serene natural views, premium linens, and all the modern amenities you expect from a five-star retreat.',
                'price_per_night'=> 280.00,
                'max_guests'     => 2,
                'size_sqm'       => 42,
                'thumbnail'      => 'images/room-image-2.png',
                'gallery'        => ['images/room-slider-image4.png', 'images/room-slider-image5.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Coffee Maker', 'Safe Box', 'Room Service'],
            ],
            [
                'room_type_id'   => $suite->id,
                'name'           => 'Horizon Junior Suite',
                'slug'           => 'horizon-junior-suite',
                'description'    => 'Indulge in this beautifully designed junior suite featuring a spacious sitting area, rain shower, soaking tub, and sweeping views of the coastline from a wraparound balcony.',
                'price_per_night'=> 520.00,
                'max_guests'     => 3,
                'size_sqm'       => 70,
                'thumbnail'      => 'images/room-image-3.png',
                'gallery'        => ['images/room-slider-image6.png', 'images/room-slider-image7.png', 'images/room-slider-image8.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Ocean View', 'Spa Bathtub', 'Balcony', 'Room Service', 'King-size Bed'],
            ],
            [
                'room_type_id'   => $suite->id,
                'name'           => 'Skyline Executive Suite',
                'slug'           => 'skyline-executive-suite',
                'description'    => 'A masterpiece of contemporary luxury. The executive suite spans 95 sqm with a dedicated living room, walk-in wardrobe, and exclusive access to the Skyline Lounge.',
                'price_per_night'=> 750.00,
                'max_guests'     => 2,
                'size_sqm'       => 95,
                'thumbnail'      => 'images/room-image-4.png',
                'gallery'        => ['images/room-slider-image9.png', 'images/room-slider-image10.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Ocean View', 'Spa Bathtub', 'Room Service', 'Safe Box', 'King-size Bed', 'Coffee Maker'],
            ],
            [
                'room_type_id'   => $villa->id,
                'name'           => 'Tropical Beach Villa',
                'slug'           => 'tropical-beach-villa',
                'description'    => 'Your own private paradise. This standalone beach villa features a 12-meter infinity pool, outdoor shower, personal chef service, and direct beach access via a lantern-lit path.',
                'price_per_night'=> 1200.00,
                'max_guests'     => 4,
                'size_sqm'       => 180,
                'thumbnail'      => 'images/room-image-5.png',
                'gallery'        => ['images/room-slider-image11.png', 'images/room-slider-image12.png', 'images/room-slider-image13.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Private Pool', 'Ocean View', 'Spa Bathtub', 'Balcony', 'Room Service', 'Safe Box', 'King-size Bed', 'Coffee Maker'],
            ],
            [
                'room_type_id'   => $villa->id,
                'name'           => 'Hillside Garden Villa',
                'slug'           => 'hillside-garden-villa',
                'description'    => 'Perched on a lush hillside, this two-bedroom villa offers jaw-dropping valley views, a private plunge pool, outdoor dining terrace, and a dedicated wellness butler.',
                'price_per_night'=> 980.00,
                'max_guests'     => 4,
                'size_sqm'       => 160,
                'thumbnail'      => 'images/room-image-6.png',
                'gallery'        => ['images/room-image-7.png', 'images/room-image-8.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Private Pool', 'Spa Bathtub', 'Balcony', 'Room Service', 'King-size Bed'],
            ],
            [
                'room_type_id'   => $penthouse->id,
                'name'           => 'Azure Penthouse',
                'slug'           => 'azure-penthouse',
                'description'    => 'Crowning the tower, the Azure Penthouse commands 360° views of sea and city. Features include a rooftop terrace, private infinity pool, grand piano, and bespoke butler service.',
                'price_per_night'=> 2200.00,
                'max_guests'     => 6,
                'size_sqm'       => 350,
                'thumbnail'      => 'images/room-image-9.png',
                'gallery'        => ['images/room-image-10.png', 'images/room-image-11.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Private Pool', 'Ocean View', 'Spa Bathtub', 'Balcony', 'Room Service', 'Safe Box', 'King-size Bed', 'Coffee Maker'],
            ],
            [
                'room_type_id'   => $presidential->id,
                'name'           => 'LuxeStay Presidential Suite',
                'slug'           => 'luxestay-presidential-suite',
                'description'    => 'The ultimate LuxeStay experience. Four bedrooms, a grand dining room, private cinema, heated pool terrace, and a personal team of six dedicated to your every need.',
                'price_per_night'=> 4500.00,
                'max_guests'     => 8,
                'size_sqm'       => 600,
                'thumbnail'      => 'images/room-image-12.png',
                'gallery'        => ['images/room-image-13.png', 'images/room-slider-image1.png'],
                'is_available'   => true,
                'amenity_keys'   => ['Free Wi-Fi', 'Air Conditioning', 'Flat-screen TV', 'Mini Bar', 'Private Pool', 'Ocean View', 'Spa Bathtub', 'Balcony', 'Room Service', 'Safe Box', 'King-size Bed', 'Coffee Maker'],
            ],
        ];

        foreach ($rooms as $data) {
            $amenityKeys = $data['amenity_keys'];
            unset($data['amenity_keys']);

            $room = Room::firstOrCreate(['slug' => $data['slug']], $data);

            $amenityIds = Amenity::whereIn('name', $amenityKeys)->pluck('id');
            $room->amenities()->sync($amenityIds);
        }
    }
}
