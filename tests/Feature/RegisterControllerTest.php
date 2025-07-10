<?php

namespace Tests\Feature;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class RegisterControllerTest extends TestCase
{

      use AdditionalAssertions,WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_registerUser(): void
    {
        $name = fake()->userName();
        $password = fake()->password();

        $response = $this->post(route('register'),['name' => $name,'password'=>$password]);

        $response->assertCreated(); //check it created or not

        $this->assertDatabaseHas('authors', [ //check the name data has in database or not
        'name' => $name,
        ]);

        $author = Author::where('name',$name)->first();  // first data of same name
        $this->assertTrue(Hash::check($password,$author->password));  // check password it is optional

        $response->assertJsonStructure([]);
    }

    public function test_loginUser():void
    {
        $password = '12345';
        $user = Author::factory()->create([
            'password'=> bcrypt($password)
        ]);
        $response = $this->post(route('login',['name'=> $user->name ,'password'=> $password]));

        $author = Author::query()
            ->where('name', $user->name)
            ->where('password', $user->password)
            ->get();

            $this->assertCount(1, $author);

            $response->assertStatus(200);
            $response->assertJsonStructure([]);
    }
}
