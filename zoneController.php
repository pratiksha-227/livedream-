<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $minArea = $request->input('min_area');
        $maxArea = $request->input('max_area');
    
        // Start a query builder
        $zones = Zone::query();
    
        if ($search) {
            $zones->where('name', 'like', '%' . $search . '%');
        }
    
        if ($status) {
            $zones->where('status', $status);
        }
    
        if ($minArea) {
            $zones->where('area', '>=', $minArea);
        }
    
        if ($maxArea) {
            $zones->where('area', '<=', $maxArea);
        }
    
        // Execute the query and get the results
        $zones = $zones->get();
    
        return view('zones.index', compact('zones'));
    }

    public function create()
    {
        return view('zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|string',
            'base_price_multiplier' => 'required|numeric|min:0',
            'minimum_price' => 'nullable|numeric|min:0',
            'maximum_price' => 'nullable|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'apply_tax' => 'boolean',
            'tax_percentage' => 'required|numeric|min:0|max:100'
        ]);

        Zone::create([
            'name' => $request->name,
            'area' => $request->area,
            'user_id' => Auth::user()->id,
            'base_price_multiplier' => $request->base_price_multiplier,
            'minimum_price' => $request->minimum_price,
            'maximum_price' => $request->maximum_price,
            'shipping_cost' => $request->shipping_cost,
            'apply_tax' => $request->apply_tax ?? false,
            'tax_percentage' => $request->tax_percentage
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone created successfully.');
    }

    public function show($id)
    {
        $zone = Zone::findOrFail($id);
        return view('zones.show', compact('zone'));
    }

    public function edit($id)
    {
        $zone = Zone::findOrFail($id);
        return view('zones.edit', compact('zone'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|string',
            'base_price_multiplier' => 'required|numeric|min:0',
            'minimum_price' => 'nullable|numeric|min:0',
            'maximum_price' => 'nullable|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'apply_tax' => 'boolean',
            'tax_percentage' => 'required|numeric|min:0|max:100'
        ]);

        $zone = Zone::findOrFail($id);
        $zone->update([
            'name' => $request->name,
            'area' => $request->area,
            'user_id' => Auth::user()->id,
            'base_price_multiplier' => $request->base_price_multiplier,
            'minimum_price' => $request->minimum_price,
            'maximum_price' => $request->maximum_price,
            'shipping_cost' => $request->shipping_cost,
            'apply_tax' => $request->apply_tax ?? false,
            'tax_percentage' => $request->tax_percentage
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();

        return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        Zone::whereIn('id', $ids)->delete();
        return redirect()->route('zones.index')->with('success', 'Zones deleted successfully.');
    }
}