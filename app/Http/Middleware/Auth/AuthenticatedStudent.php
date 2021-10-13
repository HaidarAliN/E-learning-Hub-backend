<?php

namespace App\Http\Middleware\Auth;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthenticatedStudent
{

    public function handle($request, Closure $next) 
    {
        $user = auth()->user();
        if(empty($user)){
            $response['access'] = "denied";
            return response()->json([$response], 403);
        }else{
            $user = auth()->user()->user_type_id;
            if($user != 3){
                $response['access'] = "denied";
                return response()->json([$response], 403);
            }
        }

        return $next($request);
    }
}

