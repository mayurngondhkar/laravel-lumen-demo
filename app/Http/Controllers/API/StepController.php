<?php

namespace App\Http\Controllers\API;

use App\Step;
use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($toDoListId)
    {
        try {
            $steps = Step::query()
                ->select('id', 'name', 'description', 'todolist_id', 'order_in_todolist')
                ->where('todolist_id', $toDoListId)->get();
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', 500);
        }

        foreach ($steps as $key => $value) {
            $steps[$key]->view_step = [
                'ref' => 'step',
                'href' => "api/v1/todolists/$toDoListId/steps/$value->id",
                'action' => 'GET'
            ];
            $steps[$key]->view_toDoList = [
                'ref' => 'toDoList',
                'href' => "api/v1/todolists/$toDoListId",
                'action' => 'GET'
            ];
        }
        return response()->json($steps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $toDoListId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $toDoListId)
    {
        $lastStep = Step::query()->where('todolist_id', $toDoListId)->max('order_in_todolist');

        $step = new Step([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'order_in_todolist' => $lastStep + 1,
            'todolist_id' => $toDoListId
        ]);

        try {
            $step->save();
        } catch (\Exception $e) {
            // Log error
            return response()->json('Something Went Wrong!', 500);
        }

        unset($step['updated_at']);

        $step->view_toDoList = ['rel' => 'todolists', 'href' => 'api/v1/todolist', 'action' => 'GET'];
        $step->view_toDoListItem = [
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$step->todolist_id",
            'action' => 'GET'
        ];
        $step->view_toDoListItemSteps = [
            'rel' => 'step',
            'href' => "api/v1/todolist/$step->todolist_id/steps",
            'action' => 'GET'
        ];

        return response()->json($step);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try { $step = Step::query() ->select('id', 'name', 'description', 'todolist_id', 'order_in_todolist')
                ->where('id', '=', $id)
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

        $step->view_toDoList = ['rel' => 'todolists', 'href' => 'api/v1/todolist', 'action' => 'GET'];
        $step->view_toDoListItem = [
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$step->todolist_id",
            'action' => 'GET'
        ];
        $step->view_toDoListItemSteps = [
            'rel' => 'step',
            'href' => "api/v1/todolist/$step->todolist_id/steps",
            'action' => 'GET'
        ];

        return response()->json($step);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $step = Step::find($request->input('id'));
        } catch (\Exception $e) {
            return response()->json('Resource not found', 404);
        }

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
                ->where('id', '=', $request->input('id'))
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        $savedStep->msg = 'To Do Item Updated';
        $savedStep->view_toDoList = ['rel' => 'todolists', 'href' => 'api/v1/todolist', 'action' => 'GET'];
        $savedStep->view_toDoListItem = [
            'rel' => 'todolist',
            'href' => "api/v1/todolist/$savedStep->todolist_id",
            'action' => 'GET'
        ];
        $savedStep->view_toDoListItemSteps = [
            'rel' => 'step',
            'href' => "api/v1/todolist/$savedStep->todolist_id/steps",
            'action' => 'GET'
        ];

        return response()->json($savedStep);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $step = Step::find($id);
        } catch (\Exception $e) {
            return response()->json('Resource not found', 404);
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
}
