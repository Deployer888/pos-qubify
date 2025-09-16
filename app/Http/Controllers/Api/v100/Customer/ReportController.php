<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Services\Expenses\ExpensesService;
use App\Services\Invoice\InvoiceService;
use App\Services\Product\ProductService;
use App\Services\Purchase\PurchaseServices;
use App\Services\Report\ReportServices;
use App\Traits\ApiReturnFormatTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    use ApiReturnFormatTrait;

    protected $expensesService;
    protected $invoiceService;
    protected $purchaseServices;
    protected $productServices;
    protected $reportServices;

    public function __construct(
        ExpensesService $expensesService,
        InvoiceService $invoiceService,
        PurchaseServices $purchaseServices,
        ProductService $productServices,
        ReportServices $reportServices
    ) {
        $this->expensesService = $expensesService;
        $this->invoiceService = $invoiceService;
        $this->purchaseServices = $purchaseServices;
        $this->productServices = $productServices;
        $this->reportServices = $reportServices;
    }

    public function purchases(Request $request)
    {
        $gross_total    = 0;
        $total_paid     = 0;
        $data           = [];
        $report_range   = '';
        $start          = $request->from_date;
        $end            = $request->to_date;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data         = $this->invoiceService->filterByDateRange($start, $end, ['warehouse:id,name']);
        }

        if (isset($request->q) && $request->q = 'all-time') {
            $report_range   = 'All Time';
            $data           = $this->invoiceService->filterWareHouseWiseAll(['warehouse:id,name']);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $gross_total    = $data->sum('total');
            $total_paid     = $data->sum('total_paid');
        }
        return $this->responseWithSuccess('Purchase Report', [
            'data'          => $data,
            'gross_total'   => $gross_total,
            'total_paid'    => $total_paid,
            'report_range'  => $report_range,
        ]);
    }

    public function payments(Request $request)
    {
        $total              = 0;
        $data               = [];
        $report_range       = '';
        $start              = $request->from_date;
        $end                = $request->to_date;

        if ($start && $end) {
            $report_range   = $start . ' - ' . $end;
            $data           = $this->invoiceService->filterPaymentByDateRange($start, $end, ['invoice.warehouse:id,name']);
        }

        if (isset($request->q) && $request->q = 'all-time') {
            $report_range   = 'All Time';
            $data           = $this->invoiceService->getAllPayments(['invoice.warehouse:id,name']);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $total          = $data->sum('amount');
        }
        return $this->responseWithSuccess('Payment Report', [
            'data'          => $data,
            'total'         => $total,
            'report_range'  => $report_range
        ]);
    }

}
