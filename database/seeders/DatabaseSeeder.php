<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DJ;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User (only if doesn't exist)
        $admin = User::firstOrCreate(
            ['email' => 'admin@diskjockey.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Categories (only if doesn't exist)
        $categories = [
            ['name' => 'Wedding', 'slug' => 'wedding', 'description' => 'Wedding events'],
            ['name' => 'Corporate', 'slug' => 'corporate', 'description' => 'Corporate events'],
            ['name' => 'Nightlife', 'slug' => 'nightlife', 'description' => 'Nightlife and clubs'],
            ['name' => 'Private Events', 'slug' => 'private-events', 'description' => 'Private parties'],
            ['name' => 'Conferences', 'slug' => 'conferences', 'description' => 'Conferences and seminars'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Create Sample Users and DJs
        $djs = [
            [
                'user' => [
                    'name' => 'DJ Nova',
                    'email' => 'djnova@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'dj',
                ],
                'dj' => [
                    'stage_name' => 'DJ NOVA',
                    'bio' => 'Professional DJ with 5+ years of experience in weddings and corporate events.',
                    'city' => 'Miami',
                    'state' => 'FL',
                    'zipcode' => '33101',
                    'hourly_rate' => 500,
                    'experience_years' => 5,
                    'specialties' => ['Wedding', 'Corporate', 'Private Events'],
                    'genres' => ['Hip Hop', 'EDM', 'Pop'],
                    'is_verified' => true,
                    'is_available' => true,
                    'rating' => 4.8,
                    'total_reviews' => 25,
                ],
            ],
            [
                'user' => [
                    'name' => 'Alex Vibe',
                    'email' => 'alexvibe@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'dj',
                ],
                'dj' => [
                    'stage_name' => 'DJ ALEX VIBE',
                    'bio' => 'Experienced DJ specializing in nightlife and corporate events.',
                    'city' => 'Los Angeles',
                    'state' => 'CA',
                    'zipcode' => '90001',
                    'hourly_rate' => 750,
                    'experience_years' => 8,
                    'specialties' => ['Wedding', 'Nightlife', 'Corporate Events'],
                    'genres' => ['EDM', 'Hip Hop', 'Rock'],
                    'is_verified' => true,
                    'is_available' => true,
                    'rating' => 4.9,
                    'total_reviews' => 42,
                ],
            ],
            [
                'user' => [
                    'name' => 'Michael Carter',
                    'email' => 'mcarter@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'dj',
                ],
                'dj' => [
                    'stage_name' => 'MICHAEL CARTER',
                    'bio' => 'Professional Event MC with expertise in corporate events and conferences.',
                    'city' => 'Chicago',
                    'state' => 'IL',
                    'zipcode' => '60601',
                    'hourly_rate' => 600,
                    'experience_years' => 10,
                    'specialties' => ['Corporate Events', 'Conferences', 'Live Shows'],
                    'genres' => ['Pop', 'R&B', 'Jazz'],
                    'is_verified' => true,
                    'is_available' => true,
                    'rating' => 5.0,
                    'total_reviews' => 38,
                ],
            ],
        ];

        foreach ($djs as $djData) {
            // Create or update user
            $user = User::firstOrCreate(
                ['email' => $djData['user']['email']],
                $djData['user']
            );

            // Create or update DJ profile
            $dj = DJ::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($djData['dj'], ['user_id' => $user->id])
            );
            
            // Attach categories (sync to avoid duplicates)
            $categoryIds = Category::whereIn('name', $djData['dj']['specialties'])->pluck('id');
            $dj->categories()->sync($categoryIds);
        }

        // Create Regular User (only if doesn't exist)
        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Create Sample Events
        $events = [
            [
                'user_id' => $regularUser->id,
                'title' => 'Summer Wedding Celebration',
                'description' => 'A beautiful outdoor wedding ceremony and reception for 150 guests. Looking for a professional DJ who can play a mix of contemporary hits and classic love songs.',
                'event_type' => 'Wedding',
                'event_date' => now()->addMonths(2)->format('Y-m-d'),
                'start_time' => '16:00:00',
                'end_time' => '23:00:00',
                'venue_name' => 'Grand Garden Venue',
                'address' => '1234 Park Avenue',
                'city' => 'Miami',
                'state' => 'FL',
                'zipcode' => '33101',
                'guest_count' => 150,
                'requirements' => ['Sound System', 'Microphone', 'Lighting', 'MC Services'],
                'budget_min' => 2000.00,
                'budget_max' => 3500.00,
                'status' => 'pending',
            ],
            [
                'user_id' => $regularUser->id,
                'title' => 'Corporate Annual Gala',
                'description' => 'Annual company gala event with 200+ employees. Need professional DJ for dinner and dance portions of the evening.',
                'event_type' => 'Corporate',
                'event_date' => now()->addMonths(3)->format('Y-m-d'),
                'start_time' => '18:00:00',
                'end_time' => '01:00:00',
                'venue_name' => 'Downtown Convention Center',
                'address' => '5678 Business Blvd',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'zipcode' => '90001',
                'guest_count' => 200,
                'requirements' => ['Professional Sound', 'Stage Lighting', 'Wireless Microphones', 'Background Music'],
                'budget_min' => 3000.00,
                'budget_max' => 5000.00,
                'status' => 'confirmed',
            ],
            [
                'user_id' => $regularUser->id,
                'title' => '30th Birthday Bash',
                'description' => 'Milestone birthday celebration with friends and family. Want a fun, energetic DJ who can keep the party going all night!',
                'event_type' => 'Birthday',
                'event_date' => now()->addWeeks(3)->format('Y-m-d'),
                'start_time' => '19:00:00',
                'end_time' => '02:00:00',
                'venue_name' => 'The Party Loft',
                'address' => '890 Nightlife Street',
                'city' => 'Chicago',
                'state' => 'IL',
                'zipcode' => '60601',
                'guest_count' => 75,
                'requirements' => ['DJ Booth', 'Lighting Effects', 'Smoke Machine'],
                'budget_min' => 1000.00,
                'budget_max' => 2000.00,
                'status' => 'pending',
            ],
            [
                'user_id' => $regularUser->id,
                'title' => 'Tech Conference After Party',
                'description' => 'Networking event after a tech conference. Need a DJ who can create a modern, upbeat atmosphere for tech professionals.',
                'event_type' => 'Corporate',
                'event_date' => now()->addMonths(1)->format('Y-m-d'),
                'start_time' => '20:00:00',
                'end_time' => '01:00:00',
                'venue_name' => 'Innovation Hub',
                'address' => '321 Tech Drive',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zipcode' => '94102',
                'guest_count' => 120,
                'requirements' => ['Modern Music Selection', 'Ambient Lighting', 'Professional Setup'],
                'budget_min' => 2500.00,
                'budget_max' => 4000.00,
                'status' => 'confirmed',
            ],
            [
                'user_id' => $regularUser->id,
                'title' => 'Graduation Party',
                'description' => 'High school graduation celebration with family and friends. Looking for a DJ who can play current hits and handle announcements.',
                'event_type' => 'Private Event',
                'event_date' => now()->addWeeks(6)->format('Y-m-d'),
                'start_time' => '17:00:00',
                'end_time' => '22:00:00',
                'venue_name' => 'Community Hall',
                'address' => '456 Main Street',
                'city' => 'Miami',
                'state' => 'FL',
                'zipcode' => '33102',
                'guest_count' => 100,
                'requirements' => ['Family-Friendly Music', 'Microphone', 'Simple Setup'],
                'budget_min' => 800.00,
                'budget_max' => 1500.00,
                'status' => 'pending',
            ],
        ];

        foreach ($events as $eventData) {
            \App\Models\Event::firstOrCreate(
                [
                    'user_id' => $eventData['user_id'],
                    'title' => $eventData['title'],
                    'event_date' => $eventData['event_date'],
                ],
                $eventData
            );
        }
    }
}
