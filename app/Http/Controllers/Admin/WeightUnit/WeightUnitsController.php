<?php

namespace App\Http\Controllers\Admin\WeightUnit;

use App\Http\Controllers\Controller;
use App\DataTables\WeightUnitDataTable;
use App\Http\Requests\WeightUnitRequest;
use App\Services\WeightUnit\WeightUnitService;

class WeightUnitsController extends Controller
{
    protected $weightUnitService;

    /**
     * __construct
     *
     * @param  mixed $weightUnitService
     * @return void
     */
    public function __construct(WeightUnitService $weightUnitService)
    {
        $this->weightUnitService = $weightUnitService;

        $this->middleware(['permission:List Weight Unit'])->only(['index']);
        $this->middleware(['permission:Add Weight Unit'])->only(['create']);
        $this->middleware(['permission:Edit Weight Unit'])->only(['edit']);
        $this->middleware(['permission:Delete Weight Unit'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WeightUnitDataTable $dataTable)
    {
        set_page_meta(__('custom.weight_unit'));
        return $dataTable->render('admin.weight_units.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        set_page_meta(__('custom.add_weight_unit'));
        return view('admin.weight_units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WeightUnitRequest $request)
    {
        $data = $request->validated();

        if ($this->weightUnitService->createOrUpdate($data)) {
            flash(__('custom.weight_unit_created_successfully'))->success();
        } else {
            flash(__('custom.weight_unit_create_failed'))->error();
        }

        return redirect()->route('admin.weight-units.index');
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
        $weight_unit = $this->weightUnitService->get($id);

        set_page_meta(__('custom.edit_weight_unit'));
        return view('admin.weight_units.edit', compact('weight_unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WeightUnitRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->weightUnitService->createOrUpdate($data, $id)) {
            flash(__('custom.weight_Unit_updated_successfully'))->success();
        } else {
            flash(__('custom.weight_Unit_update_failed'))->error();
        }

        return redirect()->route('admin.weight-units.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->weightUnitService->delete($id)) {
            flash(__('custom.weight_unit_deleted_successfully'))->success();
        } else {
            flash(__('custom.weight_unit_delete_failed'))->error();
        }

        return redirect()->route('admin.weight-units.index');
    }
}
