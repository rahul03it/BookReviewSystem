<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TaskValid;

Route::apiResource('authors', App\Http\Controllers\AuthorController::class)->only([
    'destroy'])->middleware('auth:sanctum');

Route::apiResource('books', App\Http\Controllers\BookController::class)->middleware('auth:sanctum');


Route::apiResource('books.reviews', App\Http\Controllers\ReviewController::class)->middleware('auth:sanctum');

Route::post('/register' ,[App\Http\Controllers\RegisterController::class,'registerUser'])->name('register');

Route::post('/login' , [App\Http\Controllers\RegisterController::class,'loginUser'])->name('login');
