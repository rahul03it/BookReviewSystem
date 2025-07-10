<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ReviewController
 */
final class ReviewControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        // $book = Book::factory()->create();
        $reviews = Review::factory()->create();

        $response1 = $this->actingAs($reviews->author,'sanctum')->get(route('books.reviews.index',['book' => $reviews->book_id,'token' =>'my']));

        $response1->assertOk();
        $response1->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ReviewController::class,
            'store',
            \App\Http\Requests\ReviewStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $User_review = fake()->text();
         $author = Author::factory()->create();
        $book = Book::factory()->create();


        $response = $this->actingAs($book->author,'sanctum')->post(route('books.reviews.store',['book' => $book->id]), [
            'User_review' => $User_review,
        ]);

        $reviews = Review::query()
            ->where('User_review', $User_review)
            ->where('book_id', $book->id)
            ->where('author_id', $author->id)
            ->get();
        $this->assertCount(1, $reviews);
        $review = $reviews->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $review = Review::factory()->create();

        $response = $this->actingAs($review->author,'sanctum')->get(route('books.reviews.show', ['book'=> $review->book_id ,'review'=> $review->id]));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ReviewController::class,
            'update',
            \App\Http\Requests\ReviewStoreRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $review = Review::factory()->create();
        $User_review = fake()->text();



        $response = $this->actingAs($review->author,'sanctum')->put(route('books.reviews.update', ["book" => $book->id, "review" => $review->id]), [
            'User_review' => $User_review,
            'book_id' => $book->id,
            'author_id' => $author->id,
        ]);

        $review->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($User_review, $review->User_review);
        $this->assertEquals($book->id, $review->book_id);
        $this->assertEquals($author->id, $review->author_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $review = Review::factory()->create();

        $response = $this->actingAs($review->author,'sanctum')->delete(route('books.reviews.destroy', ['book' => $review->book_id , 'review'=> $review->id]));

        $response->assertStatus(200);

        $this->assertModelMissing($review);
    }
}
