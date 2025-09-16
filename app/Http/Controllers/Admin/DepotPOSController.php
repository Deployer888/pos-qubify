<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\DepotCustomer;
use App\Models\DepotSale;
use App\Models\DepotStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepotPOSController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.super:Access Depot POS');
    }

    /**
     * Show the POS interface.
     */
    public function index(Depot $depot)
    {
        $stocks = $depot->stocks()
            ->where('current_stock', '>', 0)
            ->orderBy('product_name')
            ->get()
            ->map(function($stock) {
                return [
                    'id' => $stock->id,
                    'product_name' => $stock->product_name,
                    'current_stock' => (float)$stock->current_stock,
                    'price' => (float)$stock->price,
                    'customer_price' => (float)$stock->customer_price,
                    'measurement_unit' => $stock->measurement_unit,
                    'barcode' => $stock->barcode,
                    'barcode_image' => $stock->barcode_image
                ];
            });

        return view('admin.depots.pos.index', compact('depot', 'stocks'));
    }

    /**
     * Get family members by family ID.
     */
    public function getFamilyMembers(Request $request, Depot $depot)
    {
        try {
            $validated = $request->validate([
                'family_id' => 'required|string|max:50'
            ]);

            $members = $depot->customers()
                ->where('family_id', $validated['family_id'])
                ->where('status', 'active')
                ->select('id', 'name', 'family_id', 'card_range', 'is_family_head')
                ->get();

            return response()->json($members);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch family members'], 422);
        }
    }

    /**
     * Get stock details.
     */
    public function getStockDetails(Request $request, Depot $depot)
    {
        $request->validate([
            'stock_id' => 'required|exists:depot_stocks,id'
        ]);

        $stock = DepotStock::findOrFail($request->stock_id);
        
        return response()->json($stock);
    }

    /**
     * Search product by barcode.
     */
    public function searchByBarcode(Request $request, Depot $depot)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $stock = $depot->stocks()
            ->where('barcode', $request->barcode)
            ->where('current_stock', '>', 0)
            ->first();

        if (!$stock) {
            return response()->json(['error' => 'Product not found or out of stock'], 404);
        }

        return response()->json([
            'id' => $stock->id,
            'product_name' => $stock->product_name,
            'current_stock' => (float)$stock->current_stock,
            'price' => (float)$stock->price,
            'customer_price' => (float)$stock->customer_price,
            'measurement_unit' => $stock->measurement_unit,
            'barcode' => $stock->barcode
        ]);
    }

    /**
     * Process the sale.
     */
    public function processSale(Request $request, Depot $depot)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:depot_customers,id',
            'items' => 'required|array|min:1',
            'items.*.stock_id' => 'required|exists:depot_stocks,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        try {
            return DB::transaction(function () use ($validated, $depot, $request) {
                // Generate unique invoice number with barcode
                $invoiceNo = $this->generateInvoiceNumber($depot);
                
                // Create sale
                $sale = DepotSale::create([
                    'depot_id' => $depot->id,
                    'depot_customer_id' => $validated['customer_id'],
                    'invoice_no' => $invoiceNo,
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'note' => $request->input('note', '') // Default to empty string if not provided
                ]);

                $subtotal = 0;

                // Process each item
                foreach ($validated['items'] as $item) {
                    $stock = DepotStock::findOrFail($item['stock_id']);
                    
                    // Check stock availability
                    if ($stock->current_stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$stock->product_name}");
                    }

                    // Create sale item
                    $total = $item['quantity'] * $item['price'];
                    $sale->items()->create([
                        'depot_stock_id' => $item['stock_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $total
                    ]);

                    // Update stock
                    $stock->adjustStock($item['quantity'], 'Subtract');
                    
                    $subtotal += $total;
                }

                // Update sale totals
                $sale->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal // No tax for now, can be added if needed
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Sale completed successfully',
                    'invoice_no' => $sale->invoice_no,
                    'sale_id' => $sale->id
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Show the invoice.
     */
    public function showInvoice(Depot $depot, DepotSale $sale)
    {
        $sale->load(['items.stock', 'customer']);
        
        return view('admin.depots.pos.invoice', compact('depot', 'sale'));
    }

    /**
     * Print-friendly invoice (thermal/fair price receipt style)
     */
    public function printInvoice(Depot $depot, DepotSale $sale)
    {
        $sale->load(['items.stock', 'customer']);
        return view('admin.depots.pos.print', compact('depot', 'sale'));
    }

    /**
     * Generate unique invoice number with proper format.
     */
    private function generateInvoiceNumber(Depot $depot): string
    {
        $prefix = 'INV-' . $depot->id . '-';
        $date = now()->format('Ymd');
        
        // Get the last invoice number for today
        $lastInvoice = DepotSale::where('depot_id', $depot->id)
            ->where('invoice_no', 'like', $prefix . $date . '%')
            ->orderBy('invoice_no', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastInvoice->invoice_no, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . $date . '-' . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get daily sales report.
     */
    public function dailySalesReport(Depot $depot)
    {
        $today = now()->startOfDay();
        
        $sales = $depot->sales()
            ->with(['items.stock', 'customer'])
            ->where('created_at', '>=', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('total'),
            'total_items_sold' => $sales->sum(function($sale) {
                return $sale->items->sum('quantity');
            })
        ];

        return view('admin.depots.pos.daily-report', compact('depot', 'sales', 'summary'));
    }

    /**
     * Hold current transaction.
     */
    public function holdTransaction(Request $request, Depot $depot)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:depot_customers,id',
            'items' => 'required|array|min:1',
            'items.*.stock_id' => 'required|exists:depot_stocks,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        try {
            // Store held transaction in session or temporary table
            $holdData = [
                'depot_id' => $depot->id,
                'customer_id' => $validated['customer_id'] ?? null,
                'items' => $validated['items'],
                'note' => $validated['note'] ?? '',
                'created_at' => now()
            ];

            // Store in session for now (you can create a HeldTransaction model later)
            session()->push('held_transactions', $holdData);

            return response()->json([
                'success' => true,
                'message' => 'Transaction held successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to hold transaction'
            ], 422);
        }
    }

    /**
     * Get held transactions.
     */
    public function getHeldTransactions(Depot $depot)
    {
        $heldTransactions = session()->get('held_transactions', []);
        
        // Filter transactions for current depot
        $depotTransactions = array_filter($heldTransactions, function($transaction) use ($depot) {
            return $transaction['depot_id'] == $depot->id;
        });

        return response()->json(array_values($depotTransactions));
    }
}