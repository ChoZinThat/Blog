<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryApiController;


Route::post('/login', function() {
    $email = request()->email;
    $password = request()->password;

    if(!$email || !$password){
        return response(['msg' => 'email or password required'], 400);
    }

    $user = User::where('email', $email)->first();
    if(password_verify($password, $user->password)){
        return $user->createToken('api')->plainTextToken;
    }

    return response(['msg' => 'incorrect email or password']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/categories', CategoryApiController::class);
