<?php
/**
 * Created by PhpStorm.
 * User: Monimus
 * Date: 1/12/2019
 * Time: 3:57 PM
 */

use Illuminate\Support\Facades\Route;

Route::group(array('middleware' => ['bindings','api'], 'prefix' => 'api', 'namespace' => '\Kaw393939\Group\Http\Controllers'), function()
{
    Route::get('groups/mygroups','GroupsController@mygroups')->name('groups.mygroups');
    Route::apiResource('groups','GroupsController');
    Route::apiResource('groups/{group}/users','GroupUsersController');

    // Route::apiResource('groups/{group}/members','GroupUsersController');

   // Route::apiResource('groups/{group}/manage/users', 'GroupOwnerController');

    /*
    Route::post('groups/{group}/users/subscribe','subscribe@GroupUsersController');
    Route::post('groups/{group}/users/unsubscribe','unsubscribe@GroupUsersController');
    Route::post('groups/{group}/users/{user}/promote','promote@GroupUsersController');
    Route::post('groups/{group}/users/{user}/demote','demote@GroupUsersController');
    */

});

/*
Route::group(array('middleware' => ['bindings','api'], 'prefix' => 'api', 'namespace' => '\Kaw393939\Group\Http\Controllers'), function()
{
});
*/