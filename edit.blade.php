@extends('layouts.app')
@section('content')

<div class="content">


    


    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')

        <input type="hidden" name="product_detail_id" value="{{ $product->id }}">

        <div class="d-flex flex-row justify-content-between align-items-center mb-4">
            <h2 class="text-primary mb-0">Edit Product</h2>
            <div>
                <a href="{{ route('products.show') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>

        <div class="row tab-column">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="m-3">
                        <h5 class="text-primary mb-4">Product Details</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Company<span class="text-danger">*</span></label>
                            <select name="company_id" class="form-select" required>
                                <option disabled>Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ $company->id == $product->company_id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category<span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Application Area</label>
                            <input type="text" name="application_area" class="form-control" value="{{ old('application_area', $product->application_area) }}">
                        </div>

                        <h5 class="text-primary mb-3">Product Size</h5>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="form-label">Length</label>
                                <input type="number" name="length" class="form-control" value="{{ old('length', $product->length ?? 0) }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Width</label>
                                <input type="number" name="width" class="form-control" value="{{ old('width', $product->width ?? 0) }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Thickness</label>
                                <input type="number" name="thickness" class="form-control" value="{{ old('thickness', $product->thickness ?? 0) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Unit</label>
                                <select name="unit" class="form-select">
                                    @foreach(['ft'=>'Foot','m'=>'Meter','cm'=>'Centimeter','mm'=>'Millimeter','inch'=>'Inch'] as $code => $label)
                                        <option value="{{ $code }}" {{ $product->unit == $code ? 'selected' : '' }}>
                                            {{ $label }} ({{ $code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Add Parameter</label>
                                <button type="button" id="add-size" class="btn btn-outline-primary w-100" onclick="addParameter()">
                                    <i class="fas fa-plus me-2"></i>Add Parameter
                                </button>
                                <div id="size-container" class="mt-3">
                                    @if(isset($product->other_parameters))
                                        @php
                                            $customParams = is_string($product->other_parameters) ? json_decode($product->other_parameters, true) : $product->other_parameters;
                                        @endphp
                                        @if(!empty($customParams))
                                            @foreach($customParams as $key => $value)
                                            <div class="row g-3 align-items-end mt-2">
                                                <div class="col-md-5">
                                                    <label class="form-label">Parameter Name</label>
                                                    <input type="text" name="custom_keys[]" class="form-control" value="{{ $key }}" placeholder="Enter parameter (e.g., Height)">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Value</label>
                                                    <input type="text" name="custom_values[]" class="form-control" value="{{ $value }}">
                                                </div>
                                                <div class="col-md-2 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-size" onclick="removeParameter(this)">X</button>
                                                </div>
                                            </div>

                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">GST Applied (%)</label>
                            <input type="text" name="gst_percentage" class="form-control" value="{{ old('gst_percentage', $product->gst_percentage) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Warranty Period</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" name="warranty_period" class="form-control" value="{{ old('warranty_period', $product->warranty_period) }}">
                                </div>
                                <div class="col-md-6">
                                    <select name="warranty_type" class="form-select">
                                        <option value="months" {{ $product->warranty_type == 'months' ? 'selected' : '' }}>Months</option>
                                        <option value="years" {{ $product->warranty_type == 'years' ? 'selected' : '' }}>Years</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="m-3">
                        <label class="form-label fw-bold">Adhesive</label>
                        <select name="adhesive_id" class="form-select mb-3">
                            <option disabled>Select Adhesive</option>
                            @foreach ($adhesives as $adhesive)
                                <option value="{{ $adhesive->id }}" {{ $adhesive->id == $product->adhesive_id ? 'selected' : '' }}>
                                    {{ $adhesive->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Labor Charges (per sq.ft)</label>
                            <input type="text" name="labor_charges" class="form-control" value="{{ old('labor_charges', $product->labor_charges) }}">
                        </div>

                        <div class="mb-3 row">
                            <label class="form-label fw-bold">Estimated Delivery Time</label>
                            <div class="col-md-6">
                                <input type="number" name="delivery_duration" class="form-control" value="{{ old('delivery_duration', $product->delivery_duration) }}">
                            </div>
                            <div class="col-md-6">
                                <select name="delivery_unit" class="form-select">
                                    <option value="days" {{ $product->delivery_unit == 'days' ? 'selected' : '' }}>Days</option>
                                    <option value="months" {{ $product->delivery_unit == 'months' ? 'selected' : '' }}>Months</option>
                                    <option value="years" {{ $product->delivery_unit == 'years' ? 'selected' : '' }}>Years</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary mt-3" type="submit">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                    </div>
                </div>

                <!-- Product Images Section -->
                <div class="card shadow-sm mb-4">
                    <div class="m-3">
                        <h5 class="text-primary mb-4"><i class="fas fa-images me-2"></i>Product Images</h5>
                        <!-- <div class="row justify-content-center">
                            <div class="d-grid gap-2 col-6 mx-auto col-md-6">
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addproduct">
                                    <i class="fas fa-plus me-2"></i>Add Product Images
                                </button>
                            </div>
                        </div> -->

                        <div class="container mt-4">
                            <h5 class="mb-3">Product List</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product Image</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Product Color</th>
                                        
                                            <th>Purchase Cost</th>
                                            <th>Selling Price</th>
                                            <th>Discount Price</th>
                                            <th>Stock Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productTableBody">
                                        @foreach ($product->images as $p)
                                            <tr>
                                                <td>
                                                    @if ($p->image_path)
                                                        <img src="{{ asset('storage/' . $p->image_path) }}" alt="Product Image" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('images/default.png') }}" alt="Default Image" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                </td>
                                                <td>{{ $p->pdf_name ?? '--' }}</td>
                                                <td>{{ $p->product_code ?? '--' }}</td>
                                                <td>{{ $p->product_color ?? '--' }}</td>
                                            
                                                <td>₹{{ $p->purchase_cost ?? '0' }}</td>
                                                <td>₹{{ $p->selling_price ?? '0' }}</td>
                                                <td>₹{{ $p->discount_price ?? '0' }}</td>
                                                <td>{{ $p->stock_available ? 'Available' : 'Not Available' }}</td>
                                                <td>
                                                    <span>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row" data-id="{{ $p->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-sm edit-row" data-id="{{ $p->id }}" data-product-id="{{ $product->id }}" id="">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Images Section -->
                <div class="card shadow-sm">
                    <div class="m-3">
                        <h5 class="text-primary mb-4"><i class="fas fa-images me-2"></i>Sample Images</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSampleImageModal">
                                <i class="fas fa-plus me-2"></i>Add New Sample Image
                            </button>
                        </div>
                        <div id="imagePreviewContainer" class="row mt-3">
                            @foreach($product->sampleImages as $sampleImage)
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm">
                                        <img src="{{ asset('storage/' . $sampleImage->image_path) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Sample Image">
                                        <div class="card-body p-2 text-center">
                                            <button class="btn btn-sm btn-danger remove-sample" data-id="{{ $sampleImage->id }}">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Add Product Modal -->
<!-- <div class="modal fade" id="addproduct" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Product Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div style="display:flex;flex-direction:row;gap:5%;width:100%;">
                    <div class="column">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Select Image</label>
                            <div class="border p-3 text-center rounded" style="border-style: dashed;">
                                <input type="file" class="form-control d-none" id="productImage" accept="image/*" onchange="previewImage(event)">
                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                <p class="mb-1">Drag your file(s) or <a href="#">browse</a></p>
                                <small class="text-muted">Max 10 MB files are allowed</small>
                                <div id="imagePreview" class="mt-3"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">PDF Name</label>
                            <input type="text" class="form-control" id="pdfName" placeholder="Enter PDF Name">
                            <span class="text-danger d-none" id="pdfNameError">Required</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Product Code</label>
                            <input type="text" class="form-control" id="productCode" placeholder="Enter product code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Product Color</label>
                            <input type="text" class="form-control" id="productColor" placeholder="Enter Color">
                        </div>
                    </div>
                    <div class="column">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Purchase Cost<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="purchaseCost" name="purchase_cost" value="0" min="0" required>
                                </div>
                                <span class="text-danger d-none" id="purchaseCostError">Required</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Selling Price<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="sellingPrice" name="selling_price" value="0" min="0" required>
                                </div>
                                <span class="text-danger d-none" id="sellingPriceError">Required</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Discount Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="discountPrice" placeholder="00" min="0">
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="stockAvailable" checked>
                            <label class="form-check-label fw-bold text-dark" for="stockAvailable">Stock Available</label>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" onclick="saveProduct()">
                                <i class="fas fa-save me-2"></i>Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Edit Product Image Modal -->
<div class="modal fade" id="editProductImageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-edit me-2" id=""></i>Edit Product Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editImageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="editImageId" name="image_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Product Image</label>
                                <div class="border p-3 text-center rounded" style="border-style: dashed;">
                                    <input type="file" class="form-control d-none" id="editProductImage" name="product_image" accept="image/*" onchange="previewEditImage(event)">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                    <p class="mb-1">Drag your file(s) or <a href="#" onclick="document.getElementById('editProductImage').click(); return false;">browse</a></p>
                                    <small class="text-muted">Max 10 MB files are allowed</small>
                                    <div id="editImagePreview" class="mt-3"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">PDF Name<span class="text-danger">*</span></label>
                                <input type="text" name="pdf_name" id="editPdfName" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Product Code</label>
                                <input type="text" name="product_code" id="editProductCode" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Product Color</label>
                                <input type="text" name="product_color" id="editProductColor" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Purchase Cost<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="purchase_cost" id="editPurchaseCost" class="form-control" required min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Selling Price<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="selling_price" id="editSellingPrice" class="form-control" required min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Discount Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="discount_price" id="editDiscountPrice" class="form-control" min="0">
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="stock_available" id="editStockAvailable">
                                <label class="form-check-label fw-bold" for="editStockAvailable">Stock Available</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-secondary me-md-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Sample Image Modal -->
<div class="modal fade" id="addSampleImageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-plus me-2"></i>Add Sample Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSampleForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Image</label>
                        <div class="border p-3 text-center rounded" style="border-style: dashed;">
                            <input type="file" class="form-control d-none" id="newSampleImage" name="sample_image" accept="image/*">
                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                            <p class="mb-1">Drag your file(s) or <a href="#" onclick="document.getElementById('newSampleImage').click(); return false;">browse</a></p>
                            <small class="text-muted">Max 10 MB files are allowed</small>
                            <div id="newSamplePreview" class="mt-3">
                                <!-- New image preview will be shown here -->
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-secondary me-md-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Sample Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sample Image Modal -->
<!-- <div class="modal fade" id="editSampleImageModal" tabindex="-1" aria-labelledby="editSampleImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSampleForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editSampleImageModalLabel">Edit Sample Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editSampleId" name="sample_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div id="currentImagePreview" class="text-center mb-3">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Image</label>
                        <input type="file" id="editSampleImage" name="sample_image" class="form-control" accept="image/*" required>
                        <div id="editSamplePreview" class="mt-3">
                           
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Sample Image
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add Parameter Button Handler
        const addButton = document.getElementById("add-size");
        const container = document.getElementById("size-container");

        if (addButton && container) {
            // Add click event to existing remove buttons
            document.querySelectorAll('.remove-size').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.row').remove();
                });
            });

            // Add click event to the Add Parameter button
            addButton.addEventListener("click", function() {
                const newRow = document.createElement("div");
                newRow.classList.add("row", "mt-2");
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <label class="form-label">Parameter Name</label>
                        <input type="text" name="custom_keys[]" class="form-control" placeholder="Enter parameter (e.g., Height)">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Value</label>
                        <input type="text" name="custom_values[]" class="form-control">
                    </div>
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-danger mt-5 btn-sm remove-size">X</button>
                    </div>
                `;
                container.appendChild(newRow);

                // Add click event to the new remove button
                const removeButton = newRow.querySelector(".remove-size");
                if (removeButton) {
                    removeButton.addEventListener("click", function() {
                        newRow.remove();
                    });
                }
            });
        }

        // Handle edit product image form submission
        const editImageForm = document.getElementById('editImageForm');
        if (editImageForm) {
            editImageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const imageId = document.getElementById('editImageId').value;
                
                // Convert checkbox value to boolean
                formData.set('stock_available', document.getElementById('editStockAvailable').checked ? '1' : '0');

                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                submitButton.disabled = true;

                fetch(`/update-product-image/${imageId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product image updated successfully');
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to update product image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating product image: ' + error.message);
                })
                .finally(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                });
            });
        }

        // Handle remove product image
        document.querySelectorAll('.remove-row').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this product image?')) {
                    const imageId = this.getAttribute('data-id');
                    fetch(`/remove-product-image/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.closest('tr').remove();
                            alert('Product image removed successfully');
                        } else {
                            alert('Error removing product image: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error removing product image. Please try again.');
                    });
                }
            });
        });

        // Handle edit sample image form submission
        document.addEventListener('DOMContentLoaded', function() {
            const editSampleForm = document.getElementById('editSampleForm');
            if (editSampleForm) {
                editSampleForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const formData = new FormData(this);
                    const fileInput = document.getElementById('editSampleImage');
                    const sampleId = document.getElementById('editSampleId').value;
                    
                    if (!fileInput.files[0]) {
                        alert('Please select a new image to update');
                        return false;
                    }

                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                    submitButton.disabled = true;

                    fetch(`/update-sample-image/${sampleId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const imageCard = document.querySelector(`.edit-sample[data-id="${sampleId}"]`).closest('.col-md-4');
                            if (imageCard) {
                                const imgElement = imageCard.querySelector('img');
                                if (imgElement) {
                                    imgElement.src = `/storage/${data.sample_image.image_path}`;
                                }
                            }

                            const modal = bootstrap.Modal.getInstance(document.getElementById('editSampleImageModal'));
                            modal.hide();
                            this.reset();
                            document.getElementById('editSamplePreview').innerHTML = '';
                            
                            alert('Sample image updated successfully');
                        } else {
                            alert('Error updating sample image: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating sample image');
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    });
                });
            }
            
        });

        // Handle remove sample image
        document.querySelectorAll('.remove-sample').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this sample image?')) {
                    const sampleId = this.getAttribute('data-id');
                    fetch(`/remove-sample-image/${sampleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.closest('.col-md-4').remove();
                            alert('Sample image removed successfully');
                        } else {
                            alert('Error removing sample image: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error removing sample image. Please try again.');
                    });
                }
            });
        });

        // Add event listeners for edit buttons
        document.querySelectorAll('.edit-row').forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-id');
                fetch(`/get-product-image/${imageId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const image = data.product_image;
                        document.getElementById('editImageId').value = image.id;
                        document.getElementById('editPdfName').value = image.pdf_name || '';
                        document.getElementById('editProductCode').value = image.product_code || '';
                        document.getElementById('editProductColor').value = image.product_color || '';
                        document.getElementById('editPurchaseCost').value = image.purchase_cost || '';
                        document.getElementById('editSellingPrice').value = image.selling_price || '';
                        document.getElementById('editDiscountPrice').value = image.discount_price || '';
                        document.getElementById('editStockAvailable').checked = image.stock_available;

                        const previewContainer = document.getElementById('editImagePreview');
                        if (image.image_path) {
                            previewContainer.innerHTML = `
                                <div class="text-center">
                                    <img src="/storage/${image.image_path}" class="img-fluid" style="max-height: 150px;">
                                </div>
                            `;
                        }

                        const modal = new bootstrap.Modal(document.getElementById('editProductImageModal'));
                        modal.show();
                    } else {
                        alert('Error loading product image data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading product image data. Please try again.');
                });
            });
        });

        // Add event listener for edit sample buttons
        document.querySelectorAll('.edit-sample').forEach(button => {
            button.addEventListener('click', function() {
                const sampleId = this.getAttribute('data-id');
                fetch(`/get-sample-image/${sampleId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const sample = data.sample_image;
                        document.getElementById('editSampleId').value = sample.id;

                        const currentPreviewContainer = document.getElementById('currentImagePreview');
                        if (sample.image_path) {
                            currentPreviewContainer.innerHTML = `
                                <div class="text-center">
                                    <img src="/storage/${sample.image_path}" class="img-fluid" style="max-height: 200px; width: auto; object-fit: contain;">
                                </div>
                            `;
                        }

                        document.getElementById('editSamplePreview').innerHTML = '';

                        const modal = new bootstrap.Modal(document.getElementById('editSampleImageModal'));
                        modal.show();
                    } else {
                        alert('Error loading sample image data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading sample image data. Please try again.');
                });
            });
        });
    });
</script>

<script>
function previewEditImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('editImagePreview');

    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert("File size must be less than 10MB");
            event.target.value = ''; // Clear the file input
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <div class="text-center">
                    <img src="${e.target.result}" class="img-fluid" style="max-height: 150px;">
                    <p class="mt-2 text-muted">${file.name}</p>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.innerHTML = '';
    }
}
</script>

<script>
function addParameter() {
    const container = document.getElementById("size-container");
    const newRow = document.createElement("div");
    newRow.classList.add("row", "mt-2");
    newRow.innerHTML = `
        <div class="col-md-5">
            <label class="form-label">Parameter Name</label>
            <input type="text" name="custom_keys[]" class="form-control" placeholder="Enter parameter (e.g., Height)">
        </div>
        <div class="col-md-5">
            <label class="form-label">Value</label>
            <input type="text" name="custom_values[]" class="form-control">
        </div>
        <div class="col-md-2 text-center">
            <button type="button" class="btn btn-danger mt-5 btn-sm remove-size">X</button>
        </div>
    `;
    container.appendChild(newRow);
}

function removeParameter(button) {
    button.closest('.row').remove();
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle add sample image form submission
        const addSampleForm = document.getElementById('addSampleForm');
        if (addSampleForm) {
            addSampleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const fileInput = document.getElementById('newSampleImage');
                
                if (!fileInput.files[0]) {
                    alert('Please select an image to upload');
                    return false;
                }

                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                submitButton.disabled = true;

                fetch('/add-sample-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add the new image to the preview container
                        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
                        const newImageHtml = `
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm">
                                    <img src="${data.sample_image.image_url}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Sample Image">
                                    <div class="card-body p-2 text-center">
                                        <button class="btn btn-sm btn-primary me-2 edit-sample" data-id="${data.sample_image.id}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger remove-sample" data-id="${data.sample_image.id}">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        imagePreviewContainer.insertAdjacentHTML('beforeend', newImageHtml);

                        // Close the modal and reset the form
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addSampleImageModal'));
                        modal.hide();
                        this.reset();
                        document.getElementById('newSamplePreview').innerHTML = '';
                        
                        alert('Sample image added successfully');
                    } else {
                        alert('Error adding sample image: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding sample image. Please try again.');
                })
                .finally(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                });
            });
        }

        // Preview new sample image before upload
        const newSampleImage = document.getElementById('newSampleImage');
        if (newSampleImage) {
            newSampleImage.addEventListener('change', function(event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('newSamplePreview');

                if (file) {
                    if (file.size > 10 * 1024 * 1024) {
                        alert("File size must be less than 10MB");
                        this.value = ''; // Clear the file input
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                            <div class="text-center">
                                <img src="${e.target.result}" class="img-fluid" style="max-height: 150px;">
                                <p class="mt-2 text-muted">${file.name}</p>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML = '';
                }
            });
        }
    });
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit product image buttons
        document.querySelectorAll('.edit-row').forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-id');
                fetch(`/get-product-image/${imageId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const image = data.product_image;
                        document.getElementById('editImageId').value = image.id;
                        document.getElementById('editPdfName').value = image.pdf_name || '';
                        document.getElementById('editProductCode').value = image.product_code || '';
                        document.getElementById('editProductColor').value = image.product_color || '';
                        document.getElementById('editPurchaseCost').value = image.purchase_cost || '';
                        document.getElementById('editSellingPrice').value = image.selling_price || '';
                        document.getElementById('editDiscountPrice').value = image.discount_price || '';
                        document.getElementById('editStockAvailable').checked = image.stock_available;

                        const previewContainer = document.getElementById('editImagePreview');
                        if (image.image_path) {
                            previewContainer.innerHTML = `
                                <div class="text-center">
                                    <img src="/storage/${image.image_path}" class="img-fluid" style="max-height: 150px;">
                                </div>
                            `;
                        }

                        const modal = new bootstrap.Modal(document.getElementById('editProductImageModal'));
                        modal.show();
                    } else {
                        alert('Error loading product image data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading product image data. Please try again.');
                });
            });
        });

        // Handle edit product image form submission
        const editImageForm = document.getElementById('editImageForm');
        if (editImageForm) {
            editImageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const imageId = document.getElementById('editImageId').value;
                
                // Convert checkbox value to boolean
                formData.set('stock_available', document.getElementById('editStockAvailable').checked ? '1' : '0');

                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                submitButton.disabled = true;

                fetch(`/update-product-image/${imageId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product image updated successfully');
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to update product image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating product image: ' + error.message);
                })
                .finally(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                });
            });
        }
    });
</script>
@endpush


