<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\Course;
use App\Models\Material;
use App\Models\Participant;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Notification;
use DB;
// use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use File;

class InstructorCoursesController extends Controller
{
    public function uploadMaterial(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $file = $request->base64file;
            $file_name =  $request->name;
            $file_name = "$file_name(".rand(10,1000).")".'.'.'pdf';
            $path=public_path();
            $pdf_decoded = base64_decode ($file);
            $destinationPath = public_path() . "/UploadedMaterials/" . $file_name;             
            file_put_contents($destinationPath, $pdf_decoded);
            //                                                 File::delete('UploadedMaterials/qwe.pdf');
            $new_material = new Material;
            $new_material->name = $request->name;
            $new_material->description = $request->description;
            $new_material->path = $file_name;
            $course->materials()->save($new_material);
            return $this->courseDashboardInfo($id);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        } 
    }

    public function courseDashboardInfo($id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $lectures = $course->materials()->get();
            $response['lectures_count'] = count($lectures);
            $course_users = count(Course::find($id)->enrolledUsers()->get());
            $response['students_count'] = $course_users;
            $response['progress'] = $course->progress;
            return response()->json($response);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        } 
    }
    
    public function getMaterial($id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
        $lectures = $course->materials()->get();
        if($lectures){
            return response()->json($lectures);  
        }else{
            $response['status'] = "empty";
            return response()->json($response, 404);
        }
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }                   
    }

    public function getMaterialById($id){
        $user_id = auth()->user()->id;
        $lecture = Material::find($id);
        if($lecture){
            return response()->json($lecture);  
        }else{
            $response['status'] = "empty";
            return response()->json($response, 404);
        }
        
        
    }

    public function courseInfo($id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $course= User::find($user_id)->courses()
                            ->join('course_types', 'courses.type_id', '=', 'course_types.id')
                            ->where('courses.id', $id)
                            ->get(['courses.*','course_types.name as course_type']);
            return response()->json($course);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }                 
    }

    public function editCourseInfo(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $course->name = $request->name;
            $course->description = $request->description;
            $course->type_id = $request->type_id;
            $course->progress = $request->progress;
            $course->save();
            return response()->json($course);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }                 
    }

    public function createQuiz(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $course = Course::find($id);
            $quiz = new Quiz;
            $quiz->name = $request->name;
            $course->quizzes()->save($quiz);
            return $this->getQuizzes($id);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }
    }

    public function getQuizzes($id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $quizzes = $course->quizzes()
                              ->get();
            if(count($quizzes)>0){
                return response()->json($quizzes);
            }else{
                $response['status'] = "empty";
                return response()->json($response);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }
    }

    public function addQuestion(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $quiz = $course->quizzes()->find($request->quiz_id);
            if($quiz){
                $question = new Question;
                $question->content = $request->content;
                $question->first_answer = $request->first_answer;
                $question->second_answer = $request->second_answer;
                $question->third_answer = $request->third_answer;
                $question->right_answer = $request->right_answer;
                $question->type = $request->type;
                $quiz->questions()->save($question);
                $questions =  $quiz->questions()->get();
                return response()->json($questions);
            }else{
                $response['status'] = "empty";
                return response()->json($response, 404);
            }  
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }
    }

    public function editQuestion(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $quiz = $course->quizzes()->find($request->quiz_id);
            if($quiz){
                $question = $quiz->questions()->find($request->id);
                if($question){
                    $question->content = $request->content;
                    $question->first_answer = $request->first_answer;
                    $question->second_answer = $request->second_answer;
                    $question->third_answer = $request->third_answer;
                    $question->right_answer = $request->right_answer;
                    $question->type = $request->type;
                    $question->save();
                    return response()->json($question);
                }else{
                    $response['status'] = "empty";
                    return response()->json([$response], 404);
                }
            }else{
                $response['status'] = "empty";
                return response()->json([$response], 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json([$response], 403);
        }
    }

    public function removeQuestion(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $quiz = $course->quizzes()->find($request->quiz_id);
            if($quiz){
                $question = $quiz->questions()->find($request->id);
                if($question){
                    $question->delete();
                    $response['status'] = "deleted";
                return response()->json($response); 
                }else{
                    $response['status'] = "empty";
                    return response()->json($response, 404);
                }
            }else{
                $response['status'] = "empty";
                return response()->json($response, 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response, 403);
        }
    }

    public function getQuizQuestions(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $questions = Quiz::find($request->quiz_id)->questions()->get();
            if(count($questions) > 0){
                return response()->json($questions);
            }else{
                $response['status'] = "empty";
                return response()->json($response, 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }
    }

    public function editMaterial(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $lecture = $course->materials()->find($request->id);
            if($lecture){
                $lecture->name = $request->name;
                $lecture->description = $request->description;
                // $lecture->path = $request->path;
                $lecture->save();
                return response()->json($lecture);  
            }else{
                $response['status'] = "empty";
                return response()->json($response, 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        } 
    }

    public function removeMaterial(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $lecture = $course->materials()->find($request->id);
            if($lecture){
                File::delete("UploadedMaterials/$lecture->path");
                $lecture->delete();
                $response['status'] = "deleted";
                return response()->json($response);  
            }else{
                $response['status'] = "Not found";
                return response()->json([$response], 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        } 
    }

    public function enrollStudent(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $user = User::find($request->student_id)->enrolledCourses()->find($course);//check if student in the pending list
            if($user){
                $participant = $user->pivot;//resturn the pivot table raw
                if($participant){
                    $participant->status = 1;//update status from pending to registerd
                    $participant->save();

                    $token = User::find($request->student_id)->device_token;//get firebase token of the instructor
                    $message="You have been Enrolled in the $course->name course";
                    $title="Enrollment Acceptance";
                    $this->sendNotification($token, $title, $message);//send push notification to the instructor
                    $notification = new Notification;//create new notification in database
                    $notification->sent_to = $request->student_id;
                    $notification->body = $message;
                    $notification->course_id = $id;
                    $notification->save();//save the notifcation in the database


                    return response()->json($participant);
                }else{
                    $response['status'] = "Not found";
                    return response()->json($response, 200);
                }
            }else{
                $response['status'] = "Not found";
                return response()->json($response, 200);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response, 403);
        } 
    }

    public function removeStudent(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $user = User::find($request->student_id)->enrolledCourses()->find($course);
            if($user){
                $participant = $user->pivot;
                if($participant){
                    $participant->delete();
                    $response['status'] = "deleted";
                    return response()->json($response);
                }else{
                    $response['status'] = "Not found";
                    return response()->json($response, 404);
                }
            }else{
                $response['status'] = "Not found";
                return response()->json($response, 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response, 403);
        } 
    }

    public function getStudentInfo($id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $participant = User::join('participants', 'users.id', '=', 'participants.user_id')
            ->where('participants.course_id',$id)
            ->orderBy('participants.status')
            ->get(['users.*','participants.*']);
            if(count($participant)>0){

                return response()->json($participant, 200);
            }else{
                $response['status'] = "empty";
            return response()->json($response, 200);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json($response, 403);
        } 
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

    public function test(){
        // $user = User::instructor()->get(); use scope example

        //student endroll to course
        // $course = Course::find(12);
        // User::find(auth()->user()->id)->enrolledCourses()->save($course);
        
        //intructer accept course request access
        $course = Course::find(12);//12 is the course id
        $update = User::find(auth()->user()->id)->enrolledCourses()->find($course)->pivot;
        $update->status = 1;
        $update->save();
        return response()->json($update);
    }


}
