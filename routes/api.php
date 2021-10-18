<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\InstructorController;
use App\Http\Controllers\api\InstructorCoursesController;
use App\Http\Controllers\api\StudentController;
use App\Http\Controllers\api\StudentCourseController;

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
    'middleware' => ['auth.admin'],
    'prefix' => 'admin'

], function () {
    Route::post('/register-student', [AuthController::class, 'registerStudent']);   
    Route::post('/register-intructor', [AuthController::class, 'registerInstructor']);   
});

Route::group([
    'middleware' => 'api',
    'middleware' => ['auth.instructor'],
    'prefix' => 'instructor'
], function () {
    Route::post('/create-course', [InstructorController::class, 'create_course']);   
    Route::get('/get-Ongoing-courses', [InstructorController::class, 'getOngoingCourses']);   
    Route::get('/get-Finished-courses', [InstructorController::class, 'getFinishedCourses']);   
    Route::get('/dashboard', [InstructorController::class, 'getDashboard']);   
    Route::get('/navInfo', [InstructorController::class, 'getNavInfo']);   
    Route::get('/notifications', [InstructorController::class, 'getNotifications']);   
    Route::post('/notifications/mark-read', [InstructorController::class, 'setNotificationAsRead']);   
    Route::get('/get-course-types', [InstructorController::class, 'getCourseTypes']);   
    Route::get('/course/dashboard/{id}', [InstructorCoursesController::class, 'courseDashboardInfo']);   
    Route::get('/course/info/{id}', [InstructorCoursesController::class, 'courseInfo']);
    Route::post('/course/edit-info/{id}', [InstructorCoursesController::class, 'editCourseInfo']);
    Route::get('/course/get-uploaded-material/{id}', [InstructorCoursesController::class, 'getMaterial']);  
    Route::get('/course/get-material-by-id/{id}', [InstructorCoursesController::class, 'getMaterialById']);  
    Route::post('/course/edit-material/{id}', [InstructorCoursesController::class, 'editMaterial']);  
    Route::post('/course/remove-material/{id}', [InstructorCoursesController::class, 'removeMaterial']);  
    Route::post('/course/upload-new-material/{id}', [InstructorCoursesController::class, 'uploadMaterial']); 
    Route::post('/course/create-quiz/{id}', [InstructorCoursesController::class, 'createQuiz']);  
    Route::get('/course/get-quiz/{id}', [InstructorCoursesController::class, 'getQuizzes']); 
    Route::post('/course/get-quiz-questions/{id}', [InstructorCoursesController::class, 'getQuizQuestions']);    
    Route::post('/course/get-quiz-question-by-id/{id}', [InstructorCoursesController::class, 'getQuizQuestionById']);    
    Route::post('/course/add-question/{id}', [InstructorCoursesController::class, 'addQuestion']);  
    Route::post('/course/edit-question/{id}', [InstructorCoursesController::class, 'editQuestion']);  
    Route::post('/course/remove-question/{id}', [InstructorCoursesController::class, 'removeQuestion']);  
    Route::post('/course/enroll-student/{id}', [InstructorCoursesController::class, 'enrollStudent']);  
    Route::post('/course/remove-student/{id}', [InstructorCoursesController::class, 'removeStudent']);  
    Route::get('/course/get-student-info/{id}', [InstructorCoursesController::class, 'getStudentInfo']);  
    Route::get('/test', [InstructorCoursesController::class, 'test']);    
});

Route::group([
    'middleware' => 'api',
    'middleware' => ['auth.student'],
    'prefix' => 'student'
], function () {
    Route::get('/test', [StudentController::class, 'test']);   
    Route::post('/search-for-course', [StudentController::class, 'getCourseByName']);  
    Route::get('/get-ongoing-courses', [StudentController::class, 'getOngoingCourses']);   
    Route::get('/get-finished-courses', [StudentController::class, 'getFinishedCourses']);   
    Route::get('/get-notifications', [StudentController::class, 'getNotifications']); 
    Route::post('/notifications/mark-read', [StudentController::class, 'setNotificationAsRead']);   
    Route::get('/get-user-info', [StudentController::class, 'getUserInfo']);   
    Route::get('/navInfo', [StudentController::class, 'getNavInfo']);   
    Route::post('/enroll-in-course', [StudentController::class, 'enrollInCourse']);   
    Route::get('/course/get-upload-materials/{id}', [StudentCourseController::class, 'getMaterials']);
    Route::get('/course/dashboard/{id}', [StudentCourseController::class, 'courseDashboardInfo']);   
    Route::get('/course/get-quizzes/{id}', [StudentCourseController::class, 'courseGetQuizzes']);   
    Route::get('/course/start-quiz/{id}', [StudentCourseController::class, 'courseStartQuiz']);   
    Route::get('/course/get-quiz-questions/{quizId}', [StudentCourseController::class, 'courseGetQuizzeQuestions']);   
    Route::post('/course/answer-quiz-questions/{quizId}', [StudentCourseController::class, 'courseAnswerQuizzeQuestion']);   
});





