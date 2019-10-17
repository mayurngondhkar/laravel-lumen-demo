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
    $router->get('{toDoListId}',  ['uses' => 'API\ToDoListController@show']);
    $router->post('',  ['uses' => 'API\ToDoListController@store']);
    $router->put('{toDoListId}',  ['uses' => 'API\ToDoListController@update']);
    $router->delete('{toDoListId}',  ['uses' => 'API\ToDoListController@destroy']);
    $router->group(['prefix' => '{toDoListId}/steps'], function () use ($router) {
        $router->get('',  ['uses' => 'API\StepController@index']);
        $router->get('{stepId}',  ['uses' => 'API\StepController@show']);
        $router->post('',  ['uses' => 'API\StepController@store']);
        $router->put('{stepId}',  ['uses' => 'API\StepController@update']);
        $router->delete('{stepId}',  ['uses' => 'API\StepController@destroy']);
        $router->group(['prefix' => '{stepId}/tasks'], function () use ($router) {
            $router->get('',  ['uses' => 'API\TaskController@index']);
            $router->get('{taskId}',  ['uses' => 'API\TaskController@show']);
            $router->post('',  ['uses' => 'API\TaskController@store']);
            $router->put('{taskId}',  ['uses' => 'API\TaskController@update']);
            $router->delete('{taskId}',  ['uses' => 'API\TaskController@destroy']);
        });
    });
});

$router->group(['prefix' => 'api/v1/states'], function () use ($router) {
    $router->get('',  ['uses' => 'API\StateController@index']);
    $router->get('{stateId}',  ['uses' => 'API\StateController@show']);
    $router->post('',  ['uses' => 'API\StateController@store']);
    $router->put('{stateId}',  ['uses' => 'API\StateController@update']);
    $router->delete('{stateId}',  ['uses' => 'API\StateController@destroy']);
});
