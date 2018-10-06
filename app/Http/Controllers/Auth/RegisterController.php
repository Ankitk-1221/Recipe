<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;


class RegisterController extends Controller
{
    use Helpers;

// User Signup --
    public function register(Request $request){

        $validator = $this->validator($request->all());
        if($validator->fails()){
            throw new StoreResourceFailedException("Validation Error", $validator->errors());
        }

        $user = $this->create($request->all());

        if($user){

            $token = JWTAuth::fromUser($user);

            return $this->response->array([
                "token" => $token,
                "message" => "User created",
                "status_code" => 200
            ]);
        }else{
            return $this->response->error("User Not Found.", 404);
        }
    }


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
      }
 
    

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'same:password'
        ]);
    }

}