<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('type_id');
            $table->integer('major_id');
            $table->integer('progress');
            $table->integer('instructor_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('course_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('course_majors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('participants', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('course_id');
            $table->boolean('status')->default('0');//0->pending 1->enroller
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('path');
            $table->integer('course_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('course_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('first_answer');
            $table->string('second_answer');
            $table->string('third_answer')->nullable();
            $table->integer('right_answer');
            $table->boolean('type'); //0 for mcq 1 for true if false
            $table->integer('quiz_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('quiz_id');
            $table->boolean('submited')->default('0'); //0 for pending 1 for submited
            $table->string('score')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('submission_id');
            $table->integer('question_id');
            $table->boolean('answer'); //0 for false 1 for true
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('questions');
    }
}
