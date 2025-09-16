<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\DepotCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DepotCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.super:Manage Depot Customers');
        $this->middleware('permission.super:Import Depot Customers')->only(['importForm', 'import']);
    }

    /**
     * Display customers for a specific depot.
     */
    public function index(Depot $depot)
    {
        $customers = $depot->customers()
            ->orderBy('family_id')
            ->orderBy('is_family_head', 'desc')
            ->paginate(10);

        return view('admin.depots.customers.index', compact('depot', 'customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(Depot $depot)
    {
        return view('admin.depots.customers.create', compact('depot'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request, Depot $depot)
    {
        $validated = $this->validateCustomer($request);
        if(!isset($validated['is_family_head'])){
            $validated['is_family_head'] = false;
        }
        $validated['depot_id'] = $depot->id;

        DB::transaction(function () use ($validated, $depot) {
            // If this is a family head, update any existing head for this family
            if ($validated['is_family_head']) {
                $depot->customers()
                    ->where('family_id', $validated['family_id'])
                    ->where('is_family_head', true)
                    ->update(['is_family_head' => false]);
            }

            DepotCustomer::create($validated);
        });

        return redirect()
            ->route('admin.depots.customers.index', $depot)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Depot $depot, DepotCustomer $customer)
    {
        return view('admin.depots.customers.edit', compact('depot', 'customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Depot $depot, DepotCustomer $customer)
    {
        $validated = $this->validateCustomer($request, $customer->id);

        DB::transaction(function () use ($validated, $depot, $customer) {
            // If this is becoming a family head, update any existing head
            if ($validated['is_family_head'] && !$customer->is_family_head) {
                $depot->customers()
                    ->where('family_id', $validated['family_id'])
                    ->where('is_family_head', true)
                    ->update(['is_family_head' => false]);
            }

            $customer->update($validated);
        });

        return redirect()
            ->route('admin.depots.customers.index', $depot)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Depot $depot, DepotCustomer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('admin.depots.customers.index', $depot)
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Show import form
     */
    public function importForm(Depot $depot)
    {
        return view('admin.depots.customers.import', compact('depot'));
    }

    /**
     * Import customers from Excel/CSV
     */
    public function import(Request $request, Depot $depot)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new CustomersImport($depot), $request->file('file'));

            return redirect()
                ->route('admin.depots.customers.index', $depot)
                ->with('success', 'Customers imported successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error importing customers: ' . $e->getMessage()]);
        }
    }

    /**
     * Validate customer data
     */
    private function validateCustomer(Request $request, $customerId = null)
    {
        return $request->validate([
            'family_id' => 'required|string|max:255',
            'adhaar_no' => [
                'required',
                'string',
                'size:12',
                Rule::unique('depot_customers')->ignore($customerId)
            ],
            'ration_card_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('depot_customers')->ignore($customerId)
            ],
            'card_range' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'age' => 'required|integer|min:0|max:150',
            'is_family_head' => 'nullable',
            'address' => 'required|string',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);
    }
}
