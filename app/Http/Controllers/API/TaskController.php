<?php

namespace App\Http\Controllers\API;

use App\Events\TaskCreatedEvent;
use App\Step;
use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($toDoListId, $stepId)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        if(!(new StepController)->stepBelongsToToDoList($toDoListId, $stepId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $tasks = Task::query()
                ->select('id', 'name', 'description', 'state_id', 'step_id', 'order_in_steplist')
                ->where('step_id', $stepId)
                ->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        foreach ($tasks as $key => $value) {
            $tasks[$key]->links = [[
                'ref' => 'task',
                'href' => "/api/v1/todolists/$toDoListId/steps/$stepId/tasks/$value->id",
                'action' => 'GET'
            ], [
                'rel' => 'step',
                'href' => "/api/v1/todolist/$toDoListId/steps/$stepId",
                'action' => 'GET'
            ]];
        }

        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $toDoListId
     * @param $stepId
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request, $toDoListId, $stepId)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        if(!(new StepController)->stepBelongsToToDoList($toDoListId, $stepId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        $this->validate($request, [
            'name' => 'required|min:5|max:50',
            'description' => 'required|min:5|max:255'
        ]);

        try {
            $lastTask = Task::query()->where('step_id', $stepId)->max('order_in_steplist');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        $task = new Task([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'state_id' => $request->input('state_id'),
            'order_in_steplist' => $lastTask + 1,
            'step_id' => $stepId,
            'user_id' => Auth::user()->id
        ]);

        $task->save();

        try {
            $task->save();
        } catch (\Exception $e) {
            // Log error
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        event(new TaskCreatedEvent($task));

        unset($task['updated_at']);

        $task->msg = 'Task Created';
        $task['links'] = [[
            'rel' => 'step',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId",
            'action' => 'GET'
        ], [
            'rel' => 'tasks',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId/tasks",
            'action' => 'GET'
        ]];

        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $toDoListId
     * @param $stepId
     * @param $taskId
     * @return Response
     */
    public function show($toDoListId, $stepId, $taskId)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        if(!(new StepController)->stepBelongsToToDoList($toDoListId, $stepId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $task = Task::query()
                ->select('id', 'name', 'description', 'state_id', 'step_id', 'order_in_steplist')
                ->where('id', $taskId)
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        if(!$task) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $task['links'] = [[
            'rel' => 'task',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId/tasks/$taskId",
            'action' => 'PUT'
        ],[
            'rel' => 'task',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId/tasks/$taskId",
            'action' => 'DELETE'
        ],[
            'rel' => 'tasks',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId/tasks",
            'action' => 'GET'
        ] ,[
            'rel' => 'step',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId",
            'action' => 'GET'
        ]];
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $toDoListId
     * @param $stepId
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $toDoListId, $stepId, $id)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        if(!(new StepController)->stepBelongsToToDoList($toDoListId, $stepId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $task = Task::find($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        if(!$task) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $this->validate($request, [
            'name' => 'required|min:5|max:50',
            'description' => 'required|min:5|max:255'
        ]);

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->state_id = $request->input('state_id');
        $task->order_in_steplist = $request->input('order_in_steplist');

        try {
            $blah = $task->save();
        } catch (\Exception $e) {
            // Log exception
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }
        try {
            $savedTask = Task::query()
                ->select('id', 'name', 'description','state_id', 'step_id', 'order_in_steplist')
                ->where('id', $id)
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        $savedTask->msg = 'Task Updated';
        $savedTask['links'] = [[
            'rel' => 'step',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId",
            'action' => 'GET'
        ], [
            'rel' => 'step',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId",
            'action' => 'PUT'
        ],[
            'rel' => 'tasks',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId/tasks",
            'action' => 'GET'
        ]];

        return response()->json($savedTask);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($toDoListId, $stepId, $id)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        if(!(new StepController)->stepBelongsToToDoList($toDoListId, $stepId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $task = Task::find($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        if(!$task) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        try {
            $task->delete();
        } catch (\Exception $e) {
            // Log exception
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        $taskInfo = new \stdClass();
        $taskInfo->links = [
            'rel' => 'step',
            'href' => "/api/v1/todolist/$toDoListId/steps/$stepId",
            'action' => 'GET'
        ];
        $taskInfo->msg = 'Task deleted successfully';

        return response()->json($taskInfo);
    }
}
