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

class InstructorCoursesController extends Controller
{
    public function uploadMaterial(Request $request, $id){

        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){
        $new_material = new Material;
        $new_material->name = $request->name;
        $new_material->description = $request->description;
        $new_material->path = $request->path;
        $new_material->course_id = $id;
        $new_material->save();

        return $this->courseDashboardInfo($id);
    }else{
        $response['status'] = "unauth";
        return response()->json($response);
        } 
    }

    public function courseDashboardInfo($id){

        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){
        $lectures = Material::where('course_id', $id)
                            ->get();
        $response['lectures_count'] = count($lectures);

        $students = Participant::where('course_id', $id)
                            ->get();

        $response['students_count'] = count($students);

        $progress = Course::where('instructor_id',  auth()->user()->id)
                            ->where('id', $id)
                            ->get(['progress']);

        $response['progress'] = $progress[0]['progress'];
        return response()->json($response);
        }else{
        $response['status'] = "unauth";
        return response()->json($response);
        } 
    }
    
    public function getMaterial($id){

        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){
        $lectures = Material::where('course_id', $id)
                            ->get();
         return response()->json($lectures);  
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }                   
    }

    public function courseInfo($id){

        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){

        $course = Course::where('id', $id)
                        ->get();
        return response()->json($course);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }                 
    }

    public function createQuiz(Request $request, $id){

        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){

            $quiz = new Quiz;
            $quiz->name = $request->name;
            $quiz->course_id = $id;
            $quiz->save();
            return $this->getQuizzes($id);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }
    }

    public function getQuizzes($id){
        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){
            $quizzes = Quiz::where('course_id', $id)
                            ->get();
            return response()->json($quizzes);
        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }
    }

    public function addQuestions($id){

        $inst_id = auth()->user()->id;
        $course_exist = $this->is_exist($inst_id, $id);
        if($course_exist){
            
            

        }else{
            $response['status'] = "unauth";
            return response()->json($response);
        }

    }

    public function is_exist($instructor_id, $course_id){

        $course = Course::where('instructor_id', $instructor_id)
                            ->where('id', $course_id)
                            ->get('id');
        
         $course_exist = count($course);
        if($course_exist == 1){
            return true;
        }
        else{
            return false;
        }
    }


}
