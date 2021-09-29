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

class InstructorCoursesController extends Controller
{
    public function uploadMaterial(Request $request, $id){
        $new_material = new Material;
        $new_material->name = $request->name;
        $new_material->description = $request->description;
        $new_material->path = $request->path;
        $new_material->course_id = $id;
        $new_material->save();

        return $this->courseDashboardInfo($id);
    }

    public function courseDashboardInfo($id){
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
    }
    
    public function getMaterial($id){
        $lectures = Material::where('course_id', $id)
                            ->get();
         return response()->json($lectures);                    
    }

    public function courseInfo($id){

        $course = Course::where('id', $id)
                        ->get();
        return response()->json($course);                 
    }


}
