<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Godown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('contacts')->paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $companyTypes = ['Manufacturer', 'Distributor', 'Retailer', 'Wholesaler'];
        $relations = ['Customer', 'Supplier', 'Both'];
        $categories = ['Adhesives', 'Chemicals', 'Construction', 'Industrial'];
        $zones = ['North', 'South', 'East', 'West', 'Central'];

        return view('companies.create', compact('companyTypes', 'relations', 'categories', 'zones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'relation' => 'required|string',
            'product_category' => 'required|string',
            'zone' => 'required|string',
            'address' => 'required|string',
            'gst' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string',
            'godown_address.*' => 'nullable|string',
            'godown_city.*' => 'nullable|string',
            'godown_pincode.*' => 'nullable|string',
            'sales_executive_name' => 'nullable|string',
            'sales_executive_phone' => 'nullable|string',
            'sales_executive_email' => 'nullable|email',
            'channel_partner_name' => 'nullable|string',
            'channel_partner_phone' => 'nullable|string',
            'channel_partner_email' => 'nullable|email',
            'sales_rep_name' => 'nullable|string',
            'sales_rep_phone' => 'nullable|string',
            'sales_rep_email' => 'nullable|email',
        ]);

        try {
            DB::beginTransaction();

            $company = Company::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'relation' => $validated['relation'],
                'product_category' => $validated['product_category'],
                'zone' => $validated['zone'],
                'address' => $validated['address'],
                'gst' => $validated['gst'],
                'city' => $validated['city'],
                'pincode' => $validated['pincode'],
                'user_id' => auth()->id(),
                'is_active' => true
            ]);

            if ($request->filled('godown_address')) {
                foreach ($request->godown_address as $index => $address) {
                    if (!empty($address)) {
                        $company->godowns()->create([
                            'address' => $address,
                            'city' => $request->godown_city[$index] ?? null,
                            'pincode' => $request->godown_pincode[$index] ?? null,
                        ]);
                    }
                }
            }

            $contacts = [
                [
                    'type' => 'Sales Executive',
                    'name' => $request->sales_executive_name,
                    'phone' => $request->sales_executive_phone,
                    'email' => $request->sales_executive_email,
                ],
                [
                    'type' => 'Channel Partner',
                    'name' => $request->channel_partner_name,
                    'phone' => $request->channel_partner_phone,
                    'email' => $request->channel_partner_email,
                ],
                [
                    'type' => 'Sales Representative',
                    'name' => $request->sales_rep_name,
                    'phone' => $request->sales_rep_phone,
                    'email' => $request->sales_rep_email,
                ]
            ];

            foreach ($contacts as $contact) {
                if (!empty($contact['name'])) {
                    $company->contacts()->create($contact);
                }
            }

            DB::commit();
            return redirect()->route('companies.index')->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Company creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create company.');
        }
    }

        public function show($id)
    {
        $company = Company::with('godowns', 'contacts')->findOrFail($id);

        $salesExecutive = $company->contacts->where('type', 'Sales Executive')->first();
        $channelPartner = $company->contacts->where('type', 'Channel Partner')->first();
        $salesRep = $company->contacts->where('type', 'Sales Representative')->first();

        // Pass the same variables as edit view
        $companyTypes = ['Supplier', 'Distributor', 'Manufacturer', 'Retailer'];
        $relations = ['New', 'Existing', 'Cold'];
        $categories = ['Mineral Water', 'Energy Drink', 'Snacks'];
        $zones = ['North', 'South', 'East', 'West'];

        return view('companies.show', compact(
            'company',
            'salesExecutive',
            'channelPartner',
            'salesRep',
            'companyTypes',
            'relations',
            'categories',
            'zones'
        ));
    }


    public function edit($id)
    {
        $company = Company::with(['godowns', 'contacts'])->findOrFail($id);

        $salesExecutive = $company->contacts->where('type', 'Sales Executive')->first();
        $channelPartner = $company->contacts->where('type', 'Channel Partner')->first();
        $salesRep = $company->contacts->where('type', 'Sales Representative')->first();
    
        $companyTypes = ['Manufacturer', 'Distributor', 'Retailer', 'Wholesaler'];
        $relations = ['Customer', 'Supplier', 'Both'];
        $categories = ['Adhesives', 'Chemicals', 'Construction', 'Industrial'];
        $zones = ['North', 'South', 'East', 'West', 'Central'];

        return view('companies.edit', compact('company', 'companyTypes', 'relations', 'categories', 'zones', 'salesExecutive', 'channelPartner', 'salesRep'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::with(['godowns', 'contacts'])->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'relation' => 'required|string',
            'product_category' => 'required|string',
            'zone' => 'required|string',
            'address' => 'required|string',
            'gst' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string',
           
            'godown_city.*' => 'nullable|string',
            'godown_pincode.*' => 'nullable|string',
            'sales_executive_name' => 'nullable|string',
            'sales_executive_phone' => 'nullable|string',
            'sales_executive_email' => 'nullable|email',
            'channel_partner_name' => 'nullable|string',
            'channel_partner_phone' => 'nullable|string',
            'channel_partner_email' => 'nullable|email',
            'sales_rep_name' => 'nullable|string',
            'sales_rep_phone' => 'nullable|string',
            'sales_rep_email' => 'nullable|email',
        ]);

        try {
            DB::beginTransaction();

            $company->update([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'relation' => $validated['relation'],
                'product_category' => $validated['product_category'],
                'zone' => $validated['zone'],
                'address' => $validated['address'],
                'gst' => $validated['gst'],
                'city' => $validated['city'],
                'pincode' => $validated['pincode'],
            ]);

            // Update godowns
            $company->godowns()->delete();
            if ($request->filled('godown_address')) {
                foreach ($request->godown_address as $index => $address) {
                    if (!empty($address)) {
                        $company->godowns()->create([
                            'address' => $address,
                            'city' => $request->godown_city[$index] ?? null,
                            'pincode' => $request->godown_pincode[$index] ?? null,
                        ]);
                    }
                }
            }

            // Update contacts
            $company->contacts()->delete();

            $contacts = [
                [
                    'type' => 'Sales Executive',
                    'name' => $request->sales_executive_name,
                    'phone' => $request->sales_executive_phone,
                    'email' => $request->sales_executive_email,
                ],
                [
                    'type' => 'Channel Partner',
                    'name' => $request->channel_partner_name,
                    'phone' => $request->channel_partner_phone,
                    'email' => $request->channel_partner_email,
                ],
                [
                    'type' => 'Sales Representative',
                    'name' => $request->sales_rep_name,
                    'phone' => $request->sales_rep_phone,
                    'email' => $request->sales_rep_email,
                ]
            ];

            foreach ($contacts as $contact) {
                if (!empty($contact['name'])) {
                    $company->contacts()->create($contact);
                }
            }

            DB::commit();
            return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Company update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update company.');
        }
    }

    public function destroy($id)
    {
        $company = Company::with('godowns', 'contacts')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Soft delete related godowns and contacts
            $company->godowns()->delete();
            $company->contacts()->delete();

            // Soft delete company
            $company->delete();

            DB::commit();

            return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Company delete error: ' . $e->getMessage());
            return redirect()->route('companies.index')->with('error', 'Failed to delete company.');
        }
    }

}
