<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class recipe extends Model
{

 	 protected $fillable = [ 'id' ,'recipe_name' ,'recipe_ingredients' ,'category', 'preparation_time',
        						'recipe_description','user_id','is_favourite','is_deleted' 
        						];

     public function User()
    {
        return $this->belongsTo('App\User');
    }


// Recipe search Filters --
    public static function searchRecipe(){

		$request = request();
		$query = (new Recipe)->newQuery();

		$query->select()->where('is_deleted',0);

		// searrch for recipes by name 
		if ($request->has('recipe_name')) {
		    $query->where('recipe_name',"LIKE",'%'.$request->input('recipe_name').'%' );
		}
		
		// searrch for recipes by category veg or non-veg 
		if ($request->has('category')) {
		    $query->where('category',"LIKE",'%'.$request->input('category').'%' );
		}

		// searrch for recipes less than or equal to preparation time 
		if ($request->has('preparation_time')) {			
		    $query->where('preparation_time','<=',$request->input('preparation_time') );
		}

		// searrch for recipes by ingredients  
		if ($request->has('recipe_ingredients')) {
		    $query->where('recipe_ingredients',"LIKE",'%'.$request->input('recipe_ingredients').'%' );
		}

		// searrch for recipes by Description --
		if ($request->has('recipe_description')) {
		    $query->where('recipe_description',"LIKE",'%'.$request->input('recipe_description').'%' );
		}

		return $query->get();

    }




}
