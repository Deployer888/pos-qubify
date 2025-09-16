<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepotController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.super:Show Depot List')->only(['index']);
        $this->middleware('permission.super:Add Depot')->only(['create', 'store']);
        $this->middleware('permission.super:Edit Depot')->only(['edit', 'update']);
        $this->middleware('permission.super:Delete Depot')->only(['destroy']);
    }

    /**
     * Display a listing of the depots.
     */
    public function index()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            $depots = Depot::with(['user', 'statename'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }else{
            $depots = Depot::with(['user', 'statename'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.depots.index', compact('depots'));
    }

    /**
     * Show the form for creating a new depot.
     */
    public function create()
    {
        $users = \App\Models\User::role('Depot Manager')
            ->where('status', 'active')
            ->get();
            
        $states = \App\Models\State::where('country_id', 101)->get();
        
        return view('admin.depots.create', compact('users', 'states'));
    }
    
    public function getCities($stateId)
    {
        $cities = \App\Models\City::where('state_id', $stateId)->get();
        return response()->json($cities);
    }

    /**
     * Store a newly created depot in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'depot_type' => ['required', 'string', 'max:255', Rule::in(['Ward', 'Panchayat'])],
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        $validated['user_id'] = Auth::id();

        Depot::create($validated);

        return redirect()
            ->route('admin.depots.index')
            ->with('success', 'Depot created successfully.');
    }

    /**
     * Display the specified depot.
     */
    public function show(Depot $depot)
    {
        $depot->load(['user', 'stocks', 'customers']);
        
        return view('admin.depots.show', compact('depot'));
    }

    /**
     * Show the form for editing the specified depot.
     */
    public function edit(Depot $depot)
    {
        $users = \App\Models\User::role('Depot Manager')
            ->where('status', 'active')
            ->get();
            
        $states = \App\Models\State::where('country_id', 101)->get();

        return view('admin.depots.edit', compact('depot', 'users', 'states'));
    }

    /**
     * Update the specified depot in storage.
     */
    public function update(Request $request, Depot $depot)
    {
        $validated = $request->validate([
            'depot_type' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        $depot->update($validated);

        return redirect()
            ->route('admin.depots.index')
            ->with('success', 'Depot updated successfully.');
    }

    /**
     * Remove the specified depot from storage.
     */
    public function destroy(Depot $depot)
    {
        $depot->delete();

        return redirect()
            ->route('admin.depots.index')
            ->with('success', 'Depot deleted successfully.');
    }
}
