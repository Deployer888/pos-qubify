<?php

namespace App\Http\Controllers\Admin\Invoice;

use Throwable;
use App\Models\User;
use App\Mail\InvoiceSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\InvoiceDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\InvoiceRequest;
use App\Services\Invoice\InvoiceService;
use App\Services\Product\ProductService;
use App\Notifications\GlobalNotification;
use App\Services\Customer\CustomerService;
use App\Services\Warehouse\WarehouseService;
use App\Services\Product\ProductCategoryService;

class InvoicesController extends Controller
{
    protected $invoiceService;
    protected $customerService;
    protected $productService;
    protected $categoryService;
    protected $warehouseService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        InvoiceService $invoiceService,
        CustomerService $customerService,
        ProductService $productService,
        ProductCategoryService $categoryService,
        WarehouseService $warehouseService
    ) {
        $this->invoiceService = $invoiceService;
        $this->customerService = $customerService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->warehouseService = $warehouseService;

        // $this->middleware(['permission:List Invoice'])->only(['index']);
        $this->middleware(['permission:Add Invoice'])->only(['create']);
        $this->middleware(['permission:Edit Invoice'])->only(['edit']);
        $this->middleware(['permission:Show Invoice'])->only(['show']);
        $this->middleware(['permission:Delete Invoice'])->only(['destroy']);
        $this->middleware(['permission:View Payment Invoice'])->only(['getPayments']);
        $this->middleware(['permission:Add Payment Invoice'])->only(['addPayment']);
        $this->middleware(['permission:Make Payment Invoice'])->only(['makePayment']);
        // $this->middleware(['permission:Download Invoice'])->only(['download']);
        $this->middleware(['permission:Send Invoice'])->only(['sendInvoice']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InvoiceDataTable $dataTable)
    {
        set_page_meta(__('custom.invoice'));
        return $dataTable->render('admin.invoices.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $warehouses = $this->warehouseService->pluck();
        $warehouse = count($warehouses) > 1 && \request('warehouse')
            ? $this->warehouseService->getWareHouse(\request('warehouse'))
            : $this->warehouseService->firstWarehouse();

        // Check if no warehouse is available
        if (!$warehouse) {
            flash(__('custom.no_warehouse_available'))->error();
            return redirect()->route('warehouses.index')->with('error', 'No active warehouse found. Please create a warehouse first.');
        }

        $all_status = $this->invoiceService->getAllStatus();
        $customers = $this->customerService->getActiveData(null, ['b_country_data', 'b_state_data', 'b_city_data']);
        //        $products = $this->productService->wareHouseWiseProducts('warehouseStockQty', $warehouse);/*$this->productService->get(null, [], 20);*/
        $productStocks = $this->productService->wareHouseWiseAllProductStocks([
            'product',
            'attribute',
            'attributeItem',
        ], $warehouse);

        $categories = $this->categoryService->getParents('subCategory');

        $formatted_category      = [];
        $array_key               = 0;

        foreach ($categories as $key => $category) {
            $formatted_category[$array_key] = [
                'id'    => $category->id,
                'text'  => $category->name,
            ];
            $array_key++;
            foreach ($category->subCategory as $subCategory) {
                $formatted_category[$array_key] = [
                    'id'    => $subCategory->id,
                    'text'  => ' --' . $subCategory->name,
                ];
                $array_key++;
                foreach ($subCategory->subCategory as $subSubCategory) {
                    $formatted_category[$array_key] = [
                        'id'    => $subSubCategory->id,
                        'text'  => ' ---' . $subSubCategory->name,
                    ];
                    $array_key++;
                }
            }
        }

        array_unshift($formatted_category, ['id' => 'all', 'text' => 'All Categories']);
        set_page_meta(__('custom.create_invoice'));
        return view('admin.invoices.create', [
            'customers'     => $customers,
            'product_stocks' => $productStocks,
            'categories'    => $formatted_category,
            'warehouses'    => $warehouses,
            'warehouse'     => $warehouse,
            'all_status'    => $all_status,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequest $request)
    {
        // dd($request->all());
        $data = $request->validated();
        // Customer
        if (!$data['customer_id']) {
            $data['customer'] = $data['walkin_customer'];
        } else {
            $data['customer_id'] = customer_id($data['customer_id']);
            $customer = $this->customerService->get($data['customer_id']);
            $data['customer'] = $customer;
        }

        try {
            $invoice = $this->invoiceService->storeOrUpdate($data);

            // try {
            //     $superAdmins = User::role('Super Admin')->get();
            //     $superAdmin = $superAdmins->first();
            //     if (config('is_invoice_notification') == 'yes') {
            //         Log::info('Invoice Notification');
            //         $data = [
            //             'title' => 'New Invoice Created',
            //             'message' => 'Your new invoice has been created. View the invoice for more information.',
            //             'url' => route('admin.invoices.show', $invoice->id),
            //         ];
            //         $superAdmin->notify(new GlobalNotification($data));
            //     }

            //     if (config('mail.mailers.smtp.host') != null && config('mail.mailers.smtp.port') != null && config('mail.mailers.smtp.username') != null && config('mail.mailers.smtp.password') != null) {
            //         if (config('is_invoice_email') == 'yes' && $superAdmins->count() > 0) {
            //             $ccAdmins = $superAdmins->skip(1)->pluck('email')->toArray(); // Get all Super Admins except the first one
            //             $invoice = $this->invoiceService->get($invoice->id);
            //             Mail::to($superAdmin->email)
            //                 ->cc($ccAdmins) // Add other Super Admins in CC
            //                 ->send(new InvoiceSend($invoice));
            //         }
            //     }
            // } catch (\Exception $e) {

            //     Log::error('Error sending notification or email: ' . $e->getMessage());
            //     flash(__('Failed to send notification or email. Please try again later'))->success();
            //     return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            // }

            flash(__('custom.invoice_created_successful'))->success();

            return response()->json(['success' => true, 'invoice' => $invoice->id], 200);
        } catch (Throwable $th) {
            flash(__('custom.invoice_created_failed'))->success();

            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = $this->invoiceService->get($id, ['items', 'payments', 'customerInfo', 'saleReturns']);
        set_page_meta(__('custom.show_invoice'));
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = $this->invoiceService->get($id, ['items']);
        $wareHouseId = $invoice->warehouse_id ?? $this->warehouseService->defaultWareHouse()->id;
        $warehouse = $this->warehouseService->get($wareHouseId);
        $all_status = $this->invoiceService->getAllStatus();
        $customers = $this->customerService->getActiveData(null, ['b_country_data', 'b_state_data', 'b_city_data']);
        //        $products = $this->productService->wareHouseWiseProducts('warehouseStockQty', $warehouse);//$this->productService->get(null, [], 20);
        $productStocks = $this->productService->wareHouseWiseAllProductStocks(['product', 'attribute', 'attributeItem'], $warehouse);
        $categories = $this->categoryService->getParents('subCategory');

        $formatted_category      = [];
        $array_key               = 0;

        foreach ($categories as $category) {
            $formatted_category[$array_key] = [
                'id'    => $category->id,
                'text'  => $category->name,
            ];
            $array_key++;
            foreach ($category->subCategory as $subCategory) {
                $formatted_category[$array_key] = [
                    'id'    => $subCategory->id,
                    'text'  => ' --' . $subCategory->name,
                ];
                $array_key++;
                foreach ($subCategory->subCategory as $subSubCategory) {
                    $formatted_category[$array_key] = [
                        'id'    => $subSubCategory->id,
                        'text'  => ' ---' . $subSubCategory->name,
                    ];
                    $array_key++;
                }
            }
        }

        array_unshift($formatted_category, ['id' => 'all', 'text' => 'All Categories']);
        set_page_meta(__('custom.edit_invoice'));
        return view('admin.invoices.edit', [
            'invoice'       => $invoice,
            'customers'     => $customers,
            'product_stocks' => $productStocks,
            'categories'    => $formatted_category,
            'all_status'    => $all_status,
            'warehouse'     => $warehouse,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceRequest $request, $id)
    {
        $data = $request->validated();

        // Customer
        if (!$data['customer_id']) {
            $data['customer'] = $data['walkin_customer'];
        } else {
            $customer = $this->customerService->get($data['customer_id']);
            $data['customer'] = $customer;
        }
        try {
            $invoice = $this->invoiceService->storeOrUpdate($data, $id);
            flash(__('custom.invoice_updated_successful'))->success();
            return response()->json(['success' => true, 'invoice' => $invoice->id], 200);
        } catch (Throwable $th) {
            flash(__('custom.invoice_updated_failed'))->success();
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->invoiceService->deleteInvoice($id);
        return redirect()->route('admin.invoices.index');
    }

    /**
     * download
     *
     * @param  mixed $id
     * @return void
     */
    public function download($id)
    {
        return $this->invoiceService->download($id);
    }

    public function print($id)
    {
        return view('admin.invoices.thermal-print', [
            'invoice' => $this->invoiceService->get($id)
        ]);
    }

    /**
     * addPayment
     *
     * @param  mixed $request
     * @return void
     */
    public function addPayment(Request $request)
    {
        $data = $this->validate($request, [
            'invoice_id' => 'required|numeric',
            'date' => 'required',
            'payment_type' => 'required|max:50',
            'amount' => 'required|numeric',
            'notes' => 'nullable|max:200'
        ]);

        try {
            $this->invoiceService->addPayment($data);

            flash(__('custom.payment_added_successful'))->success();
            return redirect()->route('admin.invoices.index');
        } catch (Throwable $th) {
            flash(__('custom.payment_added_failed'))->error();
            return redirect()->route('admin.invoices.index');
        }
    }

    /**
     * getPayments
     *
     * @param  mixed $invoice_id
     * @return void
     */
    public function getPayments($invoice_id)
    {
        return $this->invoiceService->getPayments($invoice_id);
    }

    /**
     * invoiceCustomerEmail
     *
     * @param  mixed $id
     * @return void
     */
    public function invoiceCustomerEmail($id)
    {
        $email = $this->invoiceService->invoiceCustomerEmail($id);

        return response()->json($email);
    }

    /**
     * deletePayment
     *
     * @param  mixed $id
     * @return void
     */
    public function deletePayment($id)
    {
        $this->invoiceService->deletePayment($id);

        flash(__('custom.payment_deleted_successful'))->success();
        return redirect()->route('admin.invoices.index');
    }

    /**
     * sendInvoice
     *
     * @param  mixed $request
     * @return void
     */
    public function sendInvoice(Request $request)
    {
        $data = $this->validate($request, [
            'invoice_id' => 'required|numeric',
            'email' => 'required|email',
        ]);

        try {
            $this->invoiceService->sendInvoice($data);

            flash(__('custom.invoice_added_successful'))->success();
            return redirect()->route('admin.invoices.index');
        } catch (Throwable $th) {
            flash(__('custom.invoice_added_failed'))->error();
            return redirect()->route('admin.invoices.index');
        }
    }

    /**
     * getByToken
     *
     * @param  mixed $token
     * @return void
     */
    public function getByToken($token)
    {

        try {
            $invoice  = $this->invoiceService->getByToken($token);
            if (!$invoice) abort(404);

            return view('admin.invoices.live_url', compact('invoice'));
        } catch (Throwable $th) {
            abort(404);
        }
    }

    /**
     * payStripe
     *
     * @param  mixed $request
     * @return void
     */
    public function payStripe(Request $request)
    {
        try {
            $payment_url = $this->invoiceService->payByStripe($request->invoice_id);

            return redirect($payment_url);
        } catch (\Throwable $th) {
            flash(__('custom.stripe_setup_message'))->warning();
            return back();
        }
    }

    /**
     * stripeSuccess
     *
     * @param  mixed $request
     * @return void
     */
    public function stripeSuccess(Request $request)
    {
        $this->invoiceService->stripePaymentSuccess($request->invoice_id);

        return redirect()->route('payment.success');
    }

    /**
     * stripeCancel
     *
     * @param  mixed $request
     * @return void
     */
    public function stripeCancel(Request $request)
    {
        // TODO: handle cancel
    }

    /**
     * paypalSuccess
     *
     * @param  mixed $request
     * @return void
     */
    public function paypalSuccess(Request $request)
    {
        $this->invoiceService->paypalPaymentSuccess($request->invoice_id, $request->order_id);

        return redirect()->route('payment.success');
    }

    /**
     * paymentSuccess
     *
     * @return void
     */
    public function paymentSuccess()
    {
        return view('admin.invoices.payment_success');
    }


    /**
     * makePayment
     *
     * @param  mixed $id
     * @return void
     */
    public function makePayment($id)
    {
        $invoice = $this->invoiceService->get($id, ['items']);

        set_page_meta(__('custom.make_invoice'));
        return view('admin.invoices.make_payment', compact('invoice'));
    }

    /**
     * makePaymentPost
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function makePaymentPost(Request $request, $id)
    {
        $this->invoiceService->makePayment($id, $request->all());

        flash(__('custom.payment_make_successfully'))->success();

        return redirect()->route('admin.invoices.show', $id);
    }
    public function deliveryStatusChange($id, $status)
    {
        $this->invoiceService->deliveryStatusChange($id, $status);

        flash(__('custom.invoice_delivered_status_changed_successfully'))->success();

        return redirect()->back();
    }
}
