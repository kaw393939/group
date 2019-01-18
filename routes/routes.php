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
});