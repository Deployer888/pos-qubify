<?php

namespace App\Services\Invoice;

use App\Models\DraftInvoice;
use App\Models\DraftInvoiceItem;
use PDF;
use Throwable;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductStock;
use App\Services\BaseService;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Payments\PaypalService;
use App\Services\Payments\StripeService;
use App\Services\Product\ProductService;

/**
 * InvoiceService
 */
class DraftInvoiceService extends BaseService
{
    protected $productService;
    protected $stripeService;
    protected $paypalService;
    protected $productStock;
    protected $product;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $model = new DraftInvoice();
        parent::__construct($model);

        $this->productService   = app(ProductService::class);
        $this->stripeService    = app(StripeService::class);
        $this->paypalService    = app(PaypalService::class);
        $this->productStock     = app(ProductStock::class);
        $this->product          = app(Product::class);
    }

    /**
     * getAllStatus
     *
     * @return array
     */
    public function getAllStatus(): array
    {
        try {
            return $this->model::INVOICE_ALL_STATUS;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function download($id)
    {
        try {
            $draftInvoice    = $this->get($id, ['items']);
            $pdf        = PDF::loadView('admin.invoices.pdf.invoice', ['data' => $draftInvoice]);
            return $pdf->download('Invoice_' . make8digits($draftInvoice->id) . '.pdf');
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * storeOrUpdate
     *
     * @param mixed $data
     * @param mixed $id
     * @return void
     */
    public function storeOrUpdate(array $data, $id = null)
    {
        
        try {
            DB::beginTransaction();
            if ($id) {
                $draftInvoice                        = $this->model::findOrFail($id);
            } else {
                $draftInvoice                        = new $this->model();
            }

            $draftInvoice->date                      = Carbon::parse($data['date'])->format('Y-m-d H:i:s');
            $draftInvoice->due_date                  = Carbon::parse($data['due_date'])->format('Y-m-d H:i:s');
            $draftInvoice->customer_id               = $data['customer_id'];
            $draftInvoice->customer                  = $data['customer'];
            $draftInvoice->warehouse_id              = $data['warehouse_id'];
            $draftInvoice->billing_info              = $data['billing'];
            $draftInvoice->shipping_info             = $data['shipping'];
            $draftInvoice->items_data                = $data['items'];
            $draftInvoice->total_paid                = ($data['payment_type'] == $this->model::PAYMENT_TYPE_CASH || $data['payment_type'] == $this->model::PAYMENT_TYPE_BANK) ? $data['total_paid'] : 0;
            $draftInvoice->last_paid                 = $data['total_paid'];
            $draftInvoice->payment_type              = $data['payment_type'];
            if (isset($data['bank_info'])) {
                $draftInvoice->bank_info             = $data['bank_info'] ?? null;
            }
            $draftInvoice->global_discount           = $data['discount'];
            $draftInvoice->global_discount_type      = $data['discount_type'];
            $draftInvoice->notes                     = $data['notes'];
            $draftInvoice->status                    = $this->model::STATUS_PENDING;
            $draftInvoice->token                     = Str::random(64);
            //            $draftInvoice->created_by                = auth()->guard('customer')->check() ? auth()->guard('customer')->user()->id : (auth()->guard('api_customer')->check() ? auth()->guard('api_customer')->user()->id : Auth::id());
            //            $draftInvoice->updated_by                = auth()->guard('customer')->check() ? auth()->guard('customer')->user()->id : (auth()->guard('api_customer')->check() ? auth()->guard('api_customer')->user()->id : Auth::id());
            $draftInvoice->save();

            $gross_total    = 0;
            $total_tax      = 0;
            $total_discount = 0;


            // Delete old item
            if ($id) {
                DraftInvoiceItem::where('draft_invoice_id', $id)->delete();
            }

            // Add new item
            if ($data['items']) {
                foreach ($data['items'] as $item) {
                    // dd($item);
                    $product = $this->productService->get($item['product_id']);
                    if (!$product) continue;

                    $i_item                         = new DraftInvoiceItem();
                    $i_item->draft_invoice_id       = $draftInvoice->id;
                    $i_item->product_stock_id       = $item['id'];
                    $i_item->product_id             = $product->id;
                    $i_item->product_name           = $product->name;
                    $i_item->sku                    = $product->sku;
                    $i_item->quantity               = $item['quantity'];
                    $i_item->price                  = $item['price'];
                    $i_item->discount               = $item['discount'];
                    $i_item->discount_type          = $item['discount_type'];
                    $i_item->sub_total              = $item['price'] * $item['quantity'];

                    // Tax percent
                    if ($product->tax_status == Product::TAX_INCLUDED) {
                        if ($product->custom_tax) {
                            $i_item->tax    = $product->custom_tax;
                        } else {
                            $i_item->tax    = getDefaultTax();
                        }
                    }

                    // Calculate discount
                    $discount_amount = 0;
                    if ($item['discount_type'] == $this->model::DISCOUNT_PERCENT) {
                        $discount_amount = $item['price'] * ($item['discount'] / 100);
                    } else {
                        $discount_amount = $item['discount'];
                    }


                    $i_item->sub_total = ($item['price'] - $discount_amount) * $item['quantity'];

                    $i_item->save();

                    $gross_total += $i_item->sub_total;

                    // Calculate discount
                    $total_discount += $discount_amount;

                    // Calculate tax
                    $tax_amount = $item['price'] * ($i_item->tax / 100);
                    $total_tax += $tax_amount * $i_item->quantity;
                }
            }

            $total = $gross_total + $total_tax;

            // Calculate global discount
            $global_discount_amount = 0;
            if ($data['discount_type'] == $this->model::DISCOUNT_PERCENT) {
                $global_discount_amount = $total * ($data['discount'] / 100);
            } else {
                $global_discount_amount = $item['discount'];
            }

            $total -= $global_discount_amount;
            $total_discount += $global_discount_amount;

            // Update invoice info
            $draftInvoice->tax_amount = $total_tax;
            $draftInvoice->discount_amount = $total_discount;
            $draftInvoice->total = $total;

            // Update status
            if ($data['payment_type'] == $this->model::PAYMENT_TYPE_CASH && $data['total_paid'] > 0) {
                if ($data['total_paid'] >= $total) {
                    $draftInvoice->status = $this->model::STATUS_PAID;
                } else {
                    $draftInvoice->status = $this->model::STATUS_PARTIALLY_PAID;
                }
            }
            $draftInvoice->status = $this->model::STATUS_PENDING;
            $draftInvoice->last_paid = $total;
            $draftInvoice->payment_type = $draftInvoice->payment_type;

            $draftInvoice->save();


            DB::commit();
            return $draftInvoice;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function generateInvoiceNo()
    {
        // Generate invoice no
        $draftInvoice_no = 1;
        $last_item = $this->model::latest()->first();
        if ($last_item) $draftInvoice_no = $last_item->id + 1;
        return sprintf("%08d", $draftInvoice_no);
    }
    public function getDraftInvoiceList()
    {
        $draft_invoice = DraftInvoice::when(auth()->guard('customer')->check(), function ($q) {
            $q->where('customer_id', auth()->guard('customer')->user()->id);
        })
            ->when(auth()->guard('api_customer')->check(), function ($q) {
                $q->where('customer_id', auth()->guard('api_customer')->user()->id);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return $draft_invoice;
    }
}
