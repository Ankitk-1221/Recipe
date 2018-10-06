<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;
use JWTAuth;
use App\Recipe;

class RecipeTransformer extends TransformerAbstract
{
       public function transform(Recipe $recipe)
    {	

        $user = \Auth::user();
        $status = \App\RecipeFavourites::getRecipeFavouriteStatus($recipe->id,$user->id);

        return [
            'recipe_name' => $recipe->recipe_name,
            'posted_by' =>   $user->name,
            'user_id' => $recipe->user_id,
            'recipe_ingredients' => $recipe->recipe_ingredients,
            'preparation_time' =>$recipe->preparation_time,
            'category' => ($recipe->category == 1) ? "Veg" :"Non'veg" ,
            'recipe_description' => $recipe->recipe_description, 
            'is_favourite' => $status,
            'added_on' => date('Y-m-d', strtotime($user->created_at))
        ];
    }
}