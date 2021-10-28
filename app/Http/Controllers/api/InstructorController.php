<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\Course;
use App\Models\CourseType;
use DB;

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
        $course = new Course;//create new course
        $course->name = $request->name;
        $course->description = $request->description;
        $course->type_id = $request->type_id;
        $course->major_id = "1";
        $course->progress = 0;
        $user->courses()->save($course);//save the course using the user foreign key
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $course
        ], 200);
    }

    public function getOngoingCourses(){
        $user = User::find(auth()->user()->id);
        $courses = $user->courses()//search for all courses the has this users id as foreign key
                            ->join('course_types', 'courses.type_id', '=', 'course_types.id')//joining to get the name of the course type
                            ->ongoingCourses()//progress < 100
                            ->get(['courses.*','course_types.name as course_type']);
        if(count($courses) > 0){
            return response()->json($courses, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function getFinishedCourses(){
        $user = User::find(auth()->user()->id);
        $courses = $user->courses()
            ->join('course_types', 'courses.type_id', '=', 'course_types.id')
            ->finishedCourses()//progress = 100
            ->get(['courses.*','course_types.name as course_type']);
        if(count($courses) > 0){
            return response()->json($courses, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function getDashboard(){
        $user = User::find(auth()->user()->id);
        $count = count($user->courses()->get());
        $courses_ids = $user->courses()->pluck('id');
        $student_counts = count(DB::Table('participants')->whereIn('course_id',$courses_ids)//get the count of all enrolled student in the course
                                                         ->where('status',1)
                                                         ->get());
        $courses = $user->courses()
            ->join('course_types', 'courses.type_id', '=', 'course_types.id')//get the count of all finished courses
            ->finishedCourses()
            ->get(['courses.*','course_types.name as course_type']);
        $finished_classes = count($courses);
        $response['courses_count'] = $count;
        $response['student_count'] = $student_counts;
        $response['finished_courses'] = $finished_classes;
        return response()->json($response, 201);
    }

    public function getNavInfo(){
        $user = User::find(auth()->user()->id);
        $response['name'] = $user->first_name;
        $notification_count = count($user->notifications()->notRead()->get());
        $response['notification_count'] = $notification_count;
        return response()->json($response, 200);
    }

    public function getCourseTypes(){
        $response = CourseType::get();//return all the course types
        return response()->json($response, 200);
    }

     public function getNotifications(){
        $user_id = auth()->user()->id;
        $notifications = User::find($user_id)->notifications()->orderby('is_read')->orderby('id', 'DESC')->get();
        if(count($notifications) > 0){
            return response()->json($notifications, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function setNotificationAsRead(Request $request){
        $user_id = auth()->user()->id;
        $notifications = User::find($user_id)->notifications()->find($request->notification_id);
        if($notifications){
            $notifications->is_read = 1;
            $notifications->save();
            return response()->json($notifications, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

}
