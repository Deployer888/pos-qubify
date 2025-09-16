<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Models\DraftInvoice;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\SaleReturnRequest;
use App\Services\Dashboard\DashboardService;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    use ApiReturnFormatTrait;
    public function __construct(DashboardService $dashboardService)
    {
        $this->services = $dashboardService;
    }

    public function index(Request $request)
    {
        $data['total_product']                  = make2digits(Product::where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse'])->count());
//        $data['total_product']                  = make2digits(ProductStock::count());
        $data['total_invoice']                  = make2digits(Invoice::where('customer_id',Auth()->guard('api_customer')->user()->id)->count());

        $data['total_invoice_amount']           = make2digits(Invoice::where('customer_id',Auth()->guard('api_customer')->user()->id)->sum('total'));
        $data['total_invoice_amount_paid']      = make2digits(Invoice::where('customer_id',Auth()->guard('api_customer')->user()->id)->sum('total_paid'));

        $data['total_draft_invoice']            = make2digits(DraftInvoice::where('customer_id',Auth()->guard('api_customer')->user()->id)->count());
        $data['total_return_request']           = make2digits(SaleReturnRequest::where('requested_by',Auth()->guard('api_customer')->user()->id)->count());
        $data['total_return_request_amount']    = make2digits(SaleReturnRequest::where('requested_by',Auth()->guard('api_customer')->user()->id)->sum('return_total_amount'));
        $data['total_return_request_rejected']  = make2digits(SaleReturnRequest::where('requested_by',Auth()->guard('api_customer')->user()->id)
                                                    ->where('status',SaleReturnRequest::STATUS_REJECTED)->count());
        $data['total_return_request_accepted']  = make2digits(SaleReturnRequest::where('requested_by',Auth()->guard('api_customer')->user()->id)
                                                    ->where('status',SaleReturnRequest::STATUS_ACCEPTED)->count());


        $data['latest_sale']                    = Invoice::latest()->where('customer_id',auth()->guard('api_customer')->id())->limit(5)->get();

        $most_sale_ids = InvoiceItem::select(DB::raw('product_id, sum(quantity) as total'))
            ->groupBy('product_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->pluck('product_id');

        $data['best_item_all_time']             = Product::whereIn('id', $most_sale_ids)->whereNotIn('available_for',['warehouse'])->get();

        return $this->responseWithSuccess('Home page data', $data);
    }
    public function getTopProduct(Request $request)
    {
        $products               = $this->getMostSaleProduct($request);
        $productInfos           = [];

        foreach ($products as $key => $product) {
            $productInfos[] = [
                'id'            => $product->id,
                'name'          => $product->name,
                'sku'           => $product->sku,
                'barcode'       => $product->barcode,
                'price'         => $product->price,
                'thumb_url'     => $product->thumb_url,
                'total_sale'    => $product->totalSale(),
                'total_sale_qty'=> $product->totalSaleQty(),
            ];
        }
        return $this->responseWithSuccess('Top product', $productInfos);
    }

    private function getMostSaleProduct($request)
    {
        $most_sale_ids = InvoiceItem::query();

        if (!empty($request->all())) {
            $most_sale_ids->when($request->year, function (Builder $builder) use ($request) {
                $builder->whereHas('invoice', function (Builder $builder) use ($request) {
                    $yearStart  = Carbon::createFromDate($request->year)->copy()->startOfYear()->format('Y-m-d');
                    $yearEnd    = Carbon::createFromDate($request->year)->copy()->endOfYear()->format('Y-m-d');
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
}
