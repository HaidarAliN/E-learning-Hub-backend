<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\Course;


class InstructorController extends Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function create_course(Request $request){

        $course = new Course;
        $course->name = $request->name;
        $course->description = $request->description;
        $course->type_id = $request->type_id;
        $course->major_id = $request->major_id;
        $course->progress = 0;
        $course->instructor_id = auth()->user()->id;
        $course->save();

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $course
        ], 201);
    }

}
