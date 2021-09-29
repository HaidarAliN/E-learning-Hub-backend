<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table("user_types")->insert([
			"name" => "super_admin",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

        DB::table("user_types")->insert([
			"name" => "instuctor",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

        DB::table("user_types")->insert([
			"name" => "student",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_types")->insert([
			"name" => "Major",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_types")->insert([
			"name" => "Elective",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_types")->insert([
			"name" => "Major elective",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_types")->insert([
			"name" => "General elective",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_types")->insert([
			"name" => "Frechman",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_types")->insert([
			"name" => "Remedial",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_majors")->insert([
			"name" => "Biology",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_majors")->insert([
			"name" => "Chemisty",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);
		DB::table("course_majors")->insert([
			"name" => "Economics",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_majors")->insert([
			"name" => "Art and Science",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_majors")->insert([
			"name" => "Computer Science",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("course_majors")->insert([
			"name" => "Engineering",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("users")->insert([
			"user_type_id" => "1",
			"first_name" => "admin",
			"last_name" => "admin",
			"email" => "admin@ehub.edu",
			"password" => '$2y$10$VGQs7KZIkQdmLFVS915oH.cDa2jwKIJ2u/Qx9eYbbXExomMHizhIS', //admin
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

        DB::table("users")->insert([
			"user_type_id" => "2",
			"first_name" => "Haidar",
			"last_name" => "Ali",
			"email" => "instructor1@ehub.edu",
			"password" => '$2y$10$eZOe1WxlANUvBcuDo0r9BOz4nNJCkjqH3VOtLzVw.RjIgAlDHic4m', //qweqwe
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("users")->insert([
			"user_type_id" => "2",
			"first_name" => "Lara",
			"last_name" => "Makke",
			"email" => "instructor2@ehub.edu",
			"password" => '$2y$10$eZOe1WxlANUvBcuDo0r9BOz4nNJCkjqH3VOtLzVw.RjIgAlDHic4m', //qweqwe
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("courses")->insert([
			"name" => "english",
			"description" => "this is a new course",
			 "type_id" =>"1",
			 "major_id" =>"1",
			 "progress" => 0,
        	"instructor_id" => 2,
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("courses")->insert([
			"name" => "fr",
			"description" => "this is a second new course",
			 "type_id" =>"3",
			 "major_id" =>"3",
			 "progress" => 0,
        	"instructor_id" => 2,
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("courses")->insert([
			"name" => "data structure",
			"description" => "verry good",
			 "type_id" =>"1",
			 "major_id" =>"1",
			 "progress" => 0,
        	"instructor_id" => 3,
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		DB::table("courses")->insert([
			"name" => "information security",
			"description" => "verry importent",
			 "type_id" =>"1",
			 "major_id" =>"1",
			 "progress" => 0,
        	"instructor_id" => 3,
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

    }
}
