<?php

namespace App\Http\Controllers\Location;

use App\DataTables\CountryDataTable;
use App\Models\SystemCity;
use App\Models\SystemCountry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }

    public function index(CountryDataTable $dataTable)
    {
        return $dataTable->render('admin.location.country.index');
    }

    public function create()
    {
        return view('admin.location.country.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          =>  'required|unique:system_countries,name',
            'shortname'     =>  'required|unique:system_countries,shortname',
            'phonecode'     =>  'required|unique:system_countries,phonecode',
        ]);

        $country            = new SystemCountry();
        $country->name      = $request->name;
        $country->shortname = $request->shortname;
        $country->phonecode = $request->phonecode;
        $country->save();

        flash(__('custom.create_successful'))->success();

        return redirect()
            ->route('admin.countries.index');
    }

    public function edit($id)
    {
        $country = SystemCountry::find($id);

        return view('admin.location.country.edit', compact('country'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          =>  'required|unique:system_countries,name,' . $id,
            'shortname'     =>  'required|unique:system_countries,shortname,' . $id,
            'phonecode'     =>  'required|unique:system_countries,phonecode,' . $id,
        ]);

        $country            = SystemCountry::find($id);
        $country->name      = $request->name;
        $country->shortname = $request->shortname;
        $country->phonecode = $request->phonecode;
        $country->save();

        flash(__('custom.update_successful'))->success();

        return redirect()
            ->route('admin.countries.index');
    }

    public function destroy($id)
    {
        if (SystemCountry::find($id)->delete()) {
            flash(__('custom.country_deleted_failed'))->success();
        } else {
            flash(__('custom.country_deleted_failed'))->error();
        }

        return redirect()->route('admin.countries.index');
    }


    /**
     * getStateWiseCity
     *
     * @param  mixed $request
     * @return void
     */
    public function getCountries(Request $request)
    {
        $cities = SystemCountry::all();

        return response()->json([
            'data'  =>  $cities
        ]);
    }
}
