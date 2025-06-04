<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    //


    public function create()
    {
        // $companies = Company::select('id', 'name')->get();
        // $sample = Sample::select('id', 'name')->get();
      
        return view('quotations.create_quotation');
    }

    public function newquotation()
    {
        // $companies = Company::select('id', 'name')->get();
        // $sample = Sample::select('id', 'name')->get();
      
        return view('quotations.new_quotation');
    }

    public function productList()
    {
        return view('quotations.product_list');
    }

    public function productDetails()
    {
        return view('quotations.product_details');
    }

    public function productCart()
    {
        return view('quotations.product_cart');
    }

    public function clientDetails()
    {
        return view('quotations.client_details');
    }
    

    public function quotationsummary()
    {
        return view('quotations.quatations_summary');
    }


    public function paymentsterms()
    {
        return view('quotations.payment_terms');
    }

    public function advancedpayment()
    {
        return view('quotations.advanced_payment');
    }



}
