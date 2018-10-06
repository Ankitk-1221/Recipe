<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Http\Requests;
use JWTAuthException;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class LoginController extends Controller
{

    public function __construct()
    {
        $this->user = new User;
    }

// User login --
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $payload = app('request')->only('email', 'password','confirm_password');
        $validator = app('validator')->make($payload, $rules);

        if($validator->fails()) {
            return response()->json(['error'=> $validator->messages()], 401);
        }
        
       // $credentials['is_active'] = 1; 
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([ 'error' => 'We cannot find an account with this credentials. .'], 404);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([ 'error' => 'Failed to login, please try again.'], 500);
        }

        $user = \Auth::user()->select('id as user_id','name','email','is_active')->first();

        // user devactivated by Admin or not --
        if( $user->is_active == 0)
        return response()->json([ 'message' => 'You are not allowed to login, please contact the Admin.'], 200);
    
        return response()->json([ 'data'=> [ 'user'=>$user , 'token' => $token ]], 200);
    }


// User logout --
    public function logout(Request $request) {

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            //JWTAuth::invalidate($token);
            return response()->json([ 'message'=> "You have successfully logged out."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([ 'error' => 'Failed to logout, please try again.'], 500);
        }
    }
 

}
