<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AdhesiveController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/products', [ProductController::class, 'create'])->name('product.create')->middleware(['auth', 'verified']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::get('companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');



    Route::get('/adhesive', [AdhesiveController::class, 'create'])->name('adhesive.create');
    Route::post('/adhesive', [AdhesiveController::class, 'store'])->name('adhesive.store');
    Route::get('/adhesives', [AdhesiveController::class, 'index'])->name('adhesive.index');
Route::get('/adhesive/edit/{id}', [AdhesiveController::class, 'edit'])->name('adhesive.edit');
        
Route::get('/adhesive/{id}', [AdhesiveController::class, 'show'])->name('adhesive.show');




Route::put('/adhesive/{id}', [AdhesiveController::class, 'update'])->name('adhesive.update');

Route::delete('/adhesive/destroy/{id}', [AdhesiveController::class, 'destroy'])->name('adhesive.destroy');
Route::delete('/adhesive/bulk-delete', [AdhesiveController::class, 'bulkDelete'])->name('adhesive.bulk-delete');


    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/show-products', [ProductController::class, 'index'])->name('products.show');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    
    

    Route::get('/edit-product-image/{id}/{product_id}', [ProductController::class, 'editProductImage'])->name('product.image.edit');

    // Product Image Routes
    Route::get('/get-product-image/{id}', [ProductController::class, 'getProductImage']);
    Route::post('/update-product-image/{id}', [ProductController::class, 'updateProductImage']);
    Route::delete('/remove-product-image/{id}', [ProductController::class, 'removeProductImage']);
    
    // Sample Image Routes
    Route::get('/get-sample-image/{id}', [ProductController::class, 'getSampleImage']);
    Route::post('/add-sample-image', [ProductController::class, 'addSampleImage']);
    Route::post('/update-sample-image/{id}', [ProductController::class, 'updateSampleImage']);
    Route::delete('/remove-sample-image/{id}', [ProductController::class, 'removeSampleImage']);

    Route::get('/show-compnies', [CompanyController::class, 'index'])->name('companies.index');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/create-categories', [CategoryController::class, 'create'])->name('category.create');
    Route::get('/index-categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/edit-categories/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/update-categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/delete-categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/show-categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::delete('/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');



    Route::get('/create-sample', [SampleController::class, 'create'])->name('sample.create');
    Route::get('/index-sample', [SampleController::class, 'index'])->name('sample.index');
    // Route::post('/samples', [SampleController::class, 'store'])->name('samples.store');
    Route::get('/samples/{id}', [SampleController::class, 'show'])->name('samples.show');
    Route::get('/samples/{id}/edit', [SampleController::class, 'edit'])->name('samples.edit');
    Route::put('/samples/{id}', [SampleController::class, 'update'])->name('samples.update');
    
    Route::delete('/samples/{id}', [SampleController::class, 'destroy'])->name('samples.destroy');  
    Route::post('/upload-image', [SampleController::class, 'uploadImage'])->name('samples.uploadImage');
    Route::get('/get-samples-by-company/{companyId}', [SampleController::class, 'getSamplesByCompany'])->name('samples.getSamplesByCompany');
    Route::get('/get-sample-by-id/{id}', [SampleController::class, 'getSampleById'])->name('samples.getSampleById');
    Route::get('/get-sample-by-company-id/{companyId}', [SampleController::class, 'getSampleByCompanyId'])->name('samples.getSampleByCompanyId'); 
    Route::post('/sample', [SampleController::class, 'store'])->name('sample.store');
    Route::post('samples/bulk-delete', [SampleController::class, 'bulkDelete'])->name('samples.bulk-delete');    
   

    Route::get('/index-zones', [ZoneController::class, 'index'])->name('zones.index');
    Route::get('/show-zones/{id}', [ZoneController::class, 'show'])->name('zones.show');
    Route::get('/create-zones', [ZoneController::class, 'create'])->name('zones.create');
    Route::post('/zones', [ZoneController::class, 'store'])->name('zones.store');
    Route::get('/zones/{id}/edit', [ZoneController::class, 'edit'])->name('zones.edit');
    Route::get('/zones/{id}', [ZoneController::class, 'show'])->name('zones.show');
    Route::put('zones/{zone}', [ZoneController::class, 'update'])->name('zones.update');
    Route::delete('/zones/{id}', [ZoneController::class, 'destroy'])->name('zones.destroy');
    Route::delete('/zones/bulk-delete', [ZoneController::class, 'bulkDelete'])->name('zones.bulk-delete');
    
 


    Route::get('/create-quotation', [QuotationController::class, 'create'])->name('quotations.create_quotation');
    Route::get('/new-quotation', [QuotationController::class, 'newQuotation'])->name('quotations.new_quotation');
    Route::get('/product-list', [QuotationController::class, 'productlist'])->name('quotations.product_list');
    Route::get('/product-details', [QuotationController::class, 'productdetails'])->name('quotations.product_details');
    Route::get('/product-cart', [QuotationController::class, 'productcart'])->name('quotations.product_cart');
    Route::get('/client-details', [QuotationController::class, 'clientdetails'])->name('quotations.client_details');
    Route::get('/quotation-summary', [QuotationController::class, 'quotationsummary'])->name('quotations.quatations_summary');    
    Route::get('/quotation-terms', [QuotationController::class, 'paymentsterms'])->name('quotations.payment_terms');
    Route::get('/quotation-payment', [QuotationController::class, 'advancedpayment'])->name('quotations.advanced_payment');

    // Route::get('/create-user', [UserController::class, 'createuser'])->name('user.create');
    // Route::post('/users', [UserController::class, 'store'])->name('users.store');




   
    Route::resource('companies', CompanyController::class);

    Route::post('/products/store-form', [ProductController::class, 'storeProductForm'])->name('products.store-form');
    Route::post('/products/store-image', [ProductController::class, 'storeProductImage'])->name('products.store-image');
    Route::post('/products/store-samples', [ProductController::class, 'storeSampleImages'])->name('products.store-samples');
    Route::delete('/remove-product-image/{id}', [ProductController::class, 'removeProductImage']);
    Route::delete('/remove-sample-image/{id}', [ProductController::class, 'removeSampleImage']);
    Route::get('/get-product-image/{id}', [ProductController::class, 'getProductImage']);
    Route::post('/update-product-image/{id}', [ProductController::class, 'updateProductImage']);
    Route::get('/get-sample-image/{id}', [ProductController::class, 'getSampleImage']);
    Route::post('/update-sample-image/{id}', [ProductController::class, 'updateSampleImage']);

    Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/create-user', [UserController::class, 'create'])->name('user.create');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
});

require __DIR__.'/auth.php';
