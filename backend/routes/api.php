<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\InstructorController;
// use App\Http\Controllers\api\testController;
use App\Http\Controllers\api\testController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
    // Route::get('/create-course', [InstructorController::class, 'create_course']);    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/create-course', [InstructorController::class, 'create_course']);    
    Route::get('/get-Ongoing-courses', [InstructorController::class, 'getOngoingCourses']);    
    Route::get('/get-Finished-courses', [InstructorController::class, 'getFinishedCourses']);    
});

