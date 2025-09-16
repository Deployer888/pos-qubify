<?php

namespace App\Http\Controllers\Admin\Sale;

use App\DataTables\SaleReturnCreateDataTable;
use App\DataTables\SaleReturnDataTable;
use App\DataTables\SaleReturnRequestDataTable;
use App\Http\Controllers\Controller;
use App\Models\SaleReturn;
use App\Models\SaleReturnRequest;
use App\Models\Warehouse;
use App\Services\Sale\SaleReturnServices;
use App\Services\Warehouse\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SaleReturnController extends Controller
{
    protected $warehouseService;
    /**
     * __construct
     *
     * @param  mixed $saleReturnServices
     * @return void
     */
    public function __construct(SaleReturnServices $saleReturnServices, WarehouseService $warehouseService)
    {
        $this->services = $saleReturnServices;
        $this->warehouseService = $warehouseService;

        $this->middleware(['permission:Show Sale Return'])->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SaleReturnDataTable $dataTable)
    {
        set_page_meta(__t('sale_return_list'));

        return $dataTable->render('admin.sales.return_list');
    }

    public function createList(SaleReturnCreateDataTable $dataTable)
    {

        set_page_meta(__t('sale_return'));

        return $dataTable->render('admin.sales.return_create');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($sale_id)
    {
        set_page_meta(__t("return") . ' ' . __t('sales'));

        $returnAbleInvoice = $this->services->getReturnableSale($sale_id);


        return view('admin.sales.return', [
            'warehouses' => $this->warehouseService->getActiveData(),
            'sales' => $returnAbleInvoice,
            'warehouse' => $returnAbleInvoice->warehouse_id
                ? $this->warehouseService->getWareHouse($returnAbleInvoice->warehouse_id)
                : $this->warehouseService->defaultWareHouse()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->services->validate($request)->store($request);

        flash(__t('sales_return_successful'))->success();

        return redirect()->route('admin.sales-return.createable_list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        set_page_meta(__t('show') . ' ' . __t('sale_return'));

        return view('admin.sales.return_show', [
            'sale_return' => SaleReturn::query()
                ->with('invoice', 'saleReturnItems')
                ->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
