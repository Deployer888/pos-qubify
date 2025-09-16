<?php

namespace App\Http\Controllers\Admin\MeasurementUnit;

use App\Http\Controllers\Controller;
use App\DataTables\MeasurementUnitDataTable;
use App\Http\Requests\MeasurementUnitRequest;
use App\Services\MeasurementUnit\MeasurementUnitService;

class MeasurementUnitsController extends Controller
{
    protected $measurementUnitService;

    /**
     * __construct
     *
     * @param  mixed $measurementUnitService
     * @return void
     */
    public function __construct(MeasurementUnitService $measurementUnitService)
    {
        $this->measurementUnitService = $measurementUnitService;

        $this->middleware(['permission:List Measurement Unit'])->only(['index']);
        $this->middleware(['permission:Add Measurement Unit'])->only(['create']);
        $this->middleware(['permission:Edit Measurement Unit'])->only(['edit']);
        $this->middleware(['permission:Delete Measurement Unit'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MeasurementUnitDataTable $dataTable)
    {
        set_page_meta(__('custom.measurement_unit'));
        return $dataTable->render('admin.measurement_units.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        set_page_meta(__('custom.add_measurement_unit'));
        return view('admin.measurement_units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeasurementUnitRequest $request)
    {
        $data = $request->validated();

        if ($this->measurementUnitService->createOrUpdate($data)) {
            flash(__('custom.measurement_unit_created_successfully'))->success();
        } else {
            flash(__('custom.measurement_unit_create_failed'))->error();
        }

        return redirect()->route('admin.measurement-units.index');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $measurement_unit = $this->measurementUnitService->get($id);

        set_page_meta(__('custom.edit_measurement_unit'));
        return view('admin.measurement_units.edit', compact('measurement_unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MeasurementUnitRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->measurementUnitService->createOrUpdate($data, $id)) {
            flash(__('custom.measurement_unit_updated_successfully'))->success();
        } else {
            flash(__('custom.measurement_unit_update_failed'))->error();
        }

        return redirect()->route('admin.measurement-units.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->measurementUnitService->delete($id)) {
            flash(__('custom.measurement_unit_deleted_successfully'))->success();
        } else {
            flash(__('custom.measurement_unit_delete_failed'))->error();
        }

        return redirect()->route('admin.measurement-units.index');
    }
}
