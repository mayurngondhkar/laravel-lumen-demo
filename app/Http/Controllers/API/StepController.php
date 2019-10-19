<?php

namespace App\Http\Controllers\API;

use App\Events\StepCreatedEvent;
use App\Step;
use App\Task;
use App\Todolist;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Auth;
use Illuminate\Validation\ValidationException;

class StepController extends Controller
{
    /**
     * StepController constructor.
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
    public function index($toDoListId)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $steps = Step::query()
                ->select('id', 'name', 'description', 'todolist_id', 'order_in_todolist')
                ->where('todolist_id', $toDoListId)->get();
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', 500);
        }

        foreach ($steps as $key => $value) {
            $steps[$key]->links = [[
                'ref' => 'step',
                'href' => "api/v1/todolists/$toDoListId/steps/$value->id",
                'action' => 'GET'
            ], [
                'ref' => 'toDoList',
                'href' => "api/v1/todolists/$toDoListId",
                'action' => 'GET'
            ]];
        }
        return response()->json($steps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $toDoListId
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request, $toDoListId)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        $this->validate($request, [
            'name' => 'required|min:5|max:50',
            'description' => 'required|min:5|max:255'
        ]);


        $lastStep = Step::query()->where('todolist_id', $toDoListId)->max('order_in_todolist');

        $step = new Step([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'order_in_todolist' => $lastStep + 1,
            'todolist_id' => $toDoListId,
            'user_id' => Auth::user()->id
        ]);

        try {
            $step->save();
        } catch (\Exception $e) {
            // Log error
            return response()->json('Something Went Wrong!', 500);
        }

        event(new StepCreatedEvent($step));
        unset($step['user_id']);

        unset($step['updated_at']);

        $step['links'] = [[
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$step->todolist_id",
            'action' => 'GET'
        ], [
            'rel' => 'step',
            'href' => "api/v1/todolist/$step->todolist_id/steps",
            'action' => 'GET'
        ]];

        return response()->json($step);
    }

    /**
     * Display the specified resource.
     *
     * @param $toDoListId
     * @param int $id
     * @return Response
     */
    public function show($toDoListId, $id)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try { $step = Step::query() ->select('id', 'name', 'description', 'todolist_id', 'order_in_todolist')
                ->where('id', '=', $id)->where('todolist_id', $toDoListId)
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        try {
            $stepTasks = Task::with('step')
                ->where('step_id', $id)
                ->select('id')
                ->getQuery()
                ->get();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        if(!$step) {
            return response()->json('Resource not found', 404);
        }

        $step['links'] = [
            [
                 'ref' => 'step',
                 'href' => "api/v1/todolists/$toDoListId/steps/$id",
                 'action' => 'PUT'
            ], [
                 'ref' => 'step',
                 'href' => "api/v1/todolists/$toDoListId/steps/$id",
                 'action' => 'DELETE'
            ], [
                'rel' => 'tasks',
                'href' => "api/v1/todolist/$toDoListId/steps/$id/tasks",
                'action' => 'GET'
            ], [
                'rel' => 'todolist',
                'href' => "api/v1/todolist/$step->todolist_id",
                'action' => 'GET'
        ]];

        return response()->json($step);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $toDoListId, $id)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $step = Step::find($id);
        } catch (\Exception $e) {
            return response()->json('Something went wrong', 500);
        }

        if(!$step) {
            return response()->json('Resource not found', 404);
        }

        if($step->todolist_id !== $toDoListId) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        $this->validate($request, [
            'name' => 'required|min:5|max:50',
            'description' => 'required|min:5|max:255'
        ]);

        $step->name = $request->input('name');
        $step->description = $request->input('description');
        $step->order_in_todolist = $request->input('order_in_todolist');

        try {
            $step->save();
        } catch (\Exception $e) {
            // Log exception
            return response()->json('To Do List item not saved', 500);
        }
        try {
            $savedStep = Step::query()
                ->select('id', 'name', 'description','todolist_id', 'order_in_todolist')
                ->where('id', $id)
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        $savedStep->msg = 'To Do Item Updated';
        $savedStep['links'] = [[
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$toDoListId",
            'action' => 'GET'
        ], [
            'rel' => 'step',
            'href' => "api/v1/todolist/$toDoListId/steps/$id",
            'action' => 'GET'
        ],[
            'rel' => 'step',
            'href' => "api/v1/todolist/$toDoListId/steps/$id",
            'action' => 'PUT'
        ], [
            'rel' => 'tasks',
            'href' => "api/v1/todolist/$toDoListId/steps/$id/tasks",
            'action' => 'GET'
        ]];

        return response()->json($savedStep);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($toDoListId, $id)
    {
        if(!(new ToDoListController)->toDoListBelongsToUser($toDoListId)) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        try {
            $step = Step::find($id);
        } catch (\Exception $e) {
            return response()->json('Something went wrong', 500);
        }

        if(!$step) {
            return response()->json('Resource not found', 404);
        }

        if($step->todolist_id !== $toDoListId) {
            return response()->json(['error' => 'Not Authorised'], 401);
        }

        // Delete all tasks that belong to the step
        if(!(new TaskController)->destroyTasksOfStep($id)) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }

        try {
            $step->delete();
        } catch (\Exception $e) {
            // Log exception
            return response()->json('To Do List item not deleted', 500);
        }

        $stepInfo = new \stdClass();
        $stepInfo->view_toDoListItem = [
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$step->todolist_id",
            'action' => 'GET'
        ];
        $stepInfo->msg = 'To Do List item deleted successfully';

        return response()->json($stepInfo);
    }

    function stepBelongsToToDoList($toDoListId, $stepId) {
        try {
            $step = Step::find($stepId);
        } catch (\Exception $e) {
            return response()->json('Something went wrong', 500);
        }

        if($step['todolist_id'] == $toDoListId) {
            return true;
        } else {
            return false;
        }
    }
}
