<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class, 'course_id');
    }

    public function materials(){
        return $this->hasMany(Material::class, 'course_id');
    }

    public function types(){
        return $this->belongsTo(CourseType::class, 'id');
    }

    public function enrolledUsers(){
        return $this->belongsToMany(User::class, 'participants', 'course_id', 'user_id')->withPivot('status');
    }

    public function scopeOngoingCourses($query){
        return $query->where('progress', '<', '100');
    }

    public function scopeFinishedCourses($query){
        return $query->where('progress', '=', '100');
    }
}
