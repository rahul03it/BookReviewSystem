<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function index(Request $request): BookCollection
    {
        $authId = auth()->id();
        $auth = $request->query('token');

        if($auth == 'my'){
             $books = Book::where('author_id',$authId)->get();
        }
        if($auth == 'all'){
            $books = Book::all();
        }


        return new BookCollection($books);
    }

    //------------------------------------------------------------------>

    public function store(BookStoreRequest $request): BookResource
    {
        $authId = auth()->id();
        $arr = [ 'author_id' => $authId ];

        $book = Book::create(array_merge($request->validated() , $arr));

        return new BookResource($book);
    }

    //------------------------------------------------------------------>

    public function show(Request $request, Book $book): BookResource
    {
        return new BookResource($book);
    }

    //------------------------------------------------------------------->

    public function update(BookStoreRequest $request, Book $book)
    {
        $authId = auth()->id();
        // $authId = $request->user->id;

        if($authId != $book->author_id){
            return response()->json("Only the author is  authorize to update this book", 400);
        }

        $arr = ["author_id" => $authId];

        $book->update(array_merge($request->validated() , $arr));

        return new BookResource($book);
    }

    public function destroy(Request $request, Book $book)
    {
        $authId = auth()->id();
        if($authId != $book->author_id){
            return response()->json("Only real author of book can delete the book" , 400);
        }


        $book->delete();

        return response()->json("Book deleted successfully",200);
    }
}
