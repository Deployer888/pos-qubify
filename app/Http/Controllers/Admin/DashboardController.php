<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Expenses;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\InvoiceItem;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\SaleReturnItems;
use App\Models\SaleReturnRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Dashboard\DashboardService;

class DashboardController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */

    public function __construct(DashboardService $dashboardService)
    {
        //        $this->middleware('permission:Show Dashboard');
        $this->services = $dashboardService;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();

        $data['total_customer'] = make2digits(Customer::count());
        $data['total_supplier'] = make2digits(Supplier::count());
        $data['total_product'] = make2digits(Product::count());
        $data['total_sale'] = make2digits(Invoice::count());
        $data['total_purchase'] = make2digits(Purchase::count());
        $data['total_expenses'] = make2digits(Expenses::count());

        $data['total_warehouse']                    = make2digits(Warehouse::count());
        $data['total_active_coupon']                = (
            Coupon::whereDate('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->whereDate('end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->where('status', Coupon::STATUS_ACTIVE)->count()
        );

        $data['total_sale_amount']                  = make2digits(Invoice::sum('total'));
        $data['total_paid'] = make2digits(Invoice::sum('total_paid'));
        $data['total_due'] =  $data['total_sale_amount'] - $data['total_paid'];

        $data['total_purchase_amount']              = make2digits(Purchase::sum('total'));

        $data['total_expenses_amount']              = make2digits(Expenses::sum('total'));

        $data['total_product_with_variant']         = make2digits(ProductStock::count());
        $data['total_sale_return_request']          = make2digits(SaleReturnRequest::count());
        $data['total_pending_sale_return_request']  = make2digits(SaleReturnRequest::count());
        $data['total_stock']                        = make2digits(ProductStock::sum('quantity'));
        $data['total_sale_return']                  = make2digits(SaleReturnItems::sum('return_qty'));
        $data['total_product_category']             = make2digits(ProductCategory::count());
        $data['invoice_created_by_auth_user']       = make2digits(Invoice::where('created_by', auth()->id())->count());
        if ($authUser->can('Total Sale By Auth User')) {
            $data['total_sale_by_auth_user'] = make2digits(Invoice::where('created_by', auth()->id())->sum('total'));
        }
        if ($authUser->can('Latest Sales')) {
            $data['latest_sale'] = Invoice::latest()->limit(5)->get();
        }
        if ($authUser->can('Best Items')) {
            $most_sale_ids = InvoiceItem::select(DB::raw('product_id, sum(quantity) as total'))
                ->groupBy('product_id')
                ->orderBy('total', 'DESC')
                ->limit(5)
                ->pluck('product_id');

            $data['most_sale'] = Product::whereIn('id', $most_sale_ids)->get();
        }
        set_page_meta('Dashboard');
        return view('admin.dashboard.index', compact('data'), $this->salesChartData($request));
    }


    /**
     * getTopProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function getTopProduct(Request $request)
    {
        $products = $this->getMostSaleProduct($request);
        $productInfos = [];

        foreach ($products as $key => $product) {
            $productInfos[] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'price' => $product->price,
                'thumb_url' => $product->thumb_url,
                'total_sale' => $product->totalSale(),
            ];
        }
        return $productInfos;
    }

    /**
     * getMostSaleProduct
     *
     * @param  mixed $request
     * @return void
     */
    private function getMostSaleProduct($request)
    {
        $most_sale_ids = InvoiceItem::query();

        if (!empty($request->all())) {
            $most_sale_ids->when($request->year, function (Builder $builder) use ($request) {
                $builder->whereHas('invoice', function (Builder $builder) use ($request) {
                    $yearStart = Carbon::parse($request->year)->startOfYear()->format('Y-m-d');
                    $yearEnd = Carbon::parse($request->year)->endOfYear()->format('Y-m-d');
                    $builder->whereBetween('date', [$yearStart, $yearEnd]);
                });
            })
                ->when($request->month, function (Builder $builder) use ($request) {
                    $builder->whereHas('invoice', function (Builder $builder) use ($request) {
                        $monthStart = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
                        $monthEnd = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');
                        $builder->whereBetween('date', [$monthStart, $monthEnd]);
                    });
                })
                ->when($request->week, function (Builder $builder) use ($request) {
                    $builder->whereHas('invoice', function (Builder $builder) use ($request) {
                        $weekStart = Carbon::parse($request->week)->startOfWeek()->format('Y-m-d');
                        $weekEnd = Carbon::parse($request->week)->endOfWeek()->format('Y-m-d');
                        $builder->whereBetween('date', [$weekStart, $weekEnd]);
                    });
                });
        } else {
            $most_sale_ids->whereHas('invoice', function (Builder $builder) {
                $monthStart = Carbon::parse(date('Y-m'))->startOfMonth()->format('Y-m-d');
                $monthEnd = Carbon::parse(date('Y-m'))->endOfMonth()->format('Y-m-d');
                $builder->whereBetween('date', [$monthStart, $monthEnd]);
            });
        }

        $most_sale_ids = $most_sale_ids
            ->select(DB::raw('product_id, sum(quantity) as total'))
            ->groupBy('product_id')
            ->orderBy('total', 'DESC')
            ->pluck('product_id');

        $products = Product::query()
            ->whereIn('id', $most_sale_ids)
            ->limit(6)
            ->get();

        return $products;
    }

    /**
     * salesChartData
     *
     * @param  mixed $request
     * @return void
     */
    public function salesChartData($request): array
    {
        $total = 0;
        $data = [];
        $report_range = '';
        $start = $request->from_date;
        $end = $request->to_date;

        if ($start && $end) {
            $report_range = $start . ' - ' . $end;
            $data = $this->services->filterByDateRange($start, $end);
        } else {
            $report_range = 'All Time';
            $data = $this->services->get(null);
        }

        // Calculate total
        if ($data instanceof Collection) {
            $total = $data->sum('total');
        }


        // Monthly graph
        $graph_data = $this->services->monthGraph($start, $end);
        // Pie graph
        $pie_graph_data = $this->services->monthGraphPie();
        // Single month
        $single_month_graph = $this->services->singleMonthGraph();
        // This month total
        $this_month_total = $this->services->monthTotal(date('m'));
        // Last month total
        $last_month_total = $this->services->monthTotal(date("m", strtotime("first day of previous month")));
        // Total all time
        $total_all_time = $this->services->totalAllTime();

        return [
            'graph_data' => $graph_data,
            'pie_graph_data' => $pie_graph_data,
            'report_range' => $report_range,
            'total' => $total,
            'single_month_graph' => $single_month_graph,
            'this_month_total' => $this_month_total,
            'last_month_total' => $last_month_total,
            'total_all_time' => $total_all_time
        ];
    }


    public function setLang()
    {
        if (\request()->get('lang')) {
            Session::put('lang', \request('lang'));
        }
        return redirect()->back();
    }
}
