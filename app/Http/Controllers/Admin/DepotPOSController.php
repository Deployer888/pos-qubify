<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\DepotCustomer;
use App\Models\DepotSale;
use App\Models\DepotStock;
use App\Exceptions\DepotPOSException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DepotPOSController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.super:Access Depot POS');
    }

    /**
     * Show depot selection interface based on user role.
     */
    public function selectDepot(): View|RedirectResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('Unauthenticated user attempted to access depot POS selection');
                abort(401, 'Authentication required to access the POS system.');
            }
            
            // Check if user has Super Admin or Admin role
            if ($user->hasRole(['Super Admin', 'Admin'])) {
                try {
                    // Show all active depots for Super Admin and Admin
                    $depots = Depot::active()
                        ->with('user')
                        ->orderBy('depot_type')
                        ->orderBy('city')
                        ->get();
                    
                    Log::info('Super Admin/Admin accessed depot POS selection', [
                        'user_id' => $user->id,
                        'role' => $user->getRoleNames()->first(),
                        'depots_count' => $depots->count()
                    ]);
                    
                    return view('admin.depots.pos.select-depot', compact('depots'));
                } catch (\Exception $e) {
                    Log::error('Failed to load depots for Super Admin/Admin', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    return redirect()->back()->with('error', 'Unable to load depot information. Please contact system administrator.');
                }
            }
            
            // Check if user has Depot Manager role
            if ($user->hasRole('Depot Manager')) {
                try {
                    // Get depots assigned to this manager
                    $assignedDepots = Depot::where('user_id', $user->id)
                        ->active()
                        ->orderBy('depot_type')
                        ->orderBy('city')
                        ->get();
                    
                    Log::info('Depot Manager accessed POS selection', [
                        'user_id' => $user->id,
                        'assigned_depots_count' => $assignedDepots->count()
                    ]);
                    
                    // If no depots assigned, show no access message
                    if ($assignedDepots->count() === 0) {
                        Log::warning('Depot Manager has no assigned depots', [
                            'user_id' => $user->id,
                            'user_name' => $user->name
                        ]);
                        return view('admin.depots.pos.no-access');
                    }
                    
                    // If only one depot assigned, redirect directly to POS
                    if ($assignedDepots->count() === 1) {
                        $depot = $assignedDepots->first();
                        Log::info('Depot Manager auto-redirected to single depot POS', [
                            'user_id' => $user->id,
                            'depot_id' => $depot->id,
                            'depot_name' => $depot->depot_type
                        ]);
                        return redirect()->route('admin.depots.pos.index', $depot);
                    }
                    
                    // Multiple depots assigned, show selection interface
                    return view('admin.depots.pos.select-depot', ['depots' => $assignedDepots]);
                } catch (\Exception $e) {
                    Log::error('Failed to load assigned depots for Depot Manager', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    return redirect()->back()->with('error', 'Unable to load your assigned depots. Please contact system administrator.');
                }
            }
            
            // User doesn't have required role
            Log::warning('Unauthorized POS access attempt', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_roles' => $user->getRoleNames()->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            abort(403, 'You do not have permission to access the POS system. Required roles: Super Admin, Admin, or Depot Manager.');
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Authorization exception in depot POS selection', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'ip_address' => request()->ip()
            ]);
            abort(403, $e->getMessage());
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Unexpected error in depot POS selection', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Return error view or redirect with error message
            return redirect()->back()->with('error', 'An unexpected error occurred while accessing the POS system. Please try again or contact support.');
        }
    }

    /**
     * Show the POS interface.
     */
    public function index(Depot $depot)
    {
        try {
            $user = Auth::user();
            
            // Validate depot access for the current user
            if (!$this->validateDepotAccess($user, $depot)) {
                Log::warning('Unauthorized depot access attempt', [
                    'user_id' => $user->id,
                    'depot_id' => $depot->id,
                    'user_roles' => $user->getRoleNames()->toArray(),
                    'ip_address' => request()->ip()
                ]);
                abort(403, 'You do not have permission to access this depot\'s POS system.');
            }
            
            // Load stocks with error handling
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

            Log::info('POS interface accessed successfully', [
                'user_id' => $user->id,
                'depot_id' => $depot->id,
                'stocks_count' => $stocks->count()
            ]);

            return view('admin.depots.pos.index', compact('depot', 'stocks'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Depot not found for POS access', [
                'depot_id' => $depot->id ?? 'unknown',
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            abort(404, 'The requested depot was not found.');
        } catch (\Exception $e) {
            Log::error('Error loading POS interface', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.depots.pos.select')->with('error', 'Unable to load POS interface. Please try again.');
        }
    }

    /**
     * Get family members by family ID.
     */
    public function getFamilyMembers(Request $request, Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized family members access attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                return response()->json(['error' => 'Unauthorized access to depot data'], 403);
            }

            $validated = $request->validate([
                'family_id' => 'required|string|max:50'
            ]);

            $members = $depot->customers()
                ->where('family_id', $validated['family_id'])
                ->where('status', 'active')
                ->select('id', 'name', 'family_id', 'card_range', 'is_family_head')
                ->get();

            Log::info('Family members retrieved successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'family_id' => $validated['family_id'],
                'members_count' => $members->count()
            ]);

            return response()->json($members);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Invalid family ID provided', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'validation_errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json(['error' => 'Invalid family ID format'], 422);
        } catch (\Exception $e) {
            Log::error('Error fetching family members', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'family_id' => $request->input('family_id'),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to fetch family members. Please try again.'], 500);
        }
    }

    /**
     * Get stock details.
     */
    public function getStockDetails(Request $request, Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized stock details access attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                return response()->json(['error' => 'Unauthorized access to depot data'], 403);
            }

            $validated = $request->validate([
                'stock_id' => 'required|exists:depot_stocks,id'
            ]);

            $stock = DepotStock::findOrFail($validated['stock_id']);
            
            // Verify stock belongs to the depot
            if ($stock->depot_id !== $depot->id) {
                Log::warning('Attempt to access stock from different depot', [
                    'user_id' => Auth::id(),
                    'requested_depot_id' => $depot->id,
                    'stock_depot_id' => $stock->depot_id,
                    'stock_id' => $stock->id
                ]);
                return response()->json(['error' => 'Stock not found in this depot'], 404);
            }

            Log::info('Stock details retrieved successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'stock_id' => $stock->id,
                'product_name' => $stock->product_name
            ]);
            
            return response()->json($stock);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Invalid stock ID provided', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'validation_errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json(['error' => 'Invalid stock ID'], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Stock not found', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'stock_id' => $request->input('stock_id')
            ]);
            return response()->json(['error' => 'Stock not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching stock details', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'stock_id' => $request->input('stock_id'),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to fetch stock details. Please try again.'], 500);
        }
    }

    /**
     * Search product by barcode.
     */
    public function searchByBarcode(Request $request, Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized barcode search attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                return response()->json(['error' => 'Unauthorized access to depot data'], 403);
            }

            $validated = $request->validate([
                'barcode' => 'required|string|max:255'
            ]);

            $stock = $depot->stocks()
                ->where('barcode', $validated['barcode'])
                ->where('current_stock', '>', 0)
                ->first();

            if (!$stock) {
                Log::info('Product not found by barcode', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'barcode' => $validated['barcode']
                ]);
                return response()->json(['error' => 'Product not found or out of stock'], 404);
            }

            Log::info('Product found by barcode', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'barcode' => $validated['barcode'],
                'product_name' => $stock->product_name,
                'stock_id' => $stock->id
            ]);

            return response()->json([
                'id' => $stock->id,
                'product_name' => $stock->product_name,
                'current_stock' => (float)$stock->current_stock,
                'price' => (float)$stock->price,
                'customer_price' => (float)$stock->customer_price,
                'measurement_unit' => $stock->measurement_unit,
                'barcode' => $stock->barcode
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Invalid barcode provided', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'validation_errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json(['error' => 'Invalid barcode format'], 422);
        } catch (\Exception $e) {
            Log::error('Error searching product by barcode', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'barcode' => $request->input('barcode'),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to search product. Please try again.'], 500);
        }
    }

    /**
     * Process the sale.
     */
    public function processSale(Request $request, Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized sale processing attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                return response()->json(['error' => 'Unauthorized access to process sales'], 403);
            }

            $validated = $request->validate([
                'customer_id' => 'required|exists:depot_customers,id',
                'items' => 'required|array|min:1|max:50', // Limit items to prevent abuse
                'items.*.stock_id' => 'required|exists:depot_stocks,id',
                'items.*.quantity' => 'required|numeric|min:0.01|max:9999',
                'items.*.price' => 'required|numeric|min:0|max:999999',
                'note' => 'nullable|string|max:500'
            ]);

            // Verify customer belongs to depot
            $customer = DepotCustomer::findOrFail($validated['customer_id']);
            if ($customer->depot_id !== $depot->id) {
                Log::warning('Attempt to process sale with customer from different depot', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'customer_depot_id' => $customer->depot_id,
                    'customer_id' => $customer->id
                ]);
                return response()->json(['error' => 'Customer not found in this depot'], 404);
            }

            return DB::transaction(function () use ($validated, $depot, $request) {
                try {
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
                        'note' => $request->input('note', '')
                    ]);

                    $subtotal = 0;
                    $processedItems = [];

                    // Process each item
                    foreach ($validated['items'] as $index => $item) {
                        $stock = DepotStock::findOrFail($item['stock_id']);
                        
                        // Verify stock belongs to depot
                        if ($stock->depot_id !== $depot->id) {
                            throw new DepotPOSException(
                                "Stock item does not belong to this depot",
                                400,
                                null,
                                [
                                    'stock_id' => $stock->id,
                                    'stock_depot_id' => $stock->depot_id,
                                    'requested_depot_id' => $depot->id
                                ],
                                'warning'
                            );
                        }
                        
                        // Check stock availability
                        if ($stock->current_stock < $item['quantity']) {
                            throw new DepotPOSException(
                                "Insufficient stock for {$stock->product_name}. Available: {$stock->current_stock}, Requested: {$item['quantity']}",
                                422,
                                null,
                                [
                                    'stock_id' => $stock->id,
                                    'product_name' => $stock->product_name,
                                    'available_stock' => $stock->current_stock,
                                    'requested_quantity' => $item['quantity']
                                ],
                                'warning'
                            );
                        }

                        // Create sale item
                        $total = $item['quantity'] * $item['price'];
                        $saleItem = $sale->items()->create([
                            'depot_stock_id' => $item['stock_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'total' => $total
                        ]);

                        // Update stock
                        $stock->adjustStock($item['quantity'], 'Subtract');
                        
                        $subtotal += $total;
                        $processedItems[] = [
                            'stock_id' => $stock->id,
                            'product_name' => $stock->product_name,
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'total' => $total
                        ];
                    }

                    // Update sale totals
                    $sale->update([
                        'subtotal' => $subtotal,
                        'total' => $subtotal // No tax for now, can be added if needed
                    ]);

                    Log::info('Sale processed successfully', [
                        'user_id' => Auth::id(),
                        'depot_id' => $depot->id,
                        'sale_id' => $sale->id,
                        'invoice_no' => $sale->invoice_no,
                        'customer_id' => $validated['customer_id'],
                        'total_amount' => $subtotal,
                        'items_count' => count($processedItems)
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Sale completed successfully',
                        'invoice_no' => $sale->invoice_no,
                        'sale_id' => $sale->id,
                        'total' => $subtotal
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('Error during sale transaction', [
                        'user_id' => Auth::id(),
                        'depot_id' => $depot->id,
                        'customer_id' => $validated['customer_id'],
                        'error' => $e->getMessage(),
                        'items' => $validated['items']
                    ]);
                    throw $e;
                }
            });
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Invalid sale data provided', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'validation_errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid sale data provided',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Model not found during sale processing', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Required data not found. Please refresh and try again.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Unexpected error during sale processing', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the sale. Please try again.'
            ], 500);
        }
    }

    /**
     * Show the invoice.
     */
    public function showInvoice(Depot $depot, DepotSale $sale)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized invoice access attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'sale_id' => $sale->id,
                    'ip_address' => request()->ip()
                ]);
                abort(403, 'Unauthorized access to depot invoices');
            }

            // Verify sale belongs to depot
            if ($sale->depot_id !== $depot->id) {
                Log::warning('Attempt to access invoice from different depot', [
                    'user_id' => Auth::id(),
                    'requested_depot_id' => $depot->id,
                    'sale_depot_id' => $sale->depot_id,
                    'sale_id' => $sale->id
                ]);
                abort(404, 'Invoice not found in this depot');
            }

            $sale->load(['items.stock', 'customer']);

            Log::info('Invoice accessed successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'sale_id' => $sale->id,
                'invoice_no' => $sale->invoice_no
            ]);
            
            return view('admin.depots.pos.invoice', compact('depot', 'sale'));
            
        } catch (\Exception $e) {
            Log::error('Error loading invoice', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'sale_id' => $sale->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            return redirect()->route('admin.depots.pos.select')->with('error', 'Unable to load invoice. Please try again.');
        }
    }

    /**
     * Print-friendly invoice (thermal/fair price receipt style)
     */
    public function printInvoice(Depot $depot, DepotSale $sale)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized print invoice access attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'sale_id' => $sale->id,
                    'ip_address' => request()->ip()
                ]);
                abort(403, 'Unauthorized access to depot invoices');
            }

            // Verify sale belongs to depot
            if ($sale->depot_id !== $depot->id) {
                Log::warning('Attempt to print invoice from different depot', [
                    'user_id' => Auth::id(),
                    'requested_depot_id' => $depot->id,
                    'sale_depot_id' => $sale->depot_id,
                    'sale_id' => $sale->id
                ]);
                abort(404, 'Invoice not found in this depot');
            }

            $sale->load(['items.stock', 'customer']);

            Log::info('Print invoice accessed successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'sale_id' => $sale->id,
                'invoice_no' => $sale->invoice_no
            ]);
            
            return view('admin.depots.pos.print', compact('depot', 'sale'));
            
        } catch (\Exception $e) {
            Log::error('Error loading print invoice', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'sale_id' => $sale->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            return redirect()->route('admin.depots.pos.select')->with('error', 'Unable to load print invoice. Please try again.');
        }
    }

    /**
     * Generate unique invoice number with proper format.
     */
    private function generateInvoiceNumber(Depot $depot): string
    {
        try {
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
                
                // Prevent overflow (max 9999 invoices per day per depot)
                if ($newSequence > 9999) {
                    Log::error('Daily invoice limit exceeded', [
                        'depot_id' => $depot->id,
                        'date' => $date,
                        'last_sequence' => $lastSequence
                    ]);
                    throw new \Exception('Daily invoice limit exceeded. Please contact system administrator.');
                }
            } else {
                $newSequence = 1;
            }

            $invoiceNo = $prefix . $date . '-' . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
            
            Log::info('Invoice number generated', [
                'depot_id' => $depot->id,
                'invoice_no' => $invoiceNo,
                'sequence' => $newSequence
            ]);

            return $invoiceNo;
            
        } catch (\Exception $e) {
            Log::error('Error generating invoice number', [
                'depot_id' => $depot->id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to generate invoice number: ' . $e->getMessage());
        }
    }

    /**
     * Get daily sales report.
     */
    public function dailySalesReport(Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized daily sales report access attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                abort(403, 'Unauthorized access to depot reports');
            }

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

            Log::info('Daily sales report accessed successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'report_date' => $today->toDateString(),
                'total_sales' => $summary['total_sales'],
                'total_revenue' => $summary['total_revenue']
            ]);

            return view('admin.depots.pos.daily-report', compact('depot', 'sales', 'summary'));
            
        } catch (\Exception $e) {
            Log::error('Error loading daily sales report', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('admin.depots.pos.select')->with('error', 'Unable to load sales report. Please try again.');
        }
    }

    /**
     * Hold current transaction.
     */
    public function holdTransaction(Request $request, Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized hold transaction attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                return response()->json(['error' => 'Unauthorized access to depot transactions'], 403);
            }

            $validated = $request->validate([
                'customer_id' => 'nullable|exists:depot_customers,id',
                'items' => 'required|array|min:1|max:50',
                'items.*.stock_id' => 'required|exists:depot_stocks,id',
                'items.*.quantity' => 'required|numeric|min:0.01|max:9999',
                'items.*.price' => 'required|numeric|min:0|max:999999',
                'note' => 'nullable|string|max:500'
            ]);

            // Verify customer belongs to depot if provided
            if ($validated['customer_id']) {
                $customer = DepotCustomer::findOrFail($validated['customer_id']);
                if ($customer->depot_id !== $depot->id) {
                    Log::warning('Attempt to hold transaction with customer from different depot', [
                        'user_id' => Auth::id(),
                        'depot_id' => $depot->id,
                        'customer_depot_id' => $customer->depot_id,
                        'customer_id' => $customer->id
                    ]);
                    return response()->json(['error' => 'Customer not found in this depot'], 404);
                }
            }

            // Verify all stocks belong to depot
            foreach ($validated['items'] as $item) {
                $stock = DepotStock::findOrFail($item['stock_id']);
                if ($stock->depot_id !== $depot->id) {
                    Log::warning('Attempt to hold transaction with stock from different depot', [
                        'user_id' => Auth::id(),
                        'depot_id' => $depot->id,
                        'stock_depot_id' => $stock->depot_id,
                        'stock_id' => $stock->id
                    ]);
                    return response()->json(['error' => 'Stock item not found in this depot'], 404);
                }
            }

            // Store held transaction in session or temporary table
            $holdData = [
                'id' => uniqid('hold_', true),
                'depot_id' => $depot->id,
                'user_id' => Auth::id(),
                'customer_id' => $validated['customer_id'] ?? null,
                'items' => $validated['items'],
                'note' => $validated['note'] ?? '',
                'created_at' => now()->toISOString()
            ];

            // Store in session for now (you can create a HeldTransaction model later)
            session()->push('held_transactions', $holdData);

            Log::info('Transaction held successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'hold_id' => $holdData['id'],
                'items_count' => count($validated['items']),
                'customer_id' => $validated['customer_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction held successfully',
                'hold_id' => $holdData['id']
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Invalid hold transaction data provided', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'validation_errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid transaction data provided',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error holding transaction', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to hold transaction. Please try again.'
            ], 500);
        }
    }

    /**
     * Get held transactions.
     */
    public function getHeldTransactions(Depot $depot)
    {
        try {
            // Validate depot access
            if (!$this->validateDepotAccess(Auth::user(), $depot)) {
                Log::warning('Unauthorized held transactions access attempt', [
                    'user_id' => Auth::id(),
                    'depot_id' => $depot->id,
                    'ip_address' => request()->ip()
                ]);
                return response()->json(['error' => 'Unauthorized access to depot transactions'], 403);
            }

            $heldTransactions = session()->get('held_transactions', []);
            
            // Filter transactions for current depot and user
            $depotTransactions = array_filter($heldTransactions, function($transaction) use ($depot) {
                return $transaction['depot_id'] == $depot->id && 
                       $transaction['user_id'] == Auth::id();
            });

            Log::info('Held transactions retrieved successfully', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'transactions_count' => count($depotTransactions)
            ]);

            return response()->json(array_values($depotTransactions));
            
        } catch (\Exception $e) {
            Log::error('Error retrieving held transactions', [
                'user_id' => Auth::id(),
                'depot_id' => $depot->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to retrieve held transactions'], 500);
        }
    }

    /**
     * Validate if user has access to the specified depot.
     */
    private function validateDepotAccess($user, Depot $depot): bool
    {
        if (!$user) {
            return false;
        }

        // Super Admin and Admin have access to all depots
        if ($user->hasRole(['Super Admin', 'Admin'])) {
            return true;
        }

        // Depot Manager only has access to assigned depots
        if ($user->hasRole('Depot Manager')) {
            return $depot->user_id === $user->id;
        }

        return false;
    }

    /**
     * Handle and log security violations.
     */
    private function handleSecurityViolation(string $action, array $context = []): void
    {
        $securityContext = array_merge([
            'action' => $action,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name,
            'user_roles' => Auth::user()?->getRoleNames()->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString(),
            'session_id' => session()->getId()
        ], $context);

        Log::warning('Security violation detected in Depot POS', $securityContext);

        // You could also trigger additional security measures here:
        // - Send notification to administrators
        // - Increment failed access attempts counter
        // - Temporarily lock account after multiple violations
    }

    /**
     * Create standardized error response for API endpoints.
     */
    private function createErrorResponse(string $message, int $statusCode = 500, array $context = []): \Illuminate\Http\JsonResponse
    {
        Log::error('Depot POS API Error', array_merge([
            'message' => $message,
            'status_code' => $statusCode,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'endpoint' => request()->fullUrl(),
            'method' => request()->method()
        ], $context));

        return response()->json([
            'success' => false,
            'error' => $message,
            'timestamp' => now()->toISOString()
        ], $statusCode);
    }
}