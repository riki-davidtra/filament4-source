<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fetch existing categories
        $categories = Category::all();

        if ($categories->count() === 0) {
            $this->command->warn('No categories found. Please seed categories first.');
            return;
        }

        $data = [
            [
                'title'   => 'How to Build Your First Laravel Application',
                'content' => 'A complete guide on how to start your first Laravel project from scratch...',
            ],
            [
                'title'   => 'Tips for Optimizing Your Filament Admin Panel',
                'content' => 'Important tips to keep your Filament admin panel fast and lightweight...',
            ],
            [
                'title'   => 'Understanding Eloquent Relationships Easily',
                'content' => 'A simple explanation of belongsTo, hasMany, and belongsToMany...',
            ],
            [
                'title'   => 'Advantages of Using UUIDs in Laravel',
                'content' => 'Why UUIDs are more secure and scalable than auto-increment IDs...',
            ],
            [
                'title'   => 'Guide to Building APIs in Laravel 11',
                'content' => 'How to create API Resources, Routes, and standardized JSON Responses...',
            ],
        ];

        foreach ($data as $item) {
            $post = Post::updateOrCreate(
                ['title' => $item['title']],
                [
                    'title'     => $item['title'],
                    'content'   => $item['content'],
                    'image'     => null,
                    'thumbnail' => null,
                    'status'    => 'published',
                    'user_id'   => 1,
                ]
            );

            // Select 1–3 random categories
            $randomCategories = $categories->random(rand(1, 3))->pluck('id')->toArray();

            // Attach categories through pivot table
            $post->categories()->syncWithoutDetaching($randomCategories);
        }
    }
}
