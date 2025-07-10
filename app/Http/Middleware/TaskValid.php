<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helper\DestroyHelper;
use App\Models\Review;
use App\Models\Author;
use App\Models\Book;

class TaskValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request , Closure $next)
    {

        $validAuthor = Author::find(auth()->id());

        if(!$validAuthor){
            return response()->json([
                "Sorry, user not exist in database"
            ],401);
        }




        return $next($request);
    }
}
