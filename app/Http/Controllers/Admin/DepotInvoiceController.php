<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepotSale;
use App\Models\Depot;
use App\Models\DepotCustomer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepotSalesExport;
use App\Models\DepotInvoice;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class DepotInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            // Build base query with basic relationships
            $query = DepotSale::with(['depot', 'customer', 'items'])
                ->latest();

            // Enhanced depot-wise access control
            $userDepots = $this->getUserAccessibleDepots();
            if (!$userDepots->isEmpty()) {
                $query->whereIn('depot_id', $userDepots->pluck('id'));
            }

            // Apply basic filters
            if ($request->filled('depot_id')) {
                $query->where('depot_id', $request->depot_id);
            }

            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);
            }

            if ($request->filled('invoice_no')) {
                $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
            }

            if ($request->filled('customer')) {
                $query->whereHas('customer', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer . '%')
                      ->orWhere('family_id', 'like', '%' . $request->customer . '%')
                      ->orWhere('mobile', 'like', '%' . $request->customer . '%');
                });
            }

            // Get paginated results
            $invoices = $query->paginate(25)->withQueryString();
            
            // Get accessible depots for filter dropdown
            $depots = $userDepots;

            // Calculate basic summary from the current page results
            $allInvoices = $query->get(); // Get all for summary calculation
            $summary = [
                'total_invoices' => $allInvoices->count(),
                'total_amount' => $allInvoices->sum('total'),
                'total_tax' => $allInvoices->sum('tax'),
                'total_items' => $allInvoices->sum(function($invoice) {
                    return $invoice->items->sum('quantity');
                }),
                'average_invoice_value' => $allInvoices->count() > 0 ? $allInvoices->avg('total') : 0,
                'unique_customers' => $allInvoices->pluck('depot_customer_id')->unique()->count(),
                'filter_period' => 'All Time'
            ];

            // Empty depot stats for now
            $depotStats = collect();

            return view('admin.depots.invoices.index', compact(
                'invoices', 
                'depots', 
                'summary', 
                'depotStats'
            ));

        } catch (\Exception $e) {
            Log::error('Error in depot invoices index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load invoices: ' . $e->getMessage());
        }
    }

    public function getDataTablesData(Request $request)
    {
        $query = DepotInvoice::with(['depot.user', 'customer', 'items'])
            ->select('depot_invoices.*');

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('depot_id')) {
            $query->where('depot_id', $request->depot_id);
        }

        if ($request->filled('invoice_no')) {
            $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
        }

        if ($request->filled('customer')) {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%')
                  ->orWhere('mobile', 'like', '%' . $request->customer . '%')
                  ->orWhere('family_id', 'like', '%' . $request->customer . '%');
            });
        }

        return DataTables::of($query)
            ->addColumn('invoice_details', function ($invoice) {
                return view('admin.depots.invoices.partials.invoice-details', compact('invoice'))->render();
            })
            ->addColumn('depot_info', function ($invoice) {
                return view('admin.depots.invoices.partials.depot-info', compact('invoice'))->render();
            })
            ->addColumn('customer_info', function ($invoice) {
                return view('admin.depots.invoices.partials.customer-info', compact('invoice'))->render();
            })
            ->addColumn('items_count', function ($invoice) {
                return '<span class="badge badge-primary badge-pill">' . 
                       $invoice->items->sum('quantity') . 
                       '</span><div class="text-muted small mt-1">items</div>';
            })
            ->addColumn('amount_info', function ($invoice) {
                return view('admin.depots.invoices.partials.amount-info', compact('invoice'))->render();
            })
            ->addColumn('actions', function ($invoice) {
                return view('admin.depots.invoices.partials.actions', compact('invoice'))->render();
            })
            ->rawColumns(['invoice_details', 'depot_info', 'customer_info', 'items_count', 'amount_info', 'actions'])
            ->make(true);
    }

    public function export(Request $request)
    {
        try {
            // Get filtered data using correct DepotSale model
            $query = DepotSale::with(['depot.user', 'customer', 'items.stock']);

            // Enhanced depot-wise access control (same as index method)
            $userDepots = $this->getUserAccessibleDepots();
            if (!$userDepots->isEmpty()) {
                $query->whereIn('depot_id', $userDepots->pluck('id'));
            }

            // Apply the same filters as index
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('depot_id')) {
                $query->where('depot_id', $request->depot_id);
            }

            if ($request->filled('invoice_no')) {
                $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
            }

            if ($request->filled('customer')) {
                $query->whereHas('customer', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer . '%')
                      ->orWhere('mobile', 'like', '%' . $request->customer . '%')
                      ->orWhere('family_id', 'like', '%' . $request->customer . '%');
                });
            }

            $invoices = $query->orderBy('created_at', 'desc')->get();

            // Generate filename with current date and filters
            $filename = 'depot_invoices_' . now()->format('Y-m-d_H-i-s');
            
            if ($request->filled('date_from') || $request->filled('date_to')) {
                $filename .= '_filtered';
            }

            // Export to Excel using correct DepotSalesExport class
            return Excel::download(new DepotSalesExport($invoices), $filename . '.xlsx');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPDF(Request $request)
    {
        try {
            // Get filtered data using correct DepotSale model (same logic as export method)
            $query = DepotSale::with(['depot.user', 'customer', 'items.stock']);

            // Enhanced depot-wise access control (same as index method)
            $userDepots = $this->getUserAccessibleDepots();
            if (!$userDepots->isEmpty()) {
                $query->whereIn('depot_id', $userDepots->pluck('id'));
            }

            // Apply the same filters as index
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('depot_id')) {
                $query->where('depot_id', $request->depot_id);
            }

            if ($request->filled('invoice_no')) {
                $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
            }

            if ($request->filled('customer')) {
                $query->whereHas('customer', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer . '%')
                      ->orWhere('mobile', 'like', '%' . $request->customer . '%')
                      ->orWhere('family_id', 'like', '%' . $request->customer . '%');
                });
            }

            $invoices = $query->orderBy('created_at', 'desc')->get();
            $summary = $this->calculateSummary($query);

            $pdf = PDF::loadView('admin.depots.invoices.report', [
                'invoices' => $invoices,
                'summary' => $summary,
                'filters' => $request->all()
            ]);

            $filename = 'depot_invoices_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportCSV(Request $request)
    {
        try {
            // Get filtered data using correct DepotSale model
            $query = DepotSale::with(['depot.user', 'customer', 'items.stock']);

            // Enhanced depot-wise access control (same as index method)
            $userDepots = $this->getUserAccessibleDepots();
            if (!$userDepots->isEmpty()) {
                $query->whereIn('depot_id', $userDepots->pluck('id'));
            }

            // Apply the same filters as index
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('depot_id')) {
                $query->where('depot_id', $request->depot_id);
            }

            if ($request->filled('invoice_no')) {
                $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
            }

            if ($request->filled('customer')) {
                $query->whereHas('customer', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer . '%')
                      ->orWhere('mobile', 'like', '%' . $request->customer . '%')
                      ->orWhere('family_id', 'like', '%' . $request->customer . '%');
                });
            }

            $invoices = $query->orderBy('created_at', 'desc')->get();

            $filename = 'depot_invoices_' . now()->format('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($invoices) {
                $file = fopen('php://output', 'w');
                
                // Add CSV header with proper escaping
                fputcsv($file, [
                    'Invoice No',
                    'Date',
                    'Time',
                    'Depot Type',
                    'Depot City',
                    'Depot Manager',
                    'Customer Name',
                    'Customer Mobile',
                    'Customer Family ID',
                    'Items Count',
                    'Subtotal',
                    'Tax',
                    'Total Amount'
                ]);

                // Add data rows with proper CSV escaping and formatting
                foreach ($invoices as $invoice) {
                    fputcsv($file, [
                        $invoice->invoice_no ?? '',
                        $invoice->created_at->format('Y-m-d'),
                        $invoice->created_at->format('H:i:s'),
                        $invoice->depot->depot_type ?? '',
                        $invoice->depot->city ?? '',
                        $invoice->depot->user->name ?? '',
                        $invoice->customer->name ?? 'Walk-in Customer',
                        $invoice->customer->mobile ?? '',
                        $invoice->customer->family_id ?? '',
                        $invoice->items->sum('quantity'),
                        number_format($invoice->subtotal, 2),
                        number_format($invoice->tax, 2),
                        number_format($invoice->total, 2)
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'CSV export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateSummary($query)
    {
        // Clone the query to avoid affecting the original
        $summaryQuery = clone $query;
        
        $summary = $summaryQuery->select([
            DB::raw('COUNT(*) as total_invoices'),
            DB::raw('SUM(total) as total_amount'),
            DB::raw('SUM(tax) as total_tax'),
            DB::raw('AVG(total) as average_invoice_value'),
            DB::raw('COUNT(DISTINCT depot_customer_id) as unique_customers')
        ])->first();

        // Get total items sold using correct relationship
        $totalItems = $summaryQuery->withSum('items', 'quantity')->get()->sum('items_sum_quantity');

        return [
            'total_invoices' => $summary->total_invoices ?? 0,
            'total_amount' => $summary->total_amount ?? 0,
            'total_tax' => $summary->total_tax ?? 0,
            'average_invoice_value' => $summary->average_invoice_value ?? 0,
            'unique_customers' => $summary->unique_customers ?? 0,
            'total_items' => $totalItems ?? 0
        ];
    }

    public function show(DepotSale $invoice)
    {
        // Check if user has access to this invoice
        if (!Auth::user()->hasRole('Super Admin')) {
            $userDepots = Depot::where('user_id', Auth::id())->pluck('id');
            if (!$userDepots->contains($invoice->depot_id)) {
                abort(403, 'Unauthorized access to this invoice.');
            }
        }

        $invoice->load([
            'depot.statename', 
            'customer',
            'items.stock'
        ]);

        // Calculate detailed summary
        $summary = [
            'total_items' => $invoice->items->sum('quantity'),
            'unique_products' => $invoice->items->count(),
            'subtotal' => $invoice->subtotal,
            'tax' => $invoice->tax,
            'total' => $invoice->total,
            'payment_status' => $invoice->payment_status ?? 'Paid',
            'created_by' => $invoice->depot->user->name ?? 'System'
        ];

        return view('admin.depots.invoices.show', compact('invoice', 'summary'));
    }

    public function print(DepotSale $invoice)
    {
        // Check if user has access to this invoice
        if (!Auth::user()->hasRole('Super Admin')) {
            $userDepots = Depot::where('user_id', Auth::id())->pluck('id');
            if (!$userDepots->contains($invoice->depot_id)) {
                abort(403, 'Unauthorized access to this invoice.');
            }
        }

        $invoice->load([
            'depot.statename', 
            'customer',
            'items.stock'
        ]);

        $depot = $invoice->depot;
        
        // Calculate print summary
        $summary = [
            'total_items' => $invoice->items->sum('quantity'),
            'subtotal' => $invoice->subtotal,
            'tax' => $invoice->tax,
            'total' => $invoice->total
        ];

        return view('admin.depots.invoices.print', compact('invoice', 'depot', 'summary'));
    }

    /**
     * Display daily report of invoices.
     */
    public function dailyReport(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $depot_id = $request->input('depot_id');

        $query = DepotSale::with(['depot', 'customer', 'items.stock'])
            ->whereDate('created_at', $date);

        // Restrict access based on user role
        if (!Auth::user()->hasRole('Super Admin')) {
            $userDepots = Depot::where('user_id', Auth::id())->pluck('id');
            $query->whereIn('depot_id', $userDepots);
        }

        if ($depot_id) {
            $query->where('depot_id', $depot_id);
        }

        $invoices = $query->latest()->get();

        // Enhanced summary with more metrics
        $summary = [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('total'),
            'total_tax' => $invoices->sum('tax'),
            'total_items' => $invoices->sum(function($invoice) {
                return $invoice->items->sum('quantity');
            }),
            'average_invoice_value' => $invoices->count() > 0 
                ? $invoices->average('total') 
                : 0,
            'highest_invoice' => $invoices->max('total'),
            'lowest_invoice' => $invoices->min('total'),
            'unique_customers' => $invoices->pluck('depot_customer_id')->unique()->count()
        ];

        // Get depots based on user role
        if (Auth::user()->hasRole('Super Admin')) {
            $depots = Depot::active()->orderBy('depot_type')->pluck('depot_type', 'id');
        } else {
            $depots = Depot::where('user_id', Auth::id())->active()->orderBy('depot_type')->pluck('depot_type', 'id');
        }

        return view('admin.depots.invoices.daily-report', compact('invoices', 'summary', 'depots', 'date'));
    }

    /**
     * Export invoices to Excel/CSV
     */
    // public function export(Request $request)
    // {
    //     $query = DepotSale::with(['depot', 'customer', 'items.stock']);

    //     // Apply same filters as index method
    //     if (!Auth::user()->hasRole('Super Admin')) {
    //         $userDepots = Depot::where('user_id', Auth::id())->pluck('id');
    //         $query->whereIn('depot_id', $userDepots);
    //     }

    //     if ($request->filled('depot_id')) {
    //         $query->where('depot_id', $request->depot_id);
    //     }

    //     if ($request->filled('date_from') && $request->filled('date_to')) {
    //         $query->whereBetween('created_at', [
    //             Carbon::parse($request->date_from)->startOfDay(),
    //             Carbon::parse($request->date_to)->endOfDay()
    //         ]);
    //     }

    //     $invoices = $query->get();

    //     // Return CSV response
    //     $filename = 'depot_invoices_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    //     ];

    //     $callback = function() use ($invoices) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, ['Invoice No', 'Date', 'Depot', 'Customer', 'Items', 'Subtotal', 'Tax', 'Total']);

    //         foreach ($invoices as $invoice) {
    //             fputcsv($file, [
    //                 $invoice->invoice_no,
    //                 $invoice->created_at->format('Y-m-d H:i:s'),
    //                 $invoice->depot->depot_type,
    //                 $invoice->customer->name ?? 'Walk-in Customer',
    //                 $invoice->items->sum('quantity'),
    //                 number_format($invoice->subtotal, 2),
    //                 number_format($invoice->tax, 2),
    //                 number_format($invoice->total, 2)
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    /**
     * Get depots accessible to current user
     */
    private function getUserAccessibleDepots()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return Depot::active()
                ->with(['user:id,name', 'statename:id,name'])
                ->orderBy('depot_type')
                ->get();
        }
        
        return Depot::where('user_id', Auth::id())
            ->active()
            ->with(['user:id,name', 'statename:id,name'])
            ->orderBy('depot_type')
            ->get();
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Date range filter with validation
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            
            if ($dateFrom->lte($dateTo)) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
        } elseif ($request->filled('month')) {
            $date = Carbon::parse($request->month . '-01');
            $query->whereYear('created_at', $date->year)
                  ->whereMonth('created_at', $date->month);
        }

        // Depot filter
        if ($request->filled('depot_id')) {
            $query->where('depot_id', $request->depot_id);
        }

        // Enhanced customer search
        if ($request->filled('customer')) {
            $customerSearch = $request->customer;
            $query->whereHas('customer', function($q) use ($customerSearch) {
                $q->where('name', 'like', '%' . $customerSearch . '%')
                  ->orWhere('family_id', 'like', '%' . $customerSearch . '%')
                  ->orWhere('mobile', 'like', '%' . $customerSearch . '%');
            });
        }

        // Invoice number search
        if ($request->filled('invoice_no')) {
            $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
        }

        // Amount range filter
        if ($request->filled('min_amount')) {
            $query->where('total', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('total', '<=', $request->max_amount);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
    }

    /**
     * Calculate comprehensive summary statistics
     */
    // private function calculateSummary($query, Request $request)
    // {
    //     // Clone query for summary calculations
    //     $summaryQuery = clone $query;
    //     $summaryData = $summaryQuery->selectRaw('
    //         COUNT(*) as total_invoices,
    //         SUM(total) as total_amount,
    //         SUM(tax) as total_tax,
    //         SUM(subtotal) as total_subtotal,
    //         AVG(total) as average_invoice_value,
    //         MAX(total) as highest_invoice,
    //         MIN(total) as lowest_invoice
    //     ')->first();

    //     // Calculate total items sold - simplified approach
    //     $totalItems = 0;
    //     try {
    //         $invoicesForItems = $summaryQuery->with('items')->get();
    //         $totalItems = $invoicesForItems->sum(function($invoice) {
    //             return $invoice->items->sum('quantity');
    //         });
    //     } catch (\Exception $e) {
    //         Log::warning('Error calculating total items: ' . $e->getMessage());
    //     }

    //     // Get unique customers count
    //     $uniqueCustomers = 0;
    //     try {
    //         $uniqueCustomers = $summaryQuery->distinct('depot_customer_id')->count('depot_customer_id');
    //     } catch (\Exception $e) {
    //         Log::warning('Error calculating unique customers: ' . $e->getMessage());
    //     }

    //     return [
    //         'total_invoices' => $summaryData->total_invoices ?? 0,
    //         'total_amount' => $summaryData->total_amount ?? 0,
    //         'total_tax' => $summaryData->total_tax ?? 0,
    //         'total_subtotal' => $summaryData->total_subtotal ?? 0,
    //         'total_items' => $totalItems ?? 0,
    //         'average_invoice_value' => $summaryData->average_invoice_value ?? 0,
    //         'highest_invoice' => $summaryData->highest_invoice ?? 0,
    //         'lowest_invoice' => $summaryData->lowest_invoice ?? 0,
    //         'unique_customers' => $uniqueCustomers ?? 0,
    //         'filter_period' => $this->getFilterPeriodText($request)
    //     ];
    // }

    /**
     * Get depot-wise statistics
     */
    private function getDepotWiseStats($depotIds)
    {
        if ($depotIds->isEmpty()) {
            return collect();
        }

        return Depot::whereIn('id', $depotIds)
            ->withCount(['sales as total_invoices'])
            ->withSum('sales', 'total')
            ->withSum('sales', 'tax')
            ->with(['sales' => function($query) {
                $query->selectRaw('depot_id, COUNT(*) as monthly_count, SUM(total) as monthly_total')
                      ->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year)
                      ->groupBy('depot_id');
            }])
            ->get()
            ->map(function($depot) {
                return [
                    'id' => $depot->id,
                    'name' => $depot->depot_type . ' - ' . $depot->city,
                    'total_invoices' => $depot->total_invoices ?? 0,
                    'total_revenue' => $depot->sales_sum_total ?? 0,
                    'total_tax' => $depot->sales_sum_tax ?? 0,
                    'monthly_invoices' => $depot->sales->first()->monthly_count ?? 0,
                    'monthly_revenue' => $depot->sales->first()->monthly_total ?? 0,
                ];
            });
    }

    /**
     * Get human-readable filter period text
     */
    private function getFilterPeriodText(Request $request)
    {
        if ($request->filled('date_from') && $request->filled('date_to')) {
            return Carbon::parse($request->date_from)->format('M d, Y') . ' - ' . 
                   Carbon::parse($request->date_to)->format('M d, Y');
        } elseif ($request->filled('month')) {
            return Carbon::parse($request->month . '-01')->format('F Y');
        }
        
        return 'All Time';
    }

    /**
     * Get invoice analytics for specific depot
     */
    public function depotAnalytics(Request $request, Depot $depot)
    {
        // Check access permissions
        if (!Auth::user()->hasRole('Super Admin') && $depot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this depot.');
        }

        $period = $request->input('period', '30'); // days
        $startDate = now()->subDays($period);

        $analytics = [
            'depot' => $depot,
            'period_days' => $period,
            'total_invoices' => $depot->sales()->where('created_at', '>=', $startDate)->count(),
            'total_revenue' => $depot->sales()->where('created_at', '>=', $startDate)->sum('total'),
            'average_invoice' => $depot->sales()->where('created_at', '>=', $startDate)->avg('total'),
            'top_customers' => $this->getTopCustomers($depot, $startDate),
            'daily_sales' => $this->getDailySales($depot, $startDate),
            'product_performance' => $this->getProductPerformance($depot, $startDate)
        ];

        return response()->json($analytics);
    }

    /**
     * Get top customers for depot
     */
    private function getTopCustomers(Depot $depot, $startDate)
    {
        return DepotCustomer::whereHas('sales', function($query) use ($depot, $startDate) {
                $query->where('depot_id', $depot->id)
                      ->where('created_at', '>=', $startDate);
            })
            ->withCount(['sales as total_purchases' => function($query) use ($depot, $startDate) {
                $query->where('depot_id', $depot->id)
                      ->where('created_at', '>=', $startDate);
            }])
            ->withSum(['sales as total_spent' => function($query) use ($depot, $startDate) {
                $query->where('depot_id', $depot->id)
                      ->where('created_at', '>=', $startDate);
            }], 'total')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get daily sales data for charts
     */
    private function getDailySales(Depot $depot, $startDate)
    {
        return $depot->sales()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as invoices, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get product performance data
     */
    private function getProductPerformance(Depot $depot, $startDate)
    {
        return DB::table('ic_depot_sale_items')
            ->join('ic_depot_sales', 'ic_depot_sale_items.depot_sale_id', '=', 'ic_depot_sales.id')
            ->join('ic_depot_stocks', 'ic_depot_sale_items.depot_stock_id', '=', 'ic_depot_stocks.id')
            ->where('ic_depot_sales.depot_id', $depot->id)
            ->where('ic_depot_sales.created_at', '>=', $startDate)
            ->selectRaw('
                ic_depot_stocks.product_name,
                SUM(ic_depot_sale_items.quantity) as total_quantity,
                SUM(ic_depot_sale_items.quantity * ic_depot_sale_items.price) as total_revenue,
                COUNT(DISTINCT ic_depot_sales.id) as invoice_count
            ')
            ->groupBy('ic_depot_stocks.id', 'ic_depot_stocks.product_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(20)
            ->get();
    }
}
