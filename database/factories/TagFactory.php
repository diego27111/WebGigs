<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */


    //protected $model = Tag::class;


    public function definition(): array
    {
        $name = ucwords($this->faker->unique->word);

        return [
            'name' => $name,
            'slug' => Str::slug($name)
        ];
    }
}
