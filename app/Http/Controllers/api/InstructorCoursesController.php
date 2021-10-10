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

class InstructorCoursesController extends Controller
{
    public function uploadMaterial(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $new_material = new Material;
            $new_material->name = $request->name;
            $new_material->description = $request->description;
            $new_material->path = $request->path;
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
        if($lecture){
            return response()->json($lectures);  
        }else{
            $response['status'] = "empty";
            return response()->json([$response], 404);
        }
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }                   
    }

    public function courseInfo($id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
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
            $course->major_id = $request->major_id;
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
                return response()->json([$response], 404);
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

    public function getQuizQuestions(Request $request, $id){
        $user_id = auth()->user()->id;
        $course = User::find($user_id)->courses()->find($id);//check if this user is the instructor of the course
        if($course){
            $questions = Quiz::find($request->quiz_id)->questions()->get();
            if(count($questions) > 0){
                return response()->json($questions);
            }else{
                $response['status'] = "empty";
                return response()->json([$response], 404);
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
                $lecture->path = $request->path;
                $lecture->save();
                return response()->json($lecture);  
            }else{
                $response['status'] = "empty";
                return response()->json([$response], 404);
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
            $user = User::find($request->student_id)->enrolledCourses()->find($course);
            if($user){
                $participant = $user->pivot;
                if($participant){
                    $participant->status = 1;
                    $participant->save();
                    return response()->json($participant);
                }else{
                    $response['status'] = "Not found";
                    return response()->json([$response], 404);
                }
            }else{
                $response['status'] = "Not found";
                return response()->json([$response], 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json([$response], 403);
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
                    return response()->json([$response], 404);
                }
            }else{
                $response['status'] = "Not found";
                return response()->json([$response], 404);
            }
        }else{
            $response['status'] = "unauth";
            return response()->json([$response], 403);
        } 
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
