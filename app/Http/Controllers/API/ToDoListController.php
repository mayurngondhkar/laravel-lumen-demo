<?php

namespace App\Http\Controllers\API;

use App\Step;
use App\Todolist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\Types\Object_;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $toDoLists = Todolist::query()->select('id', 'name', 'description', 'order')->get();
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', 500);
        }

        foreach ($toDoLists as $key => $value) {
            $toDoLists[$key]->view_toDoList = [
                'ref' => 'toDoList',
                'href' => "api/v1/todolists/$value->id",
                'action' => 'GET'
            ];
        }
        return response()->json($toDoLists, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lastOrderedTodoList = Todolist::max('order');

        $toDoList = new Todolist([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'order' => $lastOrderedTodoList + 1
        ]);

        $toDoList->save();
        return response()->json($toDoList);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $toDoListItem = Todolist::query()
                ->select('id', 'name', 'description', 'order')
                ->where('id', '=', $id)
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }


        try {
            $listSteps = Step::with('todolist')
                ->where('todolist_id', $id)
                ->select('id')
                ->getQuery()
                ->get();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        if(!$toDoListItem) {
            return response()->json('Resource not found', 404);
        }

        $toDoListItem->view_toDoList = ['rel' => 'todolist', 'href' => 'api/v1/todolist', 'action' => 'GET'];

        $stepsInfo = [];
        foreach ($listSteps as $listStep) {
            array_push($stepsInfo, [
                'ref' => 'step',
                'href' => "api/v1/todolists/$listStep->id",
                'action' => 'GET'
            ] );
        }

        $toDoListItem->view_steps = $stepsInfo;

        if ($toDoListItem) {
            return response()->json($toDoListItem, 200);
        } else {
            return ;
        }
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
        try {
            $toDoList = Todolist::find($id);
        } catch (\Exception $e) {
            return response()->json('Resource not found', 404);
        }

        $toDoList->name = $request->input('name');
        $toDoList->description = $request->input('description');
        $toDoList->order = $request->input('order');

        try {
            $toDoList->save();
        } catch (\Exception $e) {
            // Log exception
            return response()->json('To Do List item not saved', 500);
        }

        try {
            $toDoListItem = Todolist::query()
                ->select('id', 'name', 'description', 'order')
                ->where('id', '=', $request->input('id'))
                ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json('Something Went Wrong!', 500);
        }

        unset($toDoList['created_at']);
        unset($toDoList['updated_at']);
        unset($toDoList['deleted_at']);

        $toDoList->msg = 'To Do Item Updated';
        $toDoList->view_toDoList = ['rel' => 'todolist', 'href' => 'api/v1/todolist', 'action' => 'GET'];
        return response()->json($toDoList, 200);
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
            $toDoList = Todolist::find($id);
        } catch (\Exception $e) {
            return response()->json('Resource not found', 404);
        }

        try {
            $toDoList->delete();
        } catch (\Exception $e) {
            // Log exception
            return response()->json('To Do List item not deleted', 500);
        }

        $toDoListInfo = new Object_();
        $toDoListInfo->view_toDoList = ['rel' => 'todolist', 'href' => 'api/v1/todolist', 'action' => 'GET'];
        $toDoListInfo->msg = 'To Do List item deleted successfully';

        return response()->json($toDoListInfo, 200);
    }
}
