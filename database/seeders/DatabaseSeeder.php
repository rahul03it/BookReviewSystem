<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Review;
use Database\Factories\AuthorFactory;
use Database\Factories\BookFactory;
use Database\Factories\ReviewFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            Author::factory()->count(5)->has(Book::factory()->count(3)->has(Review::factory()->count(2)))->create();
    }

}
