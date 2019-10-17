<?php

namespace App\Http\Controllers\API;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($toDoListId, $stepId)
    {
        try {
            $tasks = Task::query()
                ->select('id', 'name', 'description', 'state_id', 'step_id', 'order_in_steplist')
                ->where('step_id', $stepId)
                ->get();
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', 500);
        }

        foreach ($tasks as $key => $value) {
            $tasks[$key]->view_task = [
                'ref' => 'task',
                'href' => "api/v1/todolists/$toDoListId/steps/$stepId/tasks/$value->id",
                'action' => 'GET'
            ];
        }
        $tasks['view_toDoListItem'] = [
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$toDoListId",
            'action' => 'GET'
        ];
        $tasks['view_step'] = [
            'rel' => 'step',
            'href' => "api/v1/todolist/$toDoListId/steps/$stepId",
            'action' => 'GET'
        ];

        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $stepId, $taskId)
    {
        try {
            $task = Task::query()
                ->select('id', 'name', 'description', 'state_id', 'step_id', 'order_in_steplist')
                ->where('id', $taskId)
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        if(!$task) {
            return response()->json('Resource not found', 404);
        }

        $task->view_toDoList = ['rel' => 'todolist', 'href' => 'api/v1/todolist', 'action' => 'GET'];
        $task['view_toDoListItem'] = [
            'rel' => 'todolistItem',
            'href' => "api/v1/todolist/$id",
            'action' => 'GET'
        ];
        $task['view_step'] = [
            'rel' => 'step',
            'href' => "api/v1/todolist/$id/steps/$stepId",
            'action' => 'GET'
        ];
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
