<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\InstructorController;
use App\Http\Controllers\api\InstructorCoursesController;

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
    'middleware' => 'api'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group([
    'middleware' => 'api',
    'middleware' => ['auth.instructor'],
    'prefix' => 'instructor'

], function () {
    Route::post('/create-course', [InstructorController::class, 'create_course']);    
    Route::get('/get-Ongoing-courses', [InstructorController::class, 'getOngoingCourses']);    
    Route::get('/get-Finished-courses', [InstructorController::class, 'getFinishedCourses']);    
    Route::get('/course/dashboard/{id}', [InstructorCoursesController::class, 'courseDashboardInfo']);    
    Route::get('/course/info/{id}', [InstructorCoursesController::class, 'courseInfo']);    
    Route::get('/course/get-uploaded-material/{id}', [InstructorCoursesController::class, 'getMaterial']);    
    Route::post('/course/upload-new-material/{id}', [InstructorCoursesController::class, 'uploadMaterial']);    
    Route::post('/course/create-quiz/{id}', [InstructorCoursesController::class, 'createQuiz']);    
    Route::get('/course/get-quiz/{id}', [InstructorCoursesController::class, 'getQuizzes']);    
    Route::get('/course/get-quiz-questions/{id}', [InstructorCoursesController::class, 'getQuizQuestions']);    
    Route::post('/course/add-questions/{id}', [InstructorCoursesController::class, 'addQuestions']);    
});



