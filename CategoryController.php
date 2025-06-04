<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {   $searchQuery = $request->input('search'); // Get the search query from the request

        // Start with a base query
        $categoriesQuery = Category::query();

        // If a search query exists, apply the filter
        if ($searchQuery) {
            $categoriesQuery->where('name', 'like', '%' . $searchQuery . '%');
          
        }
        $categories = $categoriesQuery->get();
        return view('categories.index', compact('categories'));
    }
    public function create()
    {
      
        $categories = Category::select('id', 'name')->get();
        return view('categories.create', compact('categories'));

    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
     
        \App\Models\Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return redirect()->route('categories.index')->with('success', 'Category saved successfully!');
    
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids'); // This will be an array of selected category IDs

        // Check if any IDs were provided
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No categories selected for deletion.');
        }

        try {
            // Delete categories where their IDs are in the provided array
            $deletedCount = Category::whereIn('id', $ids)->delete();

            // Log for debugging (optional)
            Log::info("Bulk delete successful. Deleted {$deletedCount} categories.", ['ids' => $ids]);

            return redirect()->back()->with('success', "{$deletedCount} categories deleted successfully.");
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Bulk delete failed: " . $e->getMessage(), ['ids' => $ids]);
            return redirect()->back()->with('error', 'An error occurred during bulk deletion: ' . $e->getMessage());
        }
    }   
    
    
    
    
}
