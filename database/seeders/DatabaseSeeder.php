<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;
use App\Models\Tag;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Listing::factory(10)->create();
        
        $tags = Tag::factory(10)->create();

        User::factory(20)->create()->each(function($user) use($tags){
            Listing::factory(rand(1, 4))->create([
                'user_id' => $user->id
            ])->each(function($listing) use($tags){
                $listing->tags()->attach($tags->random(2));
            });
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
