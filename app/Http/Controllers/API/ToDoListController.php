<?php

namespace App\Http\Controllers\API;

use App\Step;
use App\Todolist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use mysql_xdevapi\Exception;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Todolist::all());
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
