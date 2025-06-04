<?php

namespace App\Http\Controllers;

use App\Models\Adhesive;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdhesiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search'); // Get the search query from the request

        // Start with a base query
        $adhesivesQuery = Adhesive::query();

        // If a search query exists, apply the filter   
        if ($searchQuery) {
            $adhesivesQuery->where('name', 'like', '%' . $searchQuery . '%');
        }
        $adhesives = $adhesivesQuery->get();
        return view('adhesive.index', compact('adhesives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::select('id','name')->get();
        return view('adhesive.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'purchase_cost' => 'nullable|integer',
            'selling_price' => 'nullable|integer',
           
        ]);

        $data = $request->only(['name', 'quantity', 'purchase_cost', 'selling_price', 'company_id']);
        $data['user_id'] = auth()->id(); // add logged-in user id

        Adhesive::create($data);

        return redirect('/adhesives')->with('success', 'Adhesive created successfully!');
    }


    /**
     * Display the specified resource.
     */
    
     public function show($id)
     {
         $adhesive = Adhesive::with('company')->findOrFail($id);
         $companies = Company::all(); // Add this
     
         return view('adhesive.show', compact('adhesive', 'companies'));
     }
    public function edit( $id)  
    {
      
            $adhesive = Adhesive::findOrFail($id);
            $companies = Company::all();
            return view('adhesive.edit', compact('adhesive', 'companies'));
        
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'quantity' => 'nullable|numeric|min:0',
            'purchase_cost' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);
    
        $data = $request->all();
        $data['purchase_cost'] = $data['purchase_cost'] ?? 0;
        $data['selling_price'] = $data['selling_price'] ?? 0;
    
        $adhesive = Adhesive::findOrFail($id);
        $adhesive->update($data);
    
        return redirect()->route('adhesive.index')->with('success', 'Adhesive updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adhesive = Adhesive::findOrFail($id);
        $adhesive->delete();
    
        return redirect()->route('adhesive.index')->with('success', 'Adhesive deleted successfully!');
    }
    public function bulkDelete(Request $request)
    // {
    //     // Assume $request->ids contains array of IDs to delete
    //     $ids = $request->ids;

    //     if (!$ids || !is_array($ids)) {
    //         return response()->json(['message' => 'Invalid data'], 400);
    //     }

    //     Adhesive::whereIn('id', $ids)->delete();

    //     return response()->json(['message' => 'Selected adhesives deleted successfully']);
    // }
    {
        $ids = $request->input('ids'); // This will be an array of selected adhesive IDs

        // Check if any IDs were provided
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No adhesives selected for deletion.');
        }

        try {
                // Delete adhesives where their IDs are in the provided array
            $deletedCount = Adhesive::whereIn('id', $ids)->delete();

            // Log for debugging (optional)
            Log::info("Bulk delete successful. Deleted {$deletedCount} adhesives.", ['ids' => $ids]);

            return redirect()->back()->with('success', "{$deletedCount} adhesives deleted successfully.");
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Bulk delete failed: " . $e->getMessage(), ['ids' => $ids]);
            return redirect()->back()->with('error', 'An error occurred during bulk deletion: ' . $e->getMessage());
        }
    }   
}
