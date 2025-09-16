<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v100\Admin\AuthController;
use App\Http\Controllers\Api\v100\Customer\CustomerAuthController;
use App\Http\Controllers\Api\v100\Customer\DraftInvoiceController;
use App\Http\Controllers\Api\v100\Customer\InvoiceReturnController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('v100/customer/invoice-download/{id}', [\App\Http\Controllers\Api\v100\Customer\InvoiceController::class, 'download']);
Route::get('v100/admin/invoice-download/{id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'download']);

Route::get('v100/admin/warehouses/{warehouse}/download', [\App\Http\Controllers\Api\v100\Admin\WarehouseController::class,'showPdf']);
Route::get('v100/admin/products/{id}/barcode-download', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'downloadBarcode']);
Route::get('v100/admin/products/barcodes-download', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'downloadAllBarcode']);



Route::middleware('api.checkApiKey')->prefix('v100')->group(function () {
    Route::get('test', function () {
        return 'test';
    });

    //admin
    Route::post('admin/login', [AuthController::class, 'login']);

//    Route::post('signup', [AuthController::class, 'signup']);
//    Route::post('password_reset', [ProfileController::class, 'passwordReset']);

    //Customer
    Route::post('customer/login', [CustomerAuthController::class, 'login']);
    Route::post('customer/signup', [CustomerAuthController::class, 'signup'])->name('api.customer.auth.store.customer');


    //common
    Route::get('countries',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'getCountries'] );
    Route::get('states',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'getStateByCountry'] );
    Route::get('cities',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'getCitiesByState'] );

    Route::get('settings',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'getSettings'] );
    Route::post('general-info',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'storeGeneralInfo'] );
    Route::post('login-setting',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'storeLoginSetting'] );
    Route::post('payment-method',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'storePaymentMethod'] );
    Route::post('smtp-configuration',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'storeSMTP'] );
    Route::post('product-setting',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'storeProductSetting'] );
    Route::get('warehouses',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'warehouses']);
    Route::get('categories',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'categories'] );





    Route::middleware(['jwt.verify'])->group(function () {


        // Route::get('warehouses', [\App\Http\Controllers\Api\v100\Common\CommonController::class, 'warehouses']);
        // Route::get('brands',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'brands'] );
        // Route::get('manufacturers',[\App\Http\Controllers\Api\v100\Common\CommonController::class,'manufacturers'] );



        Route::middleware(['api.checkIsAdmin'])->prefix('admin')->group(function () {
            //Admin
           //Invoice
            Route::delete('invoice-delete/{id}',[\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'delete']);
            Route::post('invoice-create', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'create']);
            Route::put('invoice-update/{id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'update']);
            Route::get('invoice-list', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'index']);
            Route::get('invoice-details/{id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'show']);
            Route::get('invoices/payments/{invoice_id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class,'getPayments'])->name('invoices.get_payments');
            Route::post('invoices/delivered/{id}/{status}',[\App\Http\Controllers\Api\v100\Admin\InvoiceController::class,'deliveryStatusChange'])->name('invoices.delivery.status.change');
            Route::post('invoices/make-payment/{id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class,'makePaymentPost'])->name('invoices.makePaymentPost');
            Route::get('invoices/customer-email/{id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class,'invoiceCustomerEmail']);
            Route::post('invoices/payments/send', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class,'sendInvoice'])->name('invoices.sendInvoice');



            Route::apiResource('customers', Api\v100\Admin\CustomersController::class);
            Route::apiResource('product-stocks', Api\v100\Admin\ProductStocksController::class);
            Route::put('product-stocks-update-by-stock/{id}', [\App\Http\Controllers\Api\v100\Admin\ProductStocksController::class,'updateByStock'])->name('product-stocks.update-by-stock');
            Route::get('/products/stock-update/{id}', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'productQtyUpdate']);
            // Route::get('/products/{id}/barcode-download-link', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'getDownloadLink']);
            // Route::get('/products/{id}/barcode-download', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'downloadBarcode']);
            // Route::get('/products/barcodes-download-link', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'getAllDownloadLink']);
            // Route::get('/products/barcodes-download', [\App\Http\Controllers\Api\v100\Admin\ProductController::class,'downloadAllBarcode']);

            Route::post('customers/verify/{id}', [\App\Http\Controllers\Api\v100\Admin\CustomersController::class, 'verifyUnverify'])->name('customers.verify');

            Route::get('permissions',[\App\Http\Controllers\Api\v100\Admin\RolesController::class,'getPermissions']);
            Route::apiResource('roles', Api\v100\Admin\RolesController::class);
            Route::apiResource('users', Api\v100\Admin\UsersController::class);
            Route::apiResource('suppliers', Api\v100\Admin\SuppliersController::class);
            Route::apiResource('countries', Api\v100\Admin\CountryController::class);
            Route::apiResource('states', Api\v100\Admin\StateController::class);
            Route::apiResource('cities', Api\v100\Admin\CityController::class);

            Route::apiResource('attributes', Api\v100\Admin\AttributesController::class);
            Route::apiResource('measurement-units', Api\v100\Admin\MeasurementUnitsController::class);
            Route::apiResource('weight-units', Api\v100\Admin\WeightUnitsController::class);
            Route::apiResource('warehouses', Api\v100\Admin\WarehouseController::class);
         //   Route::get('warehouses/{warehouse}/download', [\App\Http\Controllers\Api\v100\Admin\WarehouseController::class,'showPdf'])->name('warehouses.show-pdf');

            Route::apiResource('brands', Api\v100\Admin\BrandController::class);
            Route::apiResource('manufacturers', Api\v100\Admin\ManufacturerController::class);
            Route::apiResource('categories', Api\v100\Admin\ProductCategoriesController::class);
            // Route::get('customers',[CustomerAuthController::class, 'index']);
            Route::get('dropdown-customers',[CustomerAuthController::class, 'getDropdownCustomers']);
            Route::get('login_user/details', [AuthController::class, 'loginUserDetails']);
            Route::post('profile-update', [\App\Http\Controllers\Api\v100\Admin\ProfileController::class, 'update']);
            // Route::get('roles', [\App\Http\Controllers\Api\v100\Admin\ProfileController::class, 'roles']);

            Route::get('home-page', [\App\Http\Controllers\Api\v100\Admin\HomePageController::class, 'index']);

            Route::get('top-product', [\App\Http\Controllers\Api\v100\Admin\HomePageController::class, 'getTopProduct']);
            Route::get('sale-chart-data', [\App\Http\Controllers\Api\v100\Admin\HomePageController::class, 'salesChartData']);
            Route::get('product-list', [\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'index']);
            Route::post('products/store',[\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'store']);
            Route::get('products/create',[\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'create']);
            Route::get('products/{id}',[\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'show']);
            Route::put('products/{id}',[\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'update']);
            Route::get('stock-wise-products', [\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'warehouseAndStockWiseProducts']);
            Route::apiResource('purchases', Api\v100\Admin\PurchaseController::class);
            Route::post('purchases/{purchase}/cancel', [\App\Http\Controllers\Api\v100\Admin\PurchaseController::class, 'storeCancelPurchase'])
            ->name('purchases.cancelPost');
            Route::post('purchases/{purchase}/receive', [\App\Http\Controllers\Api\v100\Admin\PurchaseReceiveController::class, 'storePurchasesReceive'])
            ->name('purchases.receive.store');
            Route::get('purchases/receive/list', [\App\Http\Controllers\Api\v100\Admin\PurchaseReceiveController::class, 'receives'])
            ->name('purchases.receive-list');
            Route::get('purchases/receive/show/{id}', [\App\Http\Controllers\Api\v100\Admin\PurchaseReceiveController::class, 'receiveShow'])
           ->name('purchases.receive.show');
           Route::delete('purchases/receive/delete/{id}', [\App\Http\Controllers\Api\v100\Admin\PurchaseReceiveController::class, 'receiveDelete'])
          ->name('purchases.receive.delete');
            Route::post('purchases/{purchase}/return', [\App\Http\Controllers\Api\v100\Admin\PurchaseReturnController::class, 'storePurchaseReturn'])
           ->name('purchases.return.store');
           Route::get('purchases/return/list', [\App\Http\Controllers\Api\v100\Admin\PurchaseReturnController::class, 'purchaseReturnList'])
          ->name('purchases.return.list');
          Route::get('purchases/return/show/{id}', [\App\Http\Controllers\Api\v100\Admin\PurchaseReturnController::class, 'returnShow'])
          ->name('purchases.return.show');
          Route::delete('purchases/return/delete/{id}', [\App\Http\Controllers\Api\v100\Admin\PurchaseReturnController::class, 'returnDelete'])
        ->name('purchases.return.delete');
            Route::get('/product-stock/search/name-sku/{query}', [\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'productStockSearchNameSku'])->name('search-product-name-sku');
            Route::get('purchases/{purchase}/confirm', [\App\Http\Controllers\Api\v100\Admin\PurchaseController::class, 'confirmPurchase'])
            ->name('purchases.confirm');
            //sale
            Route::get('sale-return-creatable-list', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'getSaleReturns']);
            Route::get('sales-return/{sale_id}/create', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'create'])
            ->name('sales-return.create');
            Route::get('sale-return-list', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'index']);
            Route::get('sale-return-requests', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'returnRequests']);
            Route::post('sale-return-store', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'storeRequests']);
            Route::get('sale-return-show/{id}', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'show']);
            Route::get('sale-return-request/{id}', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'returnRequestShow'])
             ->name('sale-return-request.show');
             Route::post('sale-return-request/accept/{id}', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'returnRequestAccept'])
            ->name('sale-return-request.accept');
            Route::post('sale-return-request/reject/{id}', [\App\Http\Controllers\Api\v100\Admin\ProductSaleReturnController::class, 'returnRequestReject'])
            ->name('sale-return-request.reject');

