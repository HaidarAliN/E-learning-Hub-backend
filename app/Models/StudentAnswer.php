<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;
    public $table="student_answers";

    public function submition(){
        return $this->belongsTo(StudentSubmission::class, 'id');
    }
}
