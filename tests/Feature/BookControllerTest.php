<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BookController
 */
final class BookControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $books = Book::factory()->count(3)->create();
        $user = Author::factory()->create();

        $response1 = $this->actingAs($user,'sanctum')->get(route('books.index', ['token' => 'my']));
        $response1->assertOk();
        $response1->assertJsonStructure([]);

        $response2 = $this->actingAs($user,'sanctum')->get(route('books.index', ['token' => 'all']));

        $response2->assertOk();
        $response2->assertJsonStructure([]);
    }


    #[Test]//pass
    public function store_uses_form_request_validation(): void
    {

        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BookController::class,
            'store',
            \App\Http\Requests\BookStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $title = fake()->sentence(4);
        $description = fake()->text();
        $author = Author::factory()->create();

        $response = $this->actingAs($author,'sanctum')->post(route('books.store'), [
            'title' => $title,
            'description' => $description,
        ]);

        $books = Book::query()
            ->where('title', $title)
            ->where('description', $description)
            ->where('author_id', $author->id)
            ->get();
        $this->assertCount(1, $books);

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($author,'sanctum')->get(route('books.show', $book));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]//pass
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BookController::class,
            'update',
            \App\Http\Requests\BookStoreRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $title = fake()->sentence(4);
        $description = fake()->text();

        $response = $this->actingAs($author,'sanctum')->put(route('books.update', $book), [
            'title' => $title,
            'description' => $description,
            'author_id' => $book->author_id,
        ]);

        $book->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($title, $book->title);
        $this->assertEquals($description, $book->description);
        $this->assertEquals($author->id, $book->author_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($author,'sanctum')->delete(route('books.destroy', $book));

        $response->assertStatus(200);

        $this->assertModelMissing($book);
    }
}
