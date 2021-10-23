<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\Course;
use App\Models\Notification;
use DB;

class studentController extends Controller
{
    public function getCourseByName(Request $request){
        $user_id = auth()->user()->id;
        $name = '%'.$request->course_name.'%';
        $enroled_courses = User::find($user_id)->enrolledCourses()->pluck('id');
        $courses = Course::where('courses.name', 'like', "$name")
                    ->whereNotIn('courses.id',$enroled_courses)
                    ->join('course_types', 'courses.type_id', '=', 'course_types.id')
                    ->ongoingCourses()
                    ->get(['courses.*','course_types.name as course_type']);
        if(count($courses)>0){
            return response()->json($courses, 200);
        }
        $response['status'] = "empty";
        return response()->json($response, 200);
    }

    public function getOngoingCourses(){
        $user_id = auth()->user()->id;
        $enroled_courses =  DB::Table('participants')->select('course_id as id')->where([['status',1],['user_id',$user_id]])->pluck('id');//get the list of id's for all enrolled courses
        $courses = Course::whereIn('courses.id',$enroled_courses)
                        ->join('course_types', 'courses.type_id', '=', 'course_types.id')
                            ->ongoingCourses()
                            ->get(['courses.*','course_types.name as course_type']);
        if(count($courses) > 0){
            return response()->json($courses, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function getFinishedCourses(){
        $user_id = auth()->user()->id;
        $enroled_courses =  DB::Table('participants')->select('course_id as id')->where([['status',1],['user_id',$user_id]])->pluck('id');//get the list of id's for all enrolled courses
        $courses = Course::whereIn('courses.id',$enroled_courses)
                            ->join('course_types', 'courses.type_id', '=', 'course_types.id')
                            ->finishedCourses()
                            ->get(['courses.*','course_types.name as course_type']);
        if(count($courses) > 0){
            return response()->json($courses, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function getNotifications(){
        $user_id = auth()->user()->id;
        $notifications = User::find($user_id)->notifications()->orderby('is_read')->get();
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
            return response()->json([$response], 200);
        }
    }

    public function enrollInCourse(Request $request){
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->first_name;
        $course = Course::find($request->course_id);
        User::find($user_id)->enrolledCourses()->save($course);//enroll student in course
        $response['status'] = "enrolled";
        $instructor_id = $course->instructor_id;
        $token = User::find($instructor_id)->device_token;//get firebase token of the instructor
        $message="$user_name is asking to enroll in the $course->name course";
        $title="Enrollment Request";
        $this->sendNotification($token, $title, $message);//send push notification to the instructor
        $notification = new Notification;//create new notification in database
        $notification->sent_to = $instructor_id;
        $notification->body = $message;
        $notification->course_id = $request->course_id;
        $notification->save();//save the notifcation in the database
        return response()->json($response, 200);
    }

    public function getUserInfo(){
        $user_id = auth()->user()->id;
        $enrolled = User::find($user_id)->enrolledCourses()->where('participants.status',1)->get();
        $response['Courses_count'] = count($enrolled);
        $quizzes = User::find($user_id)->quizSubmission()->submited()->get();
        $response['quiz_submited_count'] = count($quizzes);
        return response()->json($response, 200);

    }

    public function getNavInfo(){
        $user = User::find(auth()->user()->id);
        $response['name'] = $user->first_name;
        $notification_count = count($user->notifications()->notRead()->get());
        $response['notification_count'] = $notification_count;
        return response()->json($response, 200);

    }

    public function getCourseName($course_id){
        $course = Course::find($course_id);
        $response['name'] = $course->name;
        return response()->json($response, 200);
    }

    public function sendNotification($tokento, $title, $subject)
    {
        // $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAA9XhgPRI:APA91bGhQasdew-FbmK29yQY_inJicjg7f4jvbscZ6AWfq9W-F-4vplMfm6hkvzF9AWhd4yij6GhH4sjhgdF5F_UGEcWlA5rar7oZaFmzYZDcUCKeNDGlQJ7ENiWfOWOuf-3AE93FcpY';
  
        $token = $tokento;  
        $from =  $SERVER_API_KEY;
        $msg = array
              (
                'body'  => "$subject",
                'title' => "$title",
                'receiver' => 'erw',
                'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
              );

        $fields = array
                (
                    'to'        => $token,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $from,
                    'Content-Type: application/json'
                );
        //#Send Reponse To FireBase Server 
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        // dd($result);
        curl_close( $ch );
    }

}
