<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterStoreRequest;
use App\Models\Author;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerUser(RegisterStoreRequest $request){

       $data = [
                    "name" => $request->name ,
                    "password" => $request->password,
       ];

       $author = Author::create($data);
       $token = $author->createToken('auth-token', ['*'])->plainTextToken;

       return response()->json([
        'meaasge' => "Registration process successful",
        "author" => $author->only(['id','name']),
        'token' => $token,
       ],201);
    }


    public function loginUser(Request $request){

         $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $author = Author::where('name',$request->name)->first();

        if(!$author || !Hash::check($request->password,$author->password)){
            return response()->json([
                'message' => "Invalid user or password"
            ],401);
        }

        $token = $author->createToken('auth-token',['*'])->plainTextToken;

        return response()->json([
            'message' => 'login successful',
            'author' => $author->only(['id','name']),
            'token' => $token,
        ]);

    }
}
