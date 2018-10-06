<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{

// Current User profile --
    public static function myProfile(){
        
         $user_id = \Auth::user()->id;
        
         return  $user = \App\User::with( ['Recipe' => function($query) use ($user_id) {  

   	     $query->select('id as recipe_id',
               	 				 'recipe_name',
               	 				 'preparation_time',
               	 				 'recipe_ingredients',
               	 				 'category',
               	 				 'recipe_description',
                			   'user_id' );

                        }])->where('id',$user_id)->get();	
    	 
    
        }

}
