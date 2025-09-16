<?php

namespace App\Http\Controllers\Admin\Sale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Invoice\InvoiceService;
use App\Services\Customer\CustomerService;

class SalesController extends Controller
{

    protected $invoiceService;
    protected $customerService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        InvoiceService $invoiceService,
        CustomerService $customerService
    ) {
        $this->invoiceService = $invoiceService;
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_status = $this->invoiceService->getAllStatus();
        $customers = $this->customerService->getActiveData(null, ['b_country_data', 'b_state_data', 'b_city_data']);

        set_page_meta(__('custom.add_sale'));
        return view('admin.sales.create', compact('all_status', 'customers'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
