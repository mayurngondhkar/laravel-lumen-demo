<?php

namespace App\Http\Controllers\API;

use App\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StateController extends Controller
{
    /**
     * StateController constructor.
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
    public function index()
    {
        try {
            $states = State::query()->select('id', 'name')->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        foreach ($states as $key => $value) {
            $states[$key]->links = [
                'ref' => 'state',
                'href' => "api/v1/states/$value->id",
                'action' => 'GET'
            ];
        }

        return response()->json($states, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5|max:50',
        ]);

        $state = new State([
            'name' => $request->input('name'),
        ]);

        try {
            $state->save();
        } catch (\Exception $e) {
            // Log exception
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        unset($state['updated_at']);
        $state->msg = 'State created successfully';
        $state['links'] = ['rel' => 'state', 'href' => 'api/v1/states', 'action' => 'GET'];

        return response()->json($state, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $state = State::query()->select('id', 'name')
            ->where('id', '=', $id)
            ->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        if(!$state) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $state['links'] = ['rel' => 'state', 'href' => 'api/v1/states', 'action' => 'GET'];

        return response()->json($state, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        try {
            $state = State::find($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        if(!$state) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $this->validate($request, [
            'name' => 'required|min:5|max:50',
        ]);

        $state->name = $request->input('name');

        try {
            $state->save();
        } catch (\Exception $e) {
            // Log exception
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        try {
            $updatedState = State::query()
                ->select('id', 'name')->where('id', $id)->first();
        } catch (\Exception $e) {
            // Log this
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        unset($updatedState['created_at']);
        unset($updatedState['updated_at']);
        unset($updatedState['deleted_at']);

        $updatedState->msg = 'State Updated';
        $updatedState['links'] = ['rel' => 'state', 'href' => 'api/v1/states', 'action' => 'GET'];
        return response()->json($updatedState, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $state = State::find($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        if(!$state) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        try {
            $state->delete();
        } catch (\Exception $e) {
            // Log exception
            return response()->json(['error' => 'Something Went Wrong!'], 500);
        }

        $stateInfo = new \stdClass();
        $stateInfo->links = ['rel' => 'state', 'href' => 'api/v1/states', 'action' => 'GET'];
        $stateInfo->msg = 'State deleted successfully';

        return response()->json($stateInfo, 200);
    }
}