//            Route::get('invoice-download/{id}', [\App\Http\Controllers\Api\v100\Admin\InvoiceController::class, 'download']);

            //Report
            Route::get('expense-report', [\App\Http\Controllers\Api\v100\Admin\ReportController::class, 'expenseReport']);
            Route::get('reports/export/expenses', [\App\Http\Controllers\Api\v100\Admin\ReportController::class,'exportExpenses'])->name('reports.export.expenses');

            Route::get('sale-report', [\App\Http\Controllers\Api\v100\Admin\ReportController::class, 'sales']);
            Route::get('reports/export/sales', [\App\Http\Controllers\Api\v100\Admin\ReportController::class,'exportSales'])->name('reports.export.sales');
            Route::get('purchase-report', [\App\Http\Controllers\Api\v100\Admin\ReportController::class, 'purchases']);
            Route::get('reports/export/purchases', [\App\Http\Controllers\Api\v100\Admin\ReportController::class,'exportPurchases'])->name('reports.export.purchases');
            Route::get('payment-report', [\App\Http\Controllers\Api\v100\Admin\ReportController::class, 'payments']);
            Route::get('reports/export/payments',  [\App\Http\Controllers\Api\v100\Admin\ReportController::class,'exportPayments'])->name('reports.export.payments');
            Route::get('warehouse-stock-report', [\App\Http\Controllers\Api\v100\Admin\ReportController::class, 'warehouseStock']);
            Route::get('loss-profit-report', [\App\Http\Controllers\Api\v100\Admin\ReportController::class, 'lossProfit']);

            //Coupon
            Route::post('coupon-apply/{code}',[\App\Http\Controllers\Api\v100\Admin\CouponController::class, 'applyCoupon']);
            Route::apiResource('coupons',Api\v100\Admin\CouponController::class);
            Route::apiResource('coupon-products', Api\v100\Admin\CouponProductController::class);
            Route::get('active-coupon-products',[\App\Http\Controllers\Api\v100\Admin\CouponProductController::class, 'getActiveCouponProducts']);
            Route::apiResource('expenses-categories', Api\v100\Admin\ExpensesCategoriesController::class);
            Route::apiResource('expenses', Api\v100\Admin\ExpensesController::class);
            Route::delete('expenses/file/delete/{file_id}', [\App\Http\Controllers\Api\v100\Admin\ExpensesController::class,'deleteFile'])->name('expenses.deleteFile');

            Route::post('logout', [AuthController::class, 'logout']);
        });
        //Customer
        Route::middleware(['api.checkIsCustomer'])->prefix('customer')->group(function () {
            Route::get('login_user/details', [CustomerAuthController::class, 'loginUserDetails']);
            Route::post('profile-update', [\App\Http\Controllers\Api\v100\Customer\ProfileController::class, 'update']);

            //home page
            Route::get('home-page', [\App\Http\Controllers\Api\v100\Customer\HomePageController::class, 'index']);
            Route::get('top-product', [\App\Http\Controllers\Api\v100\Customer\HomePageController::class, 'getTopProduct']);

            //invoice
            Route::post('invoice-create', [\App\Http\Controllers\Api\v100\Customer\InvoiceController::class, 'create']);
            Route::get('invoice-list', [\App\Http\Controllers\Api\v100\Customer\InvoiceController::class, 'index']);
            Route::get('invoice-details/{id}', [\App\Http\Controllers\Api\v100\Customer\InvoiceController::class, 'show']);
//            Route::get('invoice-download/{id}', [\App\Http\Controllers\Api\v100\Customer\InvoiceController::class, 'download']);

            Route::get('draft-invoice-list', [DraftInvoiceController::class, 'draftInvoiceList']);
            Route::get('draft-invoice-details/{id}', [DraftInvoiceController::class, 'draftInvoiceDetails']);
            Route::delete('delete-draft-invoice/{id}', [DraftInvoiceController::class, 'destroy']);
            Route::post('draft-invoice-create', [DraftInvoiceController::class, 'create']);
            Route::put('draft-invoice-update/{id}', [DraftInvoiceController::class, 'update']);
            Route::post('invoices/store/from-draft/{id}', [DraftInvoiceController::class, 'storeDraftToInvoice']);

            //sale
            Route::get('returnable-invoice-list', [InvoiceReturnController::class, 'index']);
            Route::get('invoice-return-requests', [InvoiceReturnController::class, 'returnRequests']);
            Route::get('invoice-return-request/show/{id}', [InvoiceReturnController::class, 'returnRequestShow']);
            Route::post('products-return-request', [InvoiceReturnController::class, 'storeRequests']);
            Route::get('products-return-request-details/{id}', [InvoiceReturnController::class, 'getRequests']);


            //Report
            Route::get('purchase-report', [\App\Http\Controllers\Api\v100\Customer\ReportController::class, 'purchases']);
            Route::get('payment-report', [\App\Http\Controllers\Api\v100\Customer\ReportController::class, 'payments']);

            //product
            Route::get('stock-wise-products', [\App\Http\Controllers\Api\v100\Admin\ProductController::class, 'warehouseAndStockWiseProducts']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

});
