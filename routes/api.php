<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1' , function ($api) {
     // user routes --
        $api->post('register', 'App\Http\Controllers\Auth\RegisterController@register');
        $api->post('login', 'App\Http\Controllers\Auth\LoginController@login');
        
    $api->group(['middleware' => 'Jwt.verify'], function ($api) {
        
        $api->post('logout', 'App\Http\Controllers\Auth\LoginController@logout');
       
      // Recipes routes -- 
        $api->post('add-recipe', 'App\Http\Controllers\RecipeController@addRecipe');
        $api->get('get-recipe/{id}', 'App\Http\Controllers\RecipeController@getRecipe');
        $api->get('get-all-recipes', 'App\Http\Controllers\RecipeController@getAllRecipe');
        $api->get('my-recipes', 'App\Http\Controllers\RecipeController@myRecipes');
        $api->put('delete-recipe/{id}', 'App\Http\Controllers\RecipeController@deleteRecipe');
        $api->put('manage-favourite/{id}', 'App\Http\Controllers\RecipeController@manageFavourite');
        $api->post('search-recipe', 'App\Http\Controllers\RecipeController@searchRecipe');
     
        $api->get('my-profile', 'App\Http\Controllers\UserController@myProfile');

   });
    
    // Admin routes --
    $api->group(['prefix' => 'admin'], function ($api) {
        $api->get('get-user/{id}', 'App\Http\Controllers\AdminController@getUser');
        $api->get('get-all-users', 'App\Http\Controllers\AdminController@getAllUsers');
        $api->put('manage-user/{id}', 'App\Http\Controllers\AdminController@manageUser');
   });

});


