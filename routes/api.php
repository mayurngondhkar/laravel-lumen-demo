<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api/v1/todolists'], function () use ($router) {
    $router->get('',  ['uses' => 'API\ToDoListController@index']);
    $router->get('{id}',  ['uses' => 'API\ToDoListController@show']);
    $router->post('',  ['uses' => 'API\ToDoListController@store']);
    $router->put('{id}',  ['uses' => 'API\ToDoListController@update']);
    $router->delete('{id}',  ['uses' => 'API\ToDoListController@destroy']);
    $router->group(['prefix' => '{toDoListId}/steps'], function () use ($router) {
        $router->get('',  ['uses' => 'API\StepController@index']);
        $router->get('{id}',  ['uses' => 'API\StepController@show']);
        $router->put('{id}',  ['uses' => 'API\StepController@update']);
    });
});
