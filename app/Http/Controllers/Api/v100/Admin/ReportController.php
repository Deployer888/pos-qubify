<?php

namespace App\Http\Controllers\Api\v100\Admin;


use DB;
use PDF;
use Excel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Exports\ExpensesReportExport;
use App\Exports\PaymentsReportExport;
use App\Exports\PurchasesReportExport;
use App\Services\Report\ReportServices;
use App\Services\Invoice\InvoiceService;
use App\Services\Product\ProductService;
use App\Services\Expenses\ExpensesService;
use App\Services\Purchase\PurchaseServices;

class ReportController extends Controller
{
    use ApiReturnFormatTrait;

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
    )
    {
        $this->expensesService      = $expensesService;
        $this->invoiceService       = $invoiceService;
        $this->purchaseServices     = $purchaseServices;
        $this->productServices      = $productServices;
        $this->reportServices       = $reportServices;
    }

    public function expenseReport(Request $request)
    {
            $total              = 0;
            $data               = [];
            $report_range       = '';
            $start              = $request->from_date;
            $end                = $request->to_date;

            if ($start && $end) {
                $report_range   = $start . ' - ' . $end;
                $data           = $this->expensesService->filterByDateRange($start, $end, ['category:id,name']);
            }

            if (isset($request->q) && $request->q = 'all-time') {
                $report_range   = 'All Time';
                $data           = $this->expensesService->get(null, ['category:id,name']);
            }

            // Calculate total
            if ($data instanceof Collection) {
                $total          = $data->sum('total');
            }
            return $this->responseWithSuccess('Expense Report', [
                'data'          => $data,
                'total'         => $total,
                'report_range'  => $report_range
            ]);
    }
    public function exportExpenses(Request $request)
    {
        $total = 0;
        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;
        $type = $request->type;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->expensesService->filterByDateRange($start, $end, ['category']);
        } else {
            $report_range = 'All Time';
            $data = $this->expensesService->get(null, ['category']);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $total = $data->sum('total');
        }


        // return view('admin.reports.pdf.expenses', compact('data', 'report_range', 'total'));

        $name = 'Expenses-report-' . Str::slug($report_range);
        if ($type == 'pdf') {
            $pdf = PDF::loadView('admin.reports.pdf.expenses', ['data' => $data, 'report_range' => $report_range, 'total' => $total]);
            return $pdf->download($name . '.pdf');
        } else if ($type == 'csv') {
            return Excel::download(new ExpensesReportExport($data), $name . '.csv');
        } else if ($type == 'excel') {
            return Excel::download(new ExpensesReportExport($data), $name . '.xlsx');
        }
    }
    public function sales(Request $request)
    {
        $gross_total        = 0;
        $total_paid         = 0;

        $data               = [];
        $report_range       = '';
        $start              = $request->from_date;
        $end                = $request->to_date;

        if ($start && $end) {
            $report_range   = $start . ' - ' . $end;
            $data           = $this->invoiceService->filterByDateRange($start, $end);
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
        return $this->responseWithSuccess('Sales Report', [
            'data'          => $data,
            'gross_total'   => $gross_total,
            'total_paid'    => $total_paid,
            'report_range'  => $report_range,
        ]);
    }
    public function exportSales(Request $request)
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

    public function purchases(Request $request)
    {
        $total              = 0;
        $data               = [];
        $report_range       = '';
        $start              = $request->from_date;
        $end                = $request->to_date;

        if ($start && $end) {
            $report_range   = $start . ' - ' . $end;
            $data           = $this->purchaseServices->filterByDateRange($start, $end, ['purchaseItems', 'warehouse:id,name']);
        }

        if (isset($request->q) && $request->q = 'all-time') {
            $report_range   = 'All Time';
            $data           = $this->purchaseServices->allTime(['purchaseItems', 'warehouse:id,name']);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $total          = $data->sum('total');
        }
        return $this->responseWithSuccess('Purchase Report', [
            'data'          => $data,
            'total'         => $total,
            'report_range'  => $report_range
        ]);
    }
    public function exportPurchases(Request $request)
    {
        $total = 0;
        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;
        $type = $request->type;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->purchaseServices->filterByDateRange($start, $end);
        } else {
            $report_range = 'All Time';
            $data = $this->purchaseServices->get(null);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $total = $data->sum('total');
        }


        // return view('admin.reports.pdf.purchases', compact('data', 'report_range', 'total'));

        $name = 'Purchase-report-' . Str::slug($report_range);
        if ($type == 'pdf') {
            $pdf = PDF::loadView('admin.reports.pdf.purchases', ['data' => $data, 'report_range' => $report_range, 'total' => $total]);
            return $pdf->download($name . '.pdf');
        } else if ($type == 'csv') {
            return Excel::download(new PurchasesReportExport($data), $name . '.csv');
        } else if ($type == 'excel') {
            return Excel::download(new PurchasesReportExport($data), $name . '.xlsx');
        }
    }
    public function payments(Request $request)
    {
        $total          = 0;
        $data           = [];
        $report_range   = '';
        $start          = $request->from_date;
        $end            = $request->to_date;


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
            return $pdf->download($name . '.pdf',array("Attachment" => false));
        } else if ($type == 'csv') {
            return Excel::download(new PaymentsReportExport($data), $name . '.csv');
        } else if ($type == 'excel') {
            return Excel::download(new PaymentsReportExport($data), $name . '.xlsx');
        }
    }
    public function warehouseStock(Request $request)
    {
        $products = $this->productServices->productAllWarehouseStock();
        return $this->responseWithSuccess('Warehouse Stock Report', $products);
    }

    public function lossProfit(Request $request)
    {
        try {
            $report_range       = '';
            $start              = $request->from_date;
            $end                = $request->to_date;

            if ($start && $end) {
                $report_range   = $start . ' - ' . $end;
            }

            if (isset($request->q) && $request->q = 'all-time') {
                $report_range   = 'All Time';
            }

            $loss_profit_data = $this->reportServices->lossProfitCalculation($request);

            return $this->responseWithSuccess('Loss Profit Report', [
                'data'          => $loss_profit_data,
                'report_range'  => $report_range,
            ]);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(),[], 500);
        }
    }
}
