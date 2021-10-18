<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\Course;
use App\Models\Notification;
use App\Models\StudentSubmission;
use App\Models\StudentAnswer;
use App\Models\Question;
use DB;

class studentCourseController extends Controller
{
    public function courseDashboardInfo($id){
        $user_id = auth()->user()->id;
        $course = Course::find($id);
        $lectures = $course->materials()->get();
        $response['lectures_count'] = count($lectures);
        $quizzes = count(Course::find($id)->quizzes()->get());
        $response['quiz_count'] = $quizzes;
        $response['progress'] = $course->progress;
        return response()->json($response);
    }

    public function getMaterials($id){
        $user_id = auth()->user()->id;
        $materials = Course::find($id)->materials()->get();
        if(count($materials)>0){
            return response()->json($materials, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function courseGetQuizzes($id){
        $course = Course::find($id);
        $quizzes= DB::Table('quizzes')->select('id','name')->where('course_id',$id)->get();
        return response()->json($quizzes, 200);
    }

    public function courseStartQuiz($quizId){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $submission_exist = $user->quizSubmission()->where('quiz_id',$quizId)->get();
        if(count($submission_exist)==0){
            $new_sub = new StudentSubmission;
            $new_sub->quiz_id = $quizId;
            $response = $user->quizSubmission()->save($new_sub);
            return response()->json($response, 200);
        }else{
            return response()->json($submission_exist, 200);
        }

    }

    public function courseGetQuizzeQuestions($quizId){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $submission = $user->quizSubmission()->where('quiz_id',$quizId)->first();
        if($submission->submited == 1){//check is student allread submitted all questions
            $response['status'] = "quiz done";
            return response()->json($response, 200);    
        }else{
            $answered_questions = StudentAnswer::where('student_id', $user_id)->where('submission_id',$submission->id)->pluck('question_id');//get list of all answerd question id's for this quiz
            $question = Question::where('quiz_id', $quizId)//get the next unanswered question
                                ->whereNotIn('id',$answered_questions)
                                ->first();
            if(!empty($question)){//if new question exist
                return response()->json($question, 200);    
            }else{
                $submission->submited = 1;//if no new questions exist uset the submition as done
                $submission->save();
                $response['status'] = "quiz done";
                return response()->json($response, 200);  
            }
        }
    }

        public function courseAnswerQuizzeQuestion($quizId, Request $request){
            $user_id = auth()->user()->id;
            $user = User::find($user_id);
            $question_id = $request->question_id;
            $submission = $user->quizSubmission()->where('quiz_id',$quizId)->first();//get the submission object
            $answer = $request->answer;
            $new_answer = new StudentAnswer;//create new answer
            $new_answer->student_id = $user_id;
            $new_answer->question_id = $question_id;
            $new_answer->answer =  $answer;
            $response =  $submission->quizSubmission()->save($new_answer);//save the new answer in the database
            return response()->json($response, 200);  
        }

    




}
