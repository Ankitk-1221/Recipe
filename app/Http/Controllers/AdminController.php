<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Transformers\UserTransformer;


class AdminController extends Controller
{
    //
    use Helpers;
    
// Get User details --
    public function getUser($id){

      if(intval($id) > 0 ){

        $user = \App\User::find($id);

        if(empty($user))
        return $this->response->array(['message'=>"No data" ], 200);
        
        return $this->response->item($user, new UserTransformer)->setStatusCode(200);

      }else
        return $this->response->array(['message'=>"invalid Input" ], 422);
  

    }

//  Get All users lists and details --
    public function getAllUsers(){

        $users = \App\User::all();

        if(empty($users))
        return $this->response->array(['message'=>"No data" ], 200);        
    	
        return $this->response->Collection($users, new UserTransformer)->setStatusCode(200);

    }

//  Activate and Deactivate user -- 
    public function manageUser($id){

        if(intval($id) > 0 ){

    	    $user = \App\User::find($id);

	    	$message = "User Deactivated.";
	        $new_status = 0;
	        if($user->is_active == 0)
	        { 
	        	$message = "User Activated.";
	        	$new_status = 1;
	        } 

	        $user->is_active = $new_status;
	        if($user->save())
	        	return $this->response->array([ 'message' => $message ] , 200 );
        	else	
        		return $this->response->array([ 'message' => 'something went wrong, please try again.' ] , 500 );

       }else
           return $this->response->array(['message'=>"invalid Input" ], 422);

	}

}
