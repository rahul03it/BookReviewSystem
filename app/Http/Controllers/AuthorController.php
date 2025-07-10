<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorStoreRequest;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    
    public function destroy(Request $request, Author $author): JsonResponse
    {
        $author->delete();

        return response()->json([
            'message'=>'Author deleted successfully.'
        ]);
    }
}
