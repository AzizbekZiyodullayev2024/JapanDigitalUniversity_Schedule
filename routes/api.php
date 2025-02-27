<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Models\Subject;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("register",[AuthController::class,'register']);
Route::post("login",[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout',[AuthController::class,'logout']);

    Route::resource('subjects',SubjectController::class);

    // Route::prefix('subjects')->group(function(){
    //     Route::get('/',[SubjectController::class,'index']);
    //     Route::get('/',[SubjectController::class,'store']);
    //     Route::get('/{subject}',[SubjectController::class,'show']);
    //     Route::put('/{subject}',[SubjectController::class,'update']);
    //     Route::delete('/{subject}',[SubjectController::class,'delete']);
    // });
});