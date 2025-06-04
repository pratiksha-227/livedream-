<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sample;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;


class SampleController extends Controller
{
    //
    public function index()
    {
        $search = request('search');  
        $company = request('company');
        $product = request('product');  

        $query = Sample::query();

        if ($search) {
            $query->where('sample_name', 'like', '%' . $search . '%');
        }

        if ($company) {
            $query->whereHas('company', function ($query) use ($company) {
                $query->where('name', 'like', '%' . $company . '%');
            });
        }

        if ($product) {
            $query->where('product_name', 'like', '%' . $product . '%');
        }

        $samples = $query->get();
        $companies = Company::all();
        return view('sample.index', compact('samples', 'companies'));

    }       



    public function create()
    {
        // $companies = Company::select('id', 'name')->get();
        // $sample = Sample::select('id', 'name')->get();
        $companies = Company::all();
      
        return view('sample.create', compact('companies') );
    }


    public function store(Request $request)
    {
        // Validate data
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'sample_name' => 'required|string|max:255',
            'sample_cost' => 'required|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'thickness' => 'nullable|numeric',
            'product_image' => 'nullable|image|mimes:jpg,png,webp|max:10240', // 10MB
        ]);
        
        $sample = new Sample();
        $sample->company_id = $request->company_id;
        $sample->sample_name = $request->sample_name;
        $sample->sample_cost = $request->sample_cost;
        $sample->length = $request->length;
        $sample->width = $request->width;
        $sample->thickness = $request->thickness;   



        // Handle file upload
         if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/sample_images', $filename);

            $sample->image_path = $filename;
            // store filename in DB
        }


        // Save to database
        $sample->save();

        return redirect()->route('sample.index')->with('success', 'Sample created successfully.');
    }


    public function show($id)
    {
        $sample = Sample::findOrFail($id);
        return view('sample.show', compact('sample'));
    }


    public function edit($id)
    {
        $sample = Sample::findOrFail($id);
        $companies = Company::all();

        return view('sample.edit', compact('sample', 'companies'));
    }


    public function update(Request $request, $id)
    {
        $sample = Sample::findOrFail($id); // ✅ Fetch existing record

        $sample->company_id = $request->company_id;
        $sample->sample_name = $request->sample_name;
        $sample->sample_cost = $request->sample_cost;
        $sample->length = $request->length;
        $sample->width = $request->width;
        $sample->thickness = $request->thickness;

        // Image upload if new file is provided
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/sample_images', $imageName);
            $sample->image_path = $imageName;
        }

        $sample->save(); // ✅ This will update the existing row
        return redirect()->route('sample.index')->with('success', 'Sample updated successfully!');
    }
 
    public function destroy($id)
    {
        $sample = Sample::findOrFail($id);
        $sample->delete();
    
        return redirect()->route('sample.index')->with('success', 'Sample deleted successfully.');
    }
    public function bulkDelete(Request $request)
    {
        try {
            $selectedSamples = $request->input('selected_samples', []);
            
            if (empty($selectedSamples)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No samples selected for deletion'
                ]);
            }

            // Delete the selected samples
            $deletedCount = Sample::whereIn('id', $selectedSamples)->delete();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' samples deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Sample bulk delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting samples: ' . $e->getMessage()
            ], 500);
        }
    }
                                 

   
   
    

}
