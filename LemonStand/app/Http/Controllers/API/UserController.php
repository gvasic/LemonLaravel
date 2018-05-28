<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    /**
         * login api
         *
         * @return \Illuminate\Http\Response
         */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        } else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
         * Register api
         *
         * @return \Illuminate\Http\Response
         */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_fname' => 'required',
            'user_lname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'avatar_path',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['user_lname'] =  $user->user_lname;
        $success['user_fname'] =  $user->user_fname;
        $success['avatar_path'] =  $user->avatar_path;
        return response()->json(['success'=>$success], $this-> successStatus);
    }
    /**
         * details api
         *
         * @return \Illuminate\Http\Response
         */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }

    /**
         * reset password api
         *
         * @return \Illuminate\Http\Response
         */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required',
                'c_new_password' => 'required|same:new_password',
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
            
        $user = Auth::user();
        $user->password = bcrypt($input['new_password']);
        $user->save();
        return response()->json(['success'=>$user], $this-> successStatus);
    }
}
