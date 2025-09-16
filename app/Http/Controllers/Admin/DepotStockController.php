<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\DepotStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;

class DepotStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.super:Manage Depot Stock');
    }

    /**
     * Display stocks for a specific depot.
     */
    public function index(Depot $depot)
    {
        $stocks = $depot->stocks()
            ->orderBy('product_name')
            ->paginate(10);

        return view('admin.depots.stocks.index', compact('depot', 'stocks'));
    }

    /**
     * Show the stock update form.
     */
    public function updateStockForm(Depot $depot, DepotStock $stock = null)
    {
        return view('admin.depots.stocks.update', compact('depot', 'stock'));
    }

    /**
     * Update or create stock for a depot.
     */
    public function updateStock(Request $request, Depot $depot)
    {
        $rules = [
            'product_name' => [
                'required',
                'string',
                'max:255',
            ],
            'measurement_unit' => ['required', Rule::in(['Kg', 'Ltr', 'Piece'])],
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'customer_price' => 'required|numeric|min:0',
            'stock_id' => 'nullable|exists:depot_stocks,id',
            'depot_id' => 'required|integer|exists:depots,id'
        ];

        // Add adjustment_type validation only when updating existing stock
        if ($request->stock_id) {
            $rules['adjustment_type'] = ['required', Rule::in(['Add', 'Subtract'])];
        }
        
        $validated = $request->validate($rules);
        
        try {
            if ($request->stock_id) {
                $stock = DepotStock::findOrFail($request->stock_id);
                
                // Verify depot ownership
                if ($stock->depot_id !== $depot->id) {
                    return back()->withErrors(['stock_id' => 'Invalid stock item for this depot.']);
                }
                
                // Update prices
                $stock->price = $validated['price'];
                $stock->customer_price = $validated['customer_price'];
                
                // Adjust stock
                if (!$stock->adjustStock($validated['quantity'], $validated['adjustment_type'])) {
                    return back()->withErrors([
                        'quantity' => 'Insufficient stock for subtraction.'
                    ])->withInput();
                }
            } else {
                
                // Create new stock
                $stock = new DepotStock([
                    'product_name' => $validated['product_name'],
                    'measurement_unit' => $validated['measurement_unit'],
                    'current_stock' => $validated['quantity'],
                    'price' => $validated['price'],
                    'customer_price' => $validated['customer_price'],
                    'depot_id' => $depot->id
                ]);
                // Generate unique barcode for this stock
                $stock->barcode = $this->generateUniqueBarcode();
                
                // Generate and save barcode image
                $this->generateBarcodeImage($stock);
                
                $stock->save();
            }

            return redirect()
                ->route('admin.depots.stocks.index', $depot)
                ->with('success', 'Stock ' . ($request->stock_id ? 'updated' : 'added') . ' successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Depot stock update error: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Failed to ' . ($request->stock_id ? 'update' : 'add') . ' stock. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Remove stock from depot.
     */
    public function destroy(Depot $depot, DepotStock $stock)
    {
        // Delete barcode image if exists
        if ($stock->barcode_image) {
            Storage::disk(config('filesystems.default'))->delete('depot_barcodes/' . $stock->barcode_image);
        }
        
        $stock->delete();

        return redirect()
            ->route('admin.depots.stocks.index', $depot)
            ->with('success', 'Stock deleted successfully.');
    }

    /**
     * Generate unique barcode for depot stock.
     */
    public function generateUniqueBarcode(): string
    {
        do {
            // Generate barcode: DPT + depot_id + random numbers
            $barcode = 'DPT' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (DepotStock::where('barcode', $barcode)->exists());

        return $barcode;
    }

    /**
     * Generate and save barcode image.
     */
    private function generateBarcodeImage(DepotStock $stock): void
    {
        try {
            // Create DNS1D instance instead of calling statically
            $generator = new DNS1D();
            
            // Generate barcode PNG using instance method
            $barcodePng = $generator->getBarcodePNG($stock->barcode, 'C39', 2, 60);
            
            // Create filename
            $fileName = $stock->barcode . '.png';
            
            // Save to storage
            Storage::disk(config('filesystems.default'))->put(
                'depot_barcodes/' . $fileName, 
                base64_decode($barcodePng)
            );
            
            $stock->barcode_image = $fileName;
        } catch (\Exception $e) {
            \Log::error('Barcode generation failed: ' . $e->getMessage());
            // Don't fail the entire operation if barcode generation fails
        }
    }

    /**
     * Print barcode for stock item.
     */
    public function printBarcode(Depot $depot, DepotStock $stock)
    {
        return view('admin.depots.stocks.barcode', compact('depot', 'stock'));
    }

    /**
     * Bulk print barcodes.
     */
    public function bulkPrintBarcodes(Request $request, Depot $depot)
    {
        $request->validate([
            'stock_ids' => 'required|array',
            'stock_ids.*' => 'exists:depot_stocks,id'
        ]);

        $stocks = DepotStock::whereIn('id', $request->stock_ids)
            ->where('depot_id', $depot->id)
            ->get();

        return view('admin.depots.stocks.bulk-barcode', compact('depot', 'stocks'));
    }
}