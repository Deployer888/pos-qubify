<?php

namespace App\Http\Controllers\Customer\Report;

use App\Models\Warehouse;
use App\Services\Product\ProductService;
use App\Services\Report\ReportServices;
use PDF;
use Excel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Exports\PaymentsReportExport;
use App\Exports\PurchasesReportExport;
use App\Services\Invoice\InvoiceService;
use App\Services\Expenses\ExpensesService;
use App\Services\Purchase\PurchaseServices;

class ReportsController extends Controller
{
    protected $expensesService;
    protected $invoiceService;
    protected $purchaseServices;
    protected $productServices;
    protected $reportServices;

    /**
     * __construct
     *
     * @return void
     */
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

    /**
     * sales
     *
     * @param  mixed $request
     * @return void
     */
    public function purchases(Request $request)
    {
        $wareHouses = Warehouse::query()->pluck('name', 'id');

        $gross_total = 0;
        $total_paid = 0;

        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->invoiceService->filterByDateRange($start, $end);
        }
        if (isset($request->q) && $request->q = 'all-time') {
            $report_range = 'All Time';
            $data = $this->invoiceService->filterWareHouseWiseAll(['warehouse']);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $gross_total = $data->sum('total');
            $total_paid = $data->sum('total_paid');
        }


        set_page_meta(__('custom.buys_report'));
        return view('customer.reports.customer-purchase', compact('data', 'report_range', 'gross_total', 'total_paid', 'wareHouses'));
    }


    /**
     * exportSales
     *
     * @param  mixed $request
     * @return void
     */
    public function exportPurchases(Request $request)
    {
        $gross_total = 0;
        $total_paid = 0;

        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;
        $type = $request->type;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->invoiceService->filterByDateRange($start, $end);
        } else {
            $report_range = 'All Time';
            $data = $this->invoiceService->get(null);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $gross_total = $data->sum('total');
            $total_paid = $data->sum('total_paid');
        }



        // return view('admin.reports.pdf.sales', compact('data', 'report_range', 'gross_total', 'total_paid'));

        $name = 'Sales-report-' . Str::slug($report_range);
        if ($type == 'pdf') {
            $pdf = PDF::loadView('admin.reports.pdf.sales', ['data' => $data, 'report_range' => $report_range, 'gross_total' => $gross_total, 'total_paid' => $total_paid]);
            return $pdf->download($name . '.pdf');
        } else if ($type == 'csv') {
            return Excel::download(new SalesReportExport($data), $name . '.csv');
        } else if ($type == 'excel') {
            return Excel::download(new SalesReportExport($data), $name . '.xlsx');
        }
    }

    /**
     * payments
     *
     * @param  mixed $request
     * @return void
     */
    public function payments(Request $request)
    {
        $wareHouses = Warehouse::query()->pluck('name', 'id');

        $total = 0;
        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;


        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->invoiceService->filterPaymentByDateRange($start, $end, ['invoice.warehouse']);
        }

        if (isset($request->q) && $request->q = 'all-time') {
            $report_range = 'All Time';
            $data = $this->invoiceService->getAllPayments(['invoice.warehouse']);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $total = $data->sum('amount');
        }

        set_page_meta(__('custom.payments_report'));
        return view('customer.reports.payments', compact('data', 'report_range', 'total', 'wareHouses'));
    }


    /**
     * exportPayments
     *
     * @param  mixed $request
     * @return void
     */
    public function exportPayments(Request $request)
    {
        $total = 0;
        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;
        $type = $request->type;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->invoiceService->filterPaymentByDateRange($start, $end);
        } else {
            $report_range = 'All Time';
            $data = $this->invoiceService->getAllPayments();
        }
        // Calculate total
        if ($data instanceof Collection) {
            $total = $data->sum('amount');
        }


        // return view('admin.reports.pdf.payments', compact('data', 'report_range', 'total'));

        $name = 'Payment-report-' . Str::slug($report_range);
        if ($type == 'pdf') {
            $pdf = PDF::loadView('admin.reports.pdf.payments', ['data' => $data, 'report_range' => $report_range, 'total' => $total]);
            //            return $pdf->download($name . '.pdf');
            return $pdf->download($name . '.pdf', array("Attachment" => false));
        } else if ($type == 'csv') {
            return Excel::download(new PaymentsReportExport($data), $name . '.csv');
        } else if ($type == 'excel') {
            return Excel::download(new PaymentsReportExport($data), $name . '.xlsx');
        }
    }
}
