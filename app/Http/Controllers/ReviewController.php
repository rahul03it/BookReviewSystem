<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Helper\DestroyHelper;
// use App\Models\Author;

class ReviewController extends Controller
{
    public function index(Request $request ,Book $book): ReviewCollection
    {
        $authenticate = $request->query('token');
        $userId = auth()->id();

        if($authenticate =="my"){
            $reviews = Review::where('author_id',$userId)
                             ->where('book_id',$book->id)->get();
        }
        if($authenticate == "all"){
            $reviews = Review::where('book_id',$book->id)->get();
        }


        return new ReviewCollection($reviews);
    }

//--------------------------------------------------------------------->

    public function store( ReviewStoreRequest $request , Book $book ): ReviewResource
    {

        $authId = auth()->id();
        $bookId = $book->id;

        $newArr = ['author_id'=>$authId,
                    'book_id' =>$bookId];


        $review = Review::create(
                  array_merge($request->validated() , $newArr));

        return new ReviewResource($review);
    }

    //------------------------------------------------------------------>

    public function show(Request $request,Book $book , Review $review)
    {

        if($review->book_id != $book->id){
                return response()->json(["sorry , no related book is in this review id"],400);
            }

        return new ReviewResource($review);
    }

    //------------------------------------------------------------------>

    public function update(ReviewStoreRequest $request ,Book $book , Review $review)
    {
        $authId = auth()->id();

        if($authId != $review->author_id){

            return response()->json(["sorry ,you not authorize to edit this review"],400);

        }


            if($review->book_id != $book->id){
                return response()->json(["sorry , no related book is in this review id"],400);
            }

        $review->update($request->validated());

        return new ReviewResource($review);
    }

    //------------------------------------------------------------------>

    public function destroy(Request $request, Book $book ,Review $review)
    {

        if(($review->author_id == auth()->id()) && ($review->book_id == $book->id)){
            $review->delete();
            return response()->json([
                "Review deleted successfully" ,200
            ]);
        }

        return response()->json([
                "Invlid user to perform this opertion",400
            ]);

    }



}
