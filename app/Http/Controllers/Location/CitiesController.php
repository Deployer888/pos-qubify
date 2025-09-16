<?php

namespace App\Http\Controllers\Location;

use App\DataTables\CityDataTable;
use App\Models\SystemCity;
use App\Models\SystemState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CitiesController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth');
    }

    public function index(CityDataTable $dataTable)
    {
        return $dataTable->render('admin.location.city.index');
    }

    public function create()
    {
        $states = SystemState::all();
        return view('admin.location.city.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          =>  'required|unique:system_cities,name',
            'state_id'      =>  'required',
        ]);

        $city               = new SystemCity();
        $city->name         = $request->name;
        $city->state_id     = $request->state_id;
        $city->save();

        flash(__('custom.create_successful'))->success();

        return redirect()
            ->route('admin.cities.index');
    }

    public function edit($id)
    {
        $city   = SystemCity::find($id);
        $states = SystemState::all();

        return view('admin.location.city.edit', compact('city', 'states'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          =>  'required|unique:system_cities,name,' . $id,
            'state_id'      =>  'required',
        ]);

        $city               = SystemCity::find($id);
        $city->name         = $request->name;
        $city->state_id     = $request->state_id;
        $city->save();

        flash(__('custom.update_successful'))->success();

        return redirect()
            ->route('admin.cities.index');
    }

    public function destroy($id)
    {
        $city = SystemCity::find($id);
        $city->delete();

        flash(__('custom.delete_successful'))->success();

        return redirect()
            ->route('admin.cities.index');
    }

    /**
     * getStateWiseCity
     *
     * @param  mixed $request
     * @return void
     */
    public function getStateWiseCity(Request $request)
    {
        $cities = SystemCity::where('state_id', $request->id)->get();

        return response()->json([
            'data'  =>  $cities
        ]);
    }
}
