<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use JWTAuthException;
use JWTAuth;

class HomeController extends Controller
{
    private $user;
    private $jwtauth;

    public function __construct(User $user, JWTAuth $jwtauth)
    {
        $this->user    = $user;
        $this->jwtauth = $jwtauth;
    }


    //global function to return user data if user is loged in
    public function get_user_details(Request $request)
    {
        try {
            $user = JWTAuth::toUser($request->bearerToken());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'Token is Expired']);
            }else{
                return \Response::json([
                        'Unauthorized',
                    ], 422);
            }
        }

        return response()->json(['result' => $user]);
    }
}
