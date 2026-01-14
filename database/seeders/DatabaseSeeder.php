<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DJ;
use App\Models\Category;
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
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
