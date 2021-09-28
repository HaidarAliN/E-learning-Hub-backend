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


    }
}
