<?php

namespace App\Http\Controllers\Admin\Supplier;

use App\Models\Supplier;
use App\Models\SystemCountry;
use App\Http\Controllers\Controller;
use App\DataTables\SupplierDataTable;
use App\Http\Requests\SupplierRequest;
use App\Services\Supplier\SupplierService;

class SuppliersController extends Controller
{
    protected $supplierService;

    /**
     * __construct
     *
     * @param  mixed $supplierService
     * @return void
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;

        $this->middleware(['permission:List Supplier'])->only(['index']);
        $this->middleware(['permission:Add Supplier'])->only(['create']);
        $this->middleware(['permission:Edit Supplier'])->only(['edit']);
        $this->middleware(['permission:Delete Supplier'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupplierDataTable $dataTable)
    {
        set_page_meta(__('custom.suppliers'));
        return $dataTable->render('admin.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: convert to service
        $countries = SystemCountry::get();

        set_page_meta(__('custom.add_supplier'));
        return view('admin.suppliers.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        $data = $request->validated();

        if ($this->supplierService->createOrUpdateWithFile($data, 'avatar')) {
            flash(__('custom.supplier_created_successfully'))->success();
        } else {
            flash(__('custom.supplier_create_failed'))->error();
        }

        return redirect()->route('admin.suppliers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        set_page_meta(__('custom.supplier_details'));

        return view('admin.suppliers.show', $this->supplierService->supplierShowDetails($supplier));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = $this->supplierService->get($id);
        if (!$supplier) abort(404);

        $countries = SystemCountry::get();

        set_page_meta(__('custom.edit_supplier'));
        return view('admin.suppliers.edit', compact('supplier', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->supplierService->createOrUpdateWithFile($data, 'avatar', $id)) {
            flash(__('custom.supplier_updated_successfully'))->success();
        } else {
            flash(__('custom.supplier_update_failed'))->error();
        }

        return redirect()->route('admin.suppliers.index');
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
            if ($this->supplierService->delete($id)) {
                flash(__('custom.supplier_deleted_successfully'))->success();
            } else {
                flash(__('custom.supplier_delete_failed'))->error();
            }
        } catch (\Throwable $th) {
            flash(__('custom.this_record_already_used'))->warning();
        }

        return redirect()->route('admin.suppliers.index');
    }
}
