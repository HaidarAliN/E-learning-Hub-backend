<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AdminController extends Controller
{
    public function resetPassword(Request $request){
        $user = User::where('email',$request->email)->first();
        $user->password = bcrypt($request->password);//encrypt the received password
        $user->save();
        $response['status'] ="updated!";
        return response()->json($response, 200);
    }
  
}
