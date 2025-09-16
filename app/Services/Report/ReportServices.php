<?php

namespace App\Services\Report;

use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PurchaseItemReceive;

class ReportServices
{
    public function lossProfitCalculation($request)
    {
//        $products = Product::query()->pluck('id')->toArray();

        //check if product has stock then select product id to array
        $products = Product::query()
            ->whereHas('stock')
            ->pluck('id')
            ->toArray();

        $stocks = ProductStock::query()
            ->whereIn('product_id', $products)
            ->get()
            ->groupBy('product_id')
            ->toArray();

        $invoiceUniqueItems = InvoiceItem::query()
            ->when($request->warehouse, function ($q) use ($request) {
                $q->whereHas('invoice', function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse);
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereHas('invoice', function ($q) use ($request) {
                    $q->whereBetween('date', [$request->from_date, $request->to_date]);
                });
            })
            ->distinct()
            ->select('product_id')
            ->pluck('product_id')
            ->toArray();

        $salesItems = InvoiceItem::query()
            ->when($request->warehouse, function ($q) use ($request) {
                $q->whereHas('invoice', function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse);
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereHas('invoice', function ($q) use ($request) {
                    $q->whereBetween('date', [$request->from_date, $request->to_date]);
                });
            })
            ->whereIn('product_id', $invoiceUniqueItems)
            ->get()
            ->groupBy('product_id')
            ->toArray();


        $purchaseUniqueItems = PurchaseItemReceive::query()
            ->when($request->warehouse, function ($q) use ($request) {
                $q->whereHas('purchaseReceive', function ($q) use ($request) {
                    $q->whereHas('purchase', function ($q) use ($request) {
                        $q->where('warehouse_id', $request->warehouse);
                    });
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereHas('purchaseReceive', function ($q) use ($request) {
                    $q->whereBetween('receive_date', [$request->from_date, $request->to_date]);
                });
            })
            ->distinct()
            ->select('product_id')
            ->pluck('product_id')
            ->toArray();


        $purchaseReceiveItems = PurchaseItemReceive::query()
            ->when($request->warehouse, function ($q) use ($request) {
                $q->whereHas('purchaseReceive', function ($q) use ($request) {
                    $q->whereHas('purchase', function ($q) use ($request) {
                        $q->where('warehouse_id', $request->warehouse);
                    });
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereHas('purchaseReceive', function ($q) use ($request) {
                    $q->whereBetween('receive_date', [$request->from_date, $request->to_date]);
                });
            })
            ->whereIn('product_id', $purchaseUniqueItems)
            ->get()
            ->groupBy('product_id')
            ->toArray();

        $totalSaleQty = 0;
        $totalPurchaseQty = 0;
        $totalPurchaseAmount = 0;
        $totalSalesAmount = 0;
        $saleItemQty = [];
        $saleItemPrice = [];
        $purchaseItemQty = [];
        $purchaseItemPrice = [];

        $saleItemPurchasePrice = [];
        $itemProfit = [];
        $totalProfit = 0;

        foreach ($invoiceUniqueItems as $item) {
            $specificItemQty = 0;
            $specificItemSalePrice = 0;
            foreach ($salesItems[$item] as $salesItem) {
                $totalSaleQty += $salesItem['quantity'];
                $totalSalesAmount += ($salesItem['price'] * $salesItem['quantity']);
                $specificItemQty += $salesItem['quantity'];
                $specificItemSalePrice += ($salesItem['price'] * $salesItem['quantity']);
            }
            $saleItemPrice[$item] = $specificItemSalePrice;
            $saleItemQty[$item] = $specificItemQty;

        }

        foreach ($purchaseUniqueItems as $item) {
            $specificItemPurchaseQty = 0;
            $specificItemPurchasePrice = 0;
            $specificItemAvgPurchasePrice = 0;
            foreach ($purchaseReceiveItems[$item] as $purchaseReceiveItem) {
                $totalPurchaseQty += $purchaseReceiveItem['quantity'];
                $totalPurchaseAmount += ($purchaseReceiveItem['price'] * $purchaseReceiveItem['quantity']);
                $specificItemPurchaseQty += $purchaseReceiveItem['quantity'];
                $specificItemPurchasePrice += ($purchaseReceiveItem['price'] * $purchaseReceiveItem['quantity']);
                $specificItemAvgPurchasePrice = ($specificItemPurchasePrice / $specificItemPurchaseQty);
            }
            $purchaseItemQty[$item] = $specificItemPurchaseQty;
            $purchaseItemPrice[$item] = $specificItemPurchasePrice;
            $saleItemPurchasePrice[$item] = $specificItemAvgPurchasePrice;
            $itemProfit[$item] = array_key_exists($item, $saleItemPrice) && array_key_exists($item, $saleItemQty)
                ? ($saleItemPrice[$item] - ($specificItemAvgPurchasePrice * $saleItemQty[$item])) : 0;

            $totalProfit += array_key_exists($item, $saleItemPrice) && array_key_exists($item, $saleItemQty)
                ? ($saleItemPrice[$item] - ($specificItemAvgPurchasePrice * $saleItemQty[$item])) : 0;
        }

        $totalStockAsAssets = 0;
        foreach ($products as $product){
            foreach ($stocks[$product] as $stock){
                $totalStockAsAssets += $stock['quantity'];
            }
        }


        $totalAssetQty = $totalStockAsAssets;//$totalPurchaseQty - $totalSaleQty;

        return [
            'total_sale_qty'       => $totalSaleQty,
            'total_purchase_qty'   => $totalPurchaseQty,
            'total_asset_qty'      => $totalAssetQty,
            'total_sales_price'    => $totalSalesAmount,
            'total_purchase_price' => $totalPurchaseAmount,
            'total_profit'         => $totalProfit
        ];

    }






    /*
     *It's will be deprecated
     *
     * */

    /*public function lossProfitCalculation($request)
    {

        $invoiceUniqueItems = InvoiceItem::query()
            ->when($request->warehouse, function ($q) use ($request){
                $q->whereHas('invoice', function ($q) use ($request){
                    $q->where('warehouse_id', $request->warehouse);
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request){
                $q->whereHas('invoice', function ($q) use ($request){
                    $q->whereBetween('date', [$request->from_date, $request->to_date]);
                });
            })
            ->distinct()
            ->select('product_id')
            ->pluck('product_id')
            ->toArray();

        $salesItems = InvoiceItem::query()
            ->when($request->warehouse, function ($q) use ($request){
                $q->whereHas('invoice', function ($q) use ($request){
                    $q->where('warehouse_id', $request->warehouse);
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request){
                $q->whereHas('invoice', function ($q) use ($request){
                    $q->whereBetween('date', [$request->from_date, $request->to_date]);
                });
            })
            ->whereIn('product_id', $invoiceUniqueItems)
            ->get()
            ->groupBy('product_id')
            ->toArray();

        $purchaseReceiveItems = PurchaseItemReceive::query()
            ->when($request->warehouse, function ($q) use ($request){
                $q->whereHas('purchaseReceive', function ($q) use ($request){
                    $q->whereHas('purchase', function ($q) use ($request){
                        $q->where('warehouse_id', $request->warehouse);
                    });
                });
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request){
                $q->whereHas('purchaseReceive', function ($q) use ($request){
                    $q->whereBetween('receive_date', [$request->from_date, $request->to_date]);
                });
            })
            ->whereIn('product_id', $invoiceUniqueItems)
            ->get()
            ->groupBy('product_id')
            ->toArray();

        $totalSaleQty = 0;
        $totalPurchaseQty = 0;
        $totalPurchaseAmount = 0;
        $totalSalesAmount = 0;
        $saleItemQty = [];
        $saleItemPrice = [];
        $purchaseItemQty = [];
        $purchaseItemPrice = [];

        $saleItemPurchasePrice = [];
        $itemProfit = [];
        $totalProfit = 0;

        foreach ($invoiceUniqueItems as $item){
            $specificItemQty = 0;
            $specificItemSalePrice = 0;
            foreach ($salesItems[$item] as $salesItem){
                $totalSaleQty += $salesItem['quantity'];
                $totalSalesAmount += ($salesItem['price'] * $salesItem['quantity']);
                $specificItemQty += $salesItem['quantity'];
                $specificItemSalePrice += ($salesItem['price'] * $salesItem['quantity']);
            }
            $saleItemPrice[$item] = $specificItemSalePrice;
            $saleItemQty[$item] = $specificItemQty;

            $specificItemPurchaseQty = 0;
            $specificItemPurchasePrice = 0;
            $specificItemAvgPurchasePrice = 0;
            foreach ($purchaseReceiveItems[$item] as $purchaseReceiveItem){
                $totalPurchaseQty += $purchaseReceiveItem['quantity'];
                $totalPurchaseAmount += ($purchaseReceiveItem['price'] * $purchaseReceiveItem['quantity']);
                $specificItemPurchaseQty += $purchaseReceiveItem['quantity'];
                $specificItemPurchasePrice += ($purchaseReceiveItem['price'] * $purchaseReceiveItem['quantity']);
                $specificItemAvgPurchasePrice = ($specificItemPurchasePrice/$specificItemPurchaseQty);
            }
            $purchaseItemQty[$item] = $specificItemPurchaseQty;
            $purchaseItemPrice[$item] = $specificItemPurchasePrice;
            $saleItemPurchasePrice[$item] = $specificItemAvgPurchasePrice;
            $itemProfit[$item] = ($specificItemSalePrice - ($specificItemAvgPurchasePrice * $specificItemQty));
            $totalProfit += ($specificItemSalePrice - ($specificItemAvgPurchasePrice * $specificItemQty));
        }

        $totalAssetQty = $totalPurchaseQty - $totalSaleQty;

        dd($saleItemQty, $saleItemPrice, $purchaseItemQty, $purchaseItemPrice, $saleItemPurchasePrice, $itemProfit, $totalAssetQty, $totalProfit);

        return [
            'total_sale_qty' => $totalSaleQty,
            'total_purchase_qty' => $totalPurchaseQty,
            'total_asset_qty' => $totalAssetQty,
            'total_sales_price' => $totalSalesAmount,
            'total_purchase_price' => $totalPurchaseAmount,
            'total_profit' => $totalProfit
        ];

    }*/
}
