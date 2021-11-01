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
use App\Models\Quiz;
use DB;

class studentCourseController extends Controller
{
    public function courseDashboardInfo($id){
        $course = Course::find($id);
        $lectures = $course->materials()->get();
        $response['lectures_count'] = count($lectures);//get the count of uploaded materials in the course
        $quizzes = count(Course::find($id)->quizzes()->get());
        $response['quiz_count'] = $quizzes;
        $response['progress'] = $course->progress;
        return response()->json($response);
    }

    public function getMaterials($id){
        $materials = Course::find($id)->materials()->get();//search all the materials for this course
        if(count($materials)>0){
            return response()->json($materials, 200);
        }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }

    public function courseGetQuizzes($id){
        $quizzes= DB::Table('quizzes')
                    ->select('id','name')
                    ->where('course_id',$id)
                    ->get();
        return response()->json($quizzes, 200);
    }

    public function courseStartQuiz($quizId){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $submission_exist = $user->quizSubmission()//check if the student already has a submission for this quiz
                                 ->where('quiz_id',$quizId)
                                 ->get();
        if(count($submission_exist)==0){
            $new_sub = new StudentSubmission;
            $new_sub->quiz_id = $quizId;
            $response = $user->quizSubmission()->save($new_sub);
            return response()->json($response, 200);
        }else{
            return response()->json($submission_exist[0], 200);
        }

    }

    public function courseGetQuizzeQuestions($quizId){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $submission = $user->quizSubmission()
                           ->where('quiz_id',$quizId)
                           ->first();
        if($submission->submited == 1){//check is student allread submitted all questions
            $response['status'] = "quiz done";
            return response()->json($response, 200);    
        }else{
            $answered_questions = StudentAnswer::where('student_id', $user_id)
                                                ->where('submission_id',$submission->id)
                                                ->pluck('question_id');//get list of all answerd question id's for this quiz
            $question = Question::where('quiz_id', $quizId)//get the next unanswered question
                                ->whereNotIn('id',$answered_questions)
                                ->first();
            if(!empty($question)){//if new question exist
                return response()->json($question, 200);    
            }else{
                $submission->submited = 1;//if no new questions exist set the submition as done
                $right_ans_counts = count($submission->quizSubmission()->where('answer',1)->get());//start calculating the score
                $questions_count = count($submission->quizSubmission()->get());
                $score = $right_ans_counts.'/'.$questions_count;
                $submission->score = $score;
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

    public function getStudentsGraphscore($id){
        $quiz_ids = Quiz::where('course_id',$id)->pluck('id');//get the id of all the quizzes of the course
        if(count($quiz_ids)>0){
            $temp_score = -1;//initialize the temp variables
            $temp_studentId=0;
            $temp_studentSubmissionId;
            foreach ($quiz_ids as $quiz_id){
                
                $scores = StudentSubmission::where([['quiz_id', $quiz_id],['submited',1]])->get();//get the submissions of students for the current quiz
                if(count($scores)>0){
                    foreach ($scores as $student_score) {
                        $score_string = explode('/',$student_score['score']);//split the score
                        $score = (int)$score_string[0];
                        if($temp_score < $score){//check for the highest score
                            $temp_score = $score;
                            $temp_studentId = $student_score['student_id'];
                            $temp_studentSubmissionId =$student_score['id'];
                            $temp_quizName = $student_score['student_id'];
                        }
                    }
                    $result = User::find($temp_studentId)
                                    ->join('student_submissions', 'student_submissions.student_id', '=', 'users.id')
                                    ->where('student_submissions.id',$temp_studentSubmissionId)
                                    ->get(['users.first_name as name','student_submissions.quiz_id as quizId', 'student_submissions.score as score']);
                    $quiz_name = Quiz::find($result[0]['quizId']);
                    $temp_score = -1;
                    $response['name'] = $result[0]['name'].'/'.$quiz_name['name'];
                    $final_score_string = explode('/',$result[0]['score']);
                    $response['Top_Scores']  = $final_score_string[0]*4/(int)$final_score_string[1];//calculate the GPA of the student who has the highest score
                    $data[]=$response;
                }else{
                    continue;
                }
            }
            if($temp_studentId != 0){
                return response()->json($data, 200);
            }else{
                $response['status'] = "empty";
                return response()->json($response, 200);
            }
            }else{
            $response['status'] = "empty";
            return response()->json($response, 200);
        }
    }
}
