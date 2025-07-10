<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AuthorController
 */
final class AuthorControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function test_destroy_deletes_and_redirects(): void
    {
        $author = Author::factory()->create();

        $response = $this->actingAs($author, 'sanctum')->delete(route('authors.destroy', $author));

        $response->assertOk()
         ->assertJson([
             'message' => 'Author deleted successfully.',
         ]);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }


}
