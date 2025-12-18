<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'Strength Training', 'slug' => 'strength-training'],
            ['name' => 'Cardio', 'slug' => 'cardio'],
            ['name' => 'Yoga', 'slug' => 'yoga'],
            ['name' => 'HIIT', 'slug' => 'hiit'],
            ['name' => 'Flexibility', 'slug' => 'flexibility'],
            ['name' => 'Nutrition', 'slug' => 'nutrition'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Sample Videos
        $strengthCategory = Category::where('slug', 'strength-training')->first();
        $cardioCategory = Category::where('slug', 'cardio')->first();
        $yogaCategory = Category::where('slug', 'yoga')->first();
        $hiitCategory = Category::where('slug', 'hiit')->first();
        $flexibilityCategory = Category::where('slug', 'flexibility')->first();
        $nutritionCategory = Category::where('slug', 'nutrition')->first();

        $videos = [
            // Strength Training Videos
            [
                'title' => 'Full Body Strength Workout',
                'description' => 'Complete full body strength training session for all fitness levels. Perfect for beginners and intermediate athletes looking to build overall strength.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $strengthCategory->id,
            ],
            [
                'title' => 'Advanced Deadlift Techniques',
                'description' => 'Master the deadlift with proper form and advanced techniques. Learn how to safely increase your deadlift weight and avoid common injuries.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1517836357043-4b1d0c0f0b8a?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $strengthCategory->id,
            ],
            [
                'title' => 'Core Strength Builder',
                'description' => 'Targeted core workout to build a strong and stable midsection. Includes exercises for abs, obliques, and lower back.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $strengthCategory->id,
            ],
            [
                'title' => 'Upper Body Power Training',
                'description' => 'Build explosive upper body strength with this power-focused workout. Perfect for athletes looking to improve performance.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1576678927484-cc907957088c?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $strengthCategory->id,
            ],
            [
                'title' => 'Leg Day Essentials',
                'description' => 'Complete leg workout focusing on quads, hamstrings, glutes, and calves. Build strong, powerful legs with proper form.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $strengthCategory->id,
            ],
            
            // Cardio Videos
            [
                'title' => '30-Minute Cardio Blast',
                'description' => 'High-intensity cardio workout to burn calories and improve endurance. Perfect for weight loss and cardiovascular health.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $cardioCategory->id,
            ],
            [
                'title' => 'Running Form Masterclass',
                'description' => 'Learn proper running technique to improve efficiency and prevent injuries. Essential for runners of all levels.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1571008887538-b36bb32f4571?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $cardioCategory->id,
            ],
            [
                'title' => 'Indoor Cycling Workout',
                'description' => 'High-energy indoor cycling session that will get your heart pumping. No bike needed - use bodyweight exercises.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $cardioCategory->id,
            ],
            
            // Yoga Videos
            [
                'title' => 'Morning Yoga Flow',
                'description' => 'Gentle yoga flow to start your day with energy and focus. Perfect for beginners and those looking for a calming practice.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreet.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $yogaCategory->id,
            ],
            [
                'title' => 'Power Yoga for Strength',
                'description' => 'Dynamic yoga practice that builds strength and flexibility. Challenge yourself with advanced poses and flows.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $yogaCategory->id,
            ],
            [
                'title' => 'Evening Relaxation Yoga',
                'description' => 'Wind down after a long day with this restorative yoga session. Focus on relaxation and stress relief.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $yogaCategory->id,
            ],
            
            // HIIT Videos
            [
                'title' => 'HIIT Power Session',
                'description' => 'Intense HIIT workout for maximum results in minimal time. Burn calories and build endurance with this 20-minute session.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WhatCarCanYouGetForAGrand.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $hiitCategory->id,
            ],
            [
                'title' => '15-Minute HIIT Blast',
                'description' => 'Quick and effective HIIT workout perfect for busy schedules. Maximum results in just 15 minutes.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $hiitCategory->id,
            ],
            [
                'title' => 'Advanced HIIT Challenge',
                'description' => 'Take your fitness to the next level with this advanced HIIT challenge. For experienced athletes only.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $hiitCategory->id,
            ],
            
            // Flexibility Videos
            [
                'title' => 'Full Body Stretching Routine',
                'description' => 'Comprehensive stretching routine to improve flexibility and reduce muscle tension. Perfect for post-workout recovery.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=600&fit=crop',
                'is_premium' => false,
                'category_id' => $flexibilityCategory->id,
            ],
            [
                'title' => 'Advanced Flexibility Training',
                'description' => 'Take your flexibility to the next level with advanced stretching techniques and mobility work.',
                'video_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                'thumbnail' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=600&fit=crop',
                'is_premium' => true,
                'category_id' => $flexibilityCategory->id,
            ],
        ];

        foreach ($videos as $video) {
            Video::create($video);
        }
    }
}
