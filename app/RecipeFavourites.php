<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeFavourites extends Model
{
     protected $fillable = [ 'recipe_id','user_id','is_deleted' ];
     
     
     public static function getRecipeFavouriteStatus($recipe_id,$user_id){

     	//return  \DB::raw( '(select exists(select id from recipe_favourites WHERE  user_id = '.$user_id.' and recipe_id = '.$recipe_id.' and is_deleted=0 ) ) ' );
        return  \App\RecipeFavourites::where('recipe_id',$recipe_id)->where('user_id',$user_id)->where('is_deleted', 0)->count();

     }
}
