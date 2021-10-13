<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubmission extends Model
{
    use HasFactory;
    public $table="student_submissions";

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function quizSubmission(){
        return $this->hasMany(StudentAnswer::class, 'submission_id');
    }

    public function scopeNotSubmited($query){
        return $query->where('submited',0);
    }

    public function scopeSubmited($query){
        return $query->where('submited',1);
    }

}
