<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Author;
use App\Models\Book;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $author = Author::inRandomOrder()->first();

        if(!$author){
            $author = Author::factory()->create();
        }

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'author_id' => $author->id
        ];
    }
}
