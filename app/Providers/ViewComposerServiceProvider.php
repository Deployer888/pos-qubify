<?php

namespace App\Providers;

use App\Models\SaleReturnRequest;
use App\Services\Product\ProductService;
use App\Services\Sale\SaleReturnRequestServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV')){

            if ($this->checkDatabaseExist() && $this->checkProductTableExist() && $this->checkSaleReturnRequestsTableExist()){

                $lowStockProduct = resolve(ProductService::class)->lowStockList();
                $total_pending_req = resolve(SaleReturnRequestServices::class)->getPendingReturnRequestCount();

                View::composer('*', function ($view) use ($lowStockProduct, $total_pending_req) {

                    $view->with('lowStockProduct', $lowStockProduct);
                    $view->with('total_pending_req', $total_pending_req);

                });
            }
        }
    }

    public function checkDatabaseExist()
    {
        try {

            if (DB::connection()->getPdo() && DB::connection()->getDatabaseName()){
                return true;
            }

        }catch (\Exception $e){

        }
    }

    public function checkProductTableExist()
    {
        try {

            if (Schema::hasTable('products')){
                return true;
            }

        }catch (\Exception $e){

        }
    }
    public function checkSaleReturnRequestsTableExist()
    {
        try {

            if (Schema::hasTable('sale_return_requests')){
                return true;
            }

        }catch (\Exception $e){

        }
    }

}
