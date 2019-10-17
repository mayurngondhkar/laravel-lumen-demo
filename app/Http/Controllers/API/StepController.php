<?php

namespace App\Http\Controllers\API;

use App\Step;
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
        //
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
