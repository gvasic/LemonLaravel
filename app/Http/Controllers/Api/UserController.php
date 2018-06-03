<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use JWTAuthException;
use JWTAuth;
use Image;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    
    //function to update user data if user is loged in
    public function update_avatar(Request $request)
    {
        try {
            $user = JWTAuth::toUser($request->bearerToken());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error'=>'Token is Invalid']);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error'=>'Token is Expired']);
            } else {
                return \Response::json([
                        'Unauthorized',
                    ], 422);
            }
        }
        
        if ($request->hasFile('avatar')) {
            $validator = Validator::make(Input::all(), [
                'avatar' => [
                    'required',
                    Rule::dimensions()->maxWidth(1000)->maxHeight(500),
                ],
            ]);
    
            if ($validator->fails()) {
                return \Response::json([
                    $validator->messages(),
                ], 422);
            }
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
           
            Image::make($avatar)->resize(300, 300)->save('C:\xampp\htdocs\laravel\lemonade-stand-online\LemonStand\public\images\avatars\\' . $filename);
            //Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) ); //take care of public_path and uncomment // Set avatar size
            
            $user->avatar = $filename;
            $user->save();
        }
        return response()->json(['result' => $user]); // Return to My Profile Page
    }
}
