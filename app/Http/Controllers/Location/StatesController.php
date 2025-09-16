<?php

namespace App\Http\Controllers\Location;

use App\DataTables\StateDataTable;
use App\Models\SystemCountry;
use App\Models\SystemState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatesController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(StateDataTable $dataTable)
    {
        return $dataTable->render('admin.location.state.index');
    }

    public function create()
    {
        $countries = SystemCountry::all();
        return view('admin.location.state.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          =>  'required|unique:system_states,name',
            'country_id'    =>  'required',
        ]);

        $state              = new SystemState();
        $state->name        = $request->name;
        $state->country_id  = $request->country_id;
        $state->save();

        flash(__('custom.create_successful'))->success();

        return redirect()
            ->route('admin.states.index');
    }

    public function edit($id)
    {
        $state = SystemState::find($id);
        $countries = SystemCountry::all();

        return view('admin.location.state.edit', compact('state', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          =>  'required|unique:system_states,name,' . $id,
            'country_id'    =>  'required',
        ]);

        $state              = SystemState::find($id);
        $state->name        = $request->name;
        $state->country_id  = $request->country_id;
        $state->save();

        flash(__('custom.update_successful'))->success();

        return redirect()
            ->route('admin.states.index');
    }

    public function destroy($id)
    {
        $state = SystemState::find($id);
        $state->delete();

        flash(__('custom.delete_successful'))->success();

        return redirect()
            ->route('admin.states.index');
    }

    /**
     * getCountryWiseState
     *
     * @param  mixed $request
     * @return void
     */
    public function getCountryWiseState(Request $request)
    {
        $states = SystemState::where('country_id', $request->id)->get();

        return response()->json([
            'data'  =>  $states
        ]);
    }
}
