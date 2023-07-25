<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware( 'auth:sanctum' )->get( '/user', function ( Request $request ) {
    return $request->user();
} );

Route::controller( AuthenticationController::class )->group( function () {
    Route::post( '/register', 'registration' );
    Route::post( '/user/login', 'userLogin' );
    Route::post( '/send/otp', 'sendOtp' );
    Route::post( '/verify/otp', 'verifyOtp' );
    Route::post( '/reset/password', 'resetPassword' )->middleware( 'verify.token' );
} );

Route::controller( TodoController::class )->group( function () {
    Route::middleware( 'verify.token' )->group( function () {
        Route::get( '/todo/list', 'index' );
        Route::post( '/todo', 'createTodo' );
        Route::patch( '/todo/{id}', 'updateTodo' );
        Route::delete( '/todo/{id}', 'deleteTodo' );
    } );
} );