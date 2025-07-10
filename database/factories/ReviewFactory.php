<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Author;
use App\Models\Book;
use App\Models\Review;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        
    $book = Book::inRandomOrder()->first();

    if (!$book) {
        $book = Book::factory()->create();
    }

    return [
        'User_review' => $this->faker->text(),
        'book_id' => $book->id,
        'author_id' => $book->author_id,
    ];
    }
}
