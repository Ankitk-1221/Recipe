<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use JWTAuth;
use \Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use App\Transformers\RecipeTransformer;

class RecipeController extends Controller
{    
    use Helpers;
      
// Add recipe --    
    public function addRecipe(Request $request){

    	$rules = [ 'recipe_name'=> 'required|max:100' ,
    			   'recipe_ingredients' => 'required' ,
    			   'category' => 'required|boolean' ,
    			   'preparation_time' => 'required|date_format:h:i',
    			   'recipe_description' => 'required' 
    			   ];

        $payload = app('request')->only('recipe_name', 'recipe_ingredients','category','preparation_time','recipe_description');
        $validator = app('validator')->make($payload, $rules);

        if($validator->fails()) {
            return response()->json(['error'=> $validator->messages()], 401);
        }

        $payload['user_id'] = \Auth::user()->id;
        $recipe = \App\Recipe::create( $payload );
        
        if( $recipe->save() )
        	return $this->response->array([ 'message' =>'Recipe has been added ' ] , 200 );
        else	
        	return $this->response->array([ 'message' => 'Something went wrong, please try again.' ] , 500 );

    }

// Get Recipe details --
    public function getRecipe($id){

        if(intval($id) > 0 ){

            $recipe = \App\Recipe::where('is_deleted',0)->find($id);
            
            if(empty($recipe))
            return $this->response->array(['message'=>"No data" ], 200);    

            return $this->response->item($recipe, new RecipeTransformer)->setStatusCode(200);

        }
        else
            return $this->response->array(['message'=>"Invalid Input" ], 422);

    }


// Get Recipies and details --
    public function getAllRecipe(){

            $recipe = \App\Recipe::all()->where('is_deleted',0);

            if(empty($recipe))
            return $this->response->array(['message'=>"No data" ], 200);

            return $this->response->Collection($recipe, new RecipeTransformer)->setStatusCode(200);
    
    }


// Get Current user's details --
    public function myRecipes(){

            $user_id = \Auth::user()->id;
            $recipe = \App\Recipe::where('user_id',$user_id)->where('is_deleted',0)->get();

            if(empty($recipe))
            return $this->response->array(['message'=>"No data" ], 200);

            return $this->response->Collection($recipe, new RecipeTransformer)->setStatusCode(200);

    }


// Delete Recipe --
    public function deleteRecipe($recipe_id){

         if(intval($recipe_id) > 0 ){

            $user_id = \Auth::user()->id;
            $recipe = \App\Recipe::where('is_deleted',0)->find($recipe_id);
            if($recipe){
                if($recipe->user_id == $user_id ){

                        $recipe->is_deleted = 1;
                        if( $recipe->save() )    
                        return $this->response->array([ 'message'=>"You have deleted the recipe" ], 200);
                        else
                        return $this->response->array([ 'message'=>"Something went wrong" ],500);    
                }else
                    return $this->response->array([ 'message' =>"You cannot delete this recipe "] , 401 );
            }else
                 return $this->response->array([ 'message' =>"Recipe does not exist"] , 401 );     

        }else
            return $this->response->array(['message'=>"invalid Input" ], 422);

    }


// Mark Recipes Favourite or unfavourite --
    public function manageFavourite($recipe_id){

     if(intval($recipe_id) > 0 ){    
            
            $user_id = \Auth::user()->id;           
            $recipe = \App\RecipeFavourites::where('recipe_id',$recipe_id)->where('user_id',$user_id)->first();
            
            $message = "You have marked Recipe as favourite";
            if(!$recipe){

                $recipe_fav_create = \App\RecipeFavourites::create([ 'recipe_id' =>$recipe_id ,'user_id' => $user_id ] );
            
            }else{
                
                $new_status = 0;
                if($recipe->is_deleted == 0)
                {  
                   $new_status = 1;
                   $message = "You have Unmarked Recipe as favourite";
                }

                $recipe->is_deleted = $new_status; 

                if(!$recipe->save() )    
                return $this->response->array([ 'message'=>"something went Wrong" ],500);
            }

            return $this->response->array([ 'message'=>$message ], 200);

     }else
        return $this->response->array(['message'=> "Invalid Input" ], 422);

    
 }


// Search Recipes --
    public function searchRecipe(){

       $recipes = Recipe::searchRecipe();

       return $this->response->Collection($recipes, new RecipeTransformer)->setStatusCode(200);
        
    }

}
