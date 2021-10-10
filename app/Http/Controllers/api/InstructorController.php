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

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function create_course(Request $request){

        $user = User::find(auth()->user()->id);
        $course = new Course;
        $course->name = $request->name;
        $course->description = $request->description;
        $course->type_id = $request->type_id;
        $course->major_id = $request->major_id;
        $course->progress = 0;
        $user->courses()->save($course);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $course
        ], 201);
    }

    public function getOngoingCourses(){
        $user = User::find(auth()->user()->id);
        $courses = $user->courses()
                        ->ongoingCourses()
                        ->get();
        if(count($courses) > 0){
            return response()->json($courses, 201);
        }else{
            $response['status'] = "empty";
            return response()->json([$response], 404);
        }
    }

    public function getFinishedCourses(){
        $user = User::find(auth()->user()->id);
        $courses = $user->courses()
                        ->finishedCourses()
                        ->get();
        if(count($courses) > 0){
            return response()->json($courses, 201);
        }else{
            $response['status'] = "empty";
            return response()->json([$response], 404);
        }
    }

}
