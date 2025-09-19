<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepotCustomer;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BeneficiariesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.super:Manage Depot Customers');
    }

    /**
     * Display a listing of all customers from all depots.
     */
    public function index()
    {
        // Fetch all customers with depot information using eager loading
        $customers = DepotCustomer::with(['depot:id,depot_type,city,state,address'])
            ->orderBy('family_id')
            ->orderBy('is_family_head', 'desc')
            ->orderBy('name')
            ->get();

        // Calculate basic statistics
        $totalCustomers = DepotCustomer::count();
        $activeCustomers = DepotCustomer::where('status', 'active')->count();
        $inactiveCustomers = DepotCustomer::where('status', 'inactive')->count();
        $totalFamilies = DepotCustomer::distinct('family_id')->count();
        $familyHeads = DepotCustomer::where('is_family_head', true)->count();
        $customersWithRationCards = DepotCustomer::whereNotNull('ration_card_no')
            ->where('ration_card_no', '!=', '')
            ->count();

        // Depot-wise statistics
        $depotStats = DB::table('depot_customers')
            ->join('depots', 'depot_customers.depot_id', '=', 'depots.id')
            ->select(
                'depots.depot_type',
                'depots.city',
                'depots.state',
                DB::raw('COUNT(*) as customer_count'),
                DB::raw('SUM(CASE WHEN ic_depot_customers.status = "active" THEN 1 ELSE 0 END) as active_count'),
                DB::raw('SUM(CASE WHEN ic_depot_customers.is_family_head = 1 THEN 1 ELSE 0 END) as family_head_count')
            )
            ->groupBy('depots.id', 'depots.depot_type', 'depots.city', 'depots.state')
            ->orderBy('customer_count', 'desc')
            ->get();

        $statistics = [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'inactive_customers' => $inactiveCustomers,
            'total_families' => $totalFamilies,
            'family_heads' => $familyHeads,
            'customers_with_ration_cards' => $customersWithRationCards,
            'depot_stats' => $depotStats
        ];
        // dd($statistics);

        return view('admin.beneficiaries.index', compact('customers', 'statistics'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        // Fetch active depots for dropdown
        $depots = Depot::active()
            ->select('id', 'depot_type', 'city', 'state', 'address')
            ->orderBy('depot_type')
            ->orderBy('city')
            ->get();

        return view('admin.beneficiaries.create', compact('depots'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateCustomer($request);
        
        // Ensure is_family_head is set to false if not provided
        if (!isset($validated['is_family_head'])) {
            $validated['is_family_head'] = false;
        }

        DB::transaction(function () use ($validated) {
            // If this is a family head, update any existing head for this family within the selected depot
            if ($validated['is_family_head']) {
                DepotCustomer::where('family_id', $validated['family_id'])
                    ->where('depot_id', $validated['depot_id'])
                    ->where('is_family_head', true)
                    ->update(['is_family_head' => false]);
            }

            DepotCustomer::create($validated);
        });

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(DepotCustomer $beneficiary)
    {
        // Fetch customer with depot information
        $beneficiary->load('depot:id,depot_type,city,state,address');
        
        // Fetch active depots for dropdown
        $depots = Depot::active()
            ->select('id', 'depot_type', 'city', 'state', 'address')
            ->orderBy('depot_type')
            ->orderBy('city')
            ->get();

        return view('admin.beneficiaries.edit', compact('beneficiary', 'depots'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, DepotCustomer $beneficiary)
    {
        // Validate with current record excluded from unique constraints
        $validated = $this->validateCustomer($request, $beneficiary->id);
        
        // Ensure is_family_head is set to false if not provided
        if (!isset($validated['is_family_head'])) {
            $validated['is_family_head'] = false;
        }

        // Store original values for comparison
        $originalDepotId = $beneficiary->depot_id;
        $originalFamilyId = $beneficiary->family_id;
        $originalIsFamilyHead = $beneficiary->is_family_head;

        DB::transaction(function () use ($validated, $beneficiary, $originalDepotId, $originalFamilyId, $originalIsFamilyHead) {
            // Handle family head transitions
            $this->handleFamilyHeadTransitions(
                $beneficiary,
                $validated,
                $originalDepotId,
                $originalFamilyId,
                $originalIsFamilyHead
            );

            // Update the customer
            $beneficiary->update($validated);
        });

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(DepotCustomer $beneficiary)
    {
        try {
            DB::transaction(function () use ($beneficiary) {
                // Store information about the customer being deleted for logging/confirmation
                $customerName = $beneficiary->name;
                $familyId = $beneficiary->family_id;
                $wasFamilyHead = $beneficiary->is_family_head;
                
                // Delete the customer record permanently
                $beneficiary->delete();
                
                // Note: According to requirement 5.5, when deleting a family head,
                // the system SHALL handle the deletion without automatically promoting 
                // another family member to head status. This means we don't need to
                // automatically assign a new family head when a family head is deleted.
            });

            return redirect()->route('admin.beneficiaries.index')
                ->with('success', 'Customer deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.beneficiaries.index')
                ->with('error', 'Failed to delete customer. Please try again.');
        }
    }

    /**
     * Handle family head transitions during customer updates.
     */
    private function handleFamilyHeadTransitions($beneficiary, $validated, $originalDepotId, $originalFamilyId, $originalIsFamilyHead)
    {
        $newDepotId = $validated['depot_id'];
        $newFamilyId = $validated['family_id'];
        $newIsFamilyHead = $validated['is_family_head'];

        // Case 1: Customer is becoming a family head
        if ($newIsFamilyHead && !$originalIsFamilyHead) {
            // Remove family head status from any existing head in the new depot/family combination
            DepotCustomer::where('family_id', $newFamilyId)
                ->where('depot_id', $newDepotId)
                ->where('is_family_head', true)
                ->where('id', '!=', $beneficiary->id)
                ->update(['is_family_head' => false]);
        }

        // Case 2: Customer is no longer a family head (handled automatically by update)
        
        // Case 3: Depot change - handle family head status in both old and new depots
        if ($originalDepotId != $newDepotId) {
            // If customer was a family head and is moving to a different depot
            if ($originalIsFamilyHead) {
                // Remove family head status from any existing head in the new depot/family combination
                if ($newIsFamilyHead) {
                    DepotCustomer::where('family_id', $newFamilyId)
                        ->where('depot_id', $newDepotId)
                        ->where('is_family_head', true)
                        ->where('id', '!=', $beneficiary->id)
                        ->update(['is_family_head' => false]);
                }
            }
        }

        // Case 4: Family ID change - handle family head status in both old and new families
        if ($originalFamilyId != $newFamilyId) {
            // If customer is becoming a family head in the new family
            if ($newIsFamilyHead) {
                // Remove family head status from any existing head in the new depot/family combination
                DepotCustomer::where('family_id', $newFamilyId)
                    ->where('depot_id', $newDepotId)
                    ->where('is_family_head', true)
                    ->where('id', '!=', $beneficiary->id)
                    ->update(['is_family_head' => false]);
            }
        }

        // Case 5: Customer is already a family head and staying as family head
        if ($originalIsFamilyHead && $newIsFamilyHead) {
            // If depot or family changed, ensure no conflicts in the new location
            if ($originalDepotId != $newDepotId || $originalFamilyId != $newFamilyId) {
                DepotCustomer::where('family_id', $newFamilyId)
                    ->where('depot_id', $newDepotId)
                    ->where('is_family_head', true)
                    ->where('id', '!=', $beneficiary->id)
                    ->update(['is_family_head' => false]);
            }
        }
    }

    /**
     * Validate customer data with cross-depot unique constraints.
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
            'depot_id' => 'required|exists:depots,id',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);
    }
}