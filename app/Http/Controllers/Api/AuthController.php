<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use JWTAuthException;
use Tymon\JWTAuth\JWTAuth;
use App\ActivationService;
use App\ActivationRepository;

class AuthController extends Controller
{
    private $user;
    private $jwtauth;
    protected $activationService;
    protected $activationRepo;

    public function __construct(User $user, JWTAuth $jwtauth, ActivationService $activationService, ActivationRepository $activationRepo)
    {
        $this->user    = $user;
        $this->jwtauth = $jwtauth;
        $this->middleware('guest', ['except' => 'logout']);
        $this->activationService = $activationService;
        $this->activationRepo = $activationRepo;
    }

    public function register(Request $request)
    {

        $validator = Validator::make(Input::all(), [
            'fname'    => 'required|min:2|max:50',
            'lname'    => 'required|min:2|max:50',
            'email'    => 'required|email|unique:users',
            'password'  =>  'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return \Response::json([
                $validator->messages(),
            ], 422);
        }

        $newUser = $this->user->create([
            'email'    => $request->get('email'),
            'fname'    => $request->get('fname'),
            'lname'    => $request->get('lname'),
            'password' => bcrypt($request->get('password')),
        ]);

        $this->activationService->sendActivationMail($newUser);

        if (!$newUser) {
            return response()->json(['failed_to_create_new_user'], 500);
        }

        return response()->json([
            'message' => 'Activation email sent'
        ],200);
    }

    public function login(Request $request)
    {

        if(!$this->authenticated($request->get('email'))){


//            echo '<pre>';
//            print_r($request->get('email'));
//            echo '</pre>';
//            die();

            return response()->json([
                        'error' => 'User not activated'
                    ], 422);
        }


        $credentials = $request->only('email', 'password');

        $token = null;

        try {
            $token = $this->jwtauth->attempt($credentials);
            if (!$token) {
                return response()->json([
                        'error' => 'invalid_email_or_password'
                    ], 422);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }

        
        return response()->json(compact('token'));
    }

    public function activateUser($token)
    {

        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null) {
            return response()->json([
                    'message' => 'Link not valid'
                ], 422);
        }

        $user = User::find($activation->user_id);

        $user->activated = true;

        $user->save();

        $this->activationRepo->deleteActivation($token);

        //return $user;
        return response()->json([
                'message' => 'Account activated'
            ],200);
    }

    public function authenticated($email)
    {

        $user = User::where('email', $email)
                        ->where('activated', 1)
                        ->first();

        return is_null($user) ? false : true;
    }
}
