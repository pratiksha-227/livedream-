@extends('layouts.app')
@section('content')

@section('content')
<div class="container-fluid" >
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="d-flex flex-row justify-content-between">
            <h2 class="mb-4 text-primary">Create Product</h2>
        </div>

        <div class="row ">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="m-3">
                        <h5 class="text-primary mb-4">Product Details</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Company<span class="text-danger">*</span></label>
                            <select name="company_id" class="form-select" required>
                                <option value="" disabled selected>Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category<span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Application Area</label>
                            <input type="text" name="application_area" class="form-control">
                        </div>

                        <h5 class="text-primary mb-3">Product Size</h5>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="form-label">Length</label>
                                <input type="number" name="length" class="form-control" value="0">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Width</label>
                                <input type="number" name="width" class="form-control" value="0">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Thickness</label>
                                <input type="number" name="thickness" class="form-control" value="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Unit</label>
                                <select name="unit" class="form-select">
                                    @foreach(['ft'=>'Foot','m'=>'Meter','cm'=>'Centimeter','mm'=>'Millimeter','inch'=>'Inch'] as $code => $label)
                                        <option value="{{ $code }}">
                                            {{ $label }} ({{ $code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Add Parameter</label>
                                <button type="button" id="custom-params-button" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Add Parameter
                                </button>
                                <div id="custom-params-container" class="mt-3"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">GST Applied (%)</label>
                            <input type="text" name="gst_percentage" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Warranty Period</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" name="warranty_period" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <select name="warranty_type" class="form-select">
                                        <option value="months">Months</option>
                                        <option value="years">Years</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
          

            <div class="col-md-6">
                <div class="card shadow-sm ">
                    <div class="m-3">
                        <label class="form-label fw-bold">Adhesive</label>
                        <select name="adhesive_id" class="form-select mb-3">
                            <option value="" disabled selected>Select Adhesive</option>
                            @foreach ($adhesives as $adhesive)
                                <option value="{{ $adhesive->id }}">{{ $adhesive->name }}</option>
                            @endforeach
                        </select>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Labor Charges (per sq.ft)</label>
                            <input type="text" name="labor_charges" class="form-control">
                        </div>

                        <div class="mb-3 row">
                            <label class="form-label fw-bold">Estimated Delivery Time</label>
                            <div class="col-md-6">
                                <input type="number" name="delivery_duration" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <select name="delivery_unit" class="form-select">
                                    <option value="days">Days</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Zone-Wise Pricing</h5>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enableManualPricing">
                                    <label class="form-check-label fw-bold" for="enableManualPricing">
                                        Enable Manual Pricing
                                    </label>
                                </div>
                            </div>
                            @foreach($zones as $zone)
                                <div class="form-check mb-2">
                                    <input class="form-check-input zone-checkbox" type="checkbox" 
                                           name="zones[{{ $zone->id }}][enabled]" 
                                           id="zone_{{ $zone->id }}" value="1">
                                    <label class="form-check-label fw-bold" for="zone_{{ $zone->id }}">
                                        {{ $zone->name }}
                                    </label>

                                    <div class="mt-2 ms-4 zone-price-input d-none" id="zone_price_{{ $zone->id }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Base Price</label>
                                                <input type="number" step="0.01" 
                                                       name="zones[{{ $zone->id }}][base_price]" 
                                                       class="form-control base-price" 
                                                       placeholder="Enter base price">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Multiplier</label>
                                                <input type="number" step="0.01" 
                                                       name="zones[{{ $zone->id }}][multiplier]" 
                                                       class="form-control multiplier" 
                                                       value="1.00" 
                                                       placeholder="Enter multiplier">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Final Price</label>
                                                <input type="number" step="0.01" 
                                                       name="zones[{{ $zone->id }}][price]" 
                                                       class="form-control final-price" 
                                                       readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="btn btn-primary mt-3" type="submit">
                            <i class="fas fa-save me-2"></i>Create Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
            <div class="row " style="margin-top:-25%">
                <div class="col-md-6 offset-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="m-3">
                            <h5 class="text-primary mb-4"><i class="fas fa-images me-2"></i>Product Images</h5>
                            <div class="row justify-content-center">
                                <div class="d-grid gap-2 col-6 mx-auto col-md-6">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addproduct">
                                        <i class="fas fa-plus me-2"></i>Add Product Images
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
              
               

                    <!-- Sample Images Section -->
                   
                        <div class="card shadow-sm mb-4">
                            <div class="m-3">
                                <h5 class="text-primary mb-4"><i class="fas fa-images me-2"></i>Sample Images</h5>
                                <div class="d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSampleImageModal">
                                        <i class="fas fa-plus me-2"></i>Add New Sample Image
                                    </button>
                                </div>
                                <div id="imagePreviewContainer" class="row mt-3">
                                </div>
                            </div>
                        </div>
                   
                </div>
               
            </div>
                
</div>
                <!-- Add Product Modal --> 
 <div class="modal fade" id="addproduct" tabindex="-1">
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
                                <p class="mb-1">Drag your file(s) or <a href="#" onclick="document.getElementById('productImage').click(); return false;">browse</a></p>
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
                            <button type="button" class="btn btn-primary" id="saveProductImageBtn" onclick="saveProductForm()">
                                <i class="fas fa-save me-2"></i>Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Sample Image Modal -->
<div class="modal fade" id="addSampleImageModal" tabindex="-1" aria-labelledby="addSampleImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="addSampleImageModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add Sample Images
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Select Sample Images</label>
                    <div class="border p-3 text-center rounded" style="border-style: dashed;">
                        <input type="file" class="form-control d-none" id="imageUpload" accept="image/*" multiple onchange="previewSampleImages(event)">
                        <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                        <p class="mb-1">Drag your file(s) or <a href="#" onclick="document.getElementById('imageUpload').click(); return false;">browse</a></p>
                        <small class="text-muted">Max 10 MB files are allowed</small>
                        <div id="sampleImagePreview" class="mt-3 row"></div>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="button" class="btn btn-primary" id="saveSampleImagesBtn" onclick="saveSampleImages()">
                        <i class="fas fa-save me-2"></i>Save Sample Images
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Remove all localStorage related code
    document.addEventListener('DOMContentLoaded', function() {
        // Clear any stored data
        localStorage.removeItem('custom_params');
        localStorage.removeItem('current_product_id');
        localStorage.removeItem('current_product_image_id');
        
        // Initialize form elements
        const form = document.getElementById('productForm');
        const addParamBtn = document.getElementById('custom-params-button');
        const customParamsContainer = document.getElementById('custom-params-container');

        // Add Parameter button click handler
        if (addParamBtn) {
            addParamBtn.addEventListener('click', () => {
                const paramDiv = document.createElement('div');
                paramDiv.className = 'row mb-2 align-items-center';
                paramDiv.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" name="param_names[]" class="form-control" placeholder="Parameter Name">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="param_values[]" class="form-control" placeholder="Parameter Value">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-param">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;

                // Add remove button functionality
                paramDiv.querySelector('.remove-param').addEventListener('click', function() {
                    paramDiv.remove();
                });

                customParamsContainer.appendChild(paramDiv);
            });
        }

        // Form submit handler
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                saveProduct();
            });
        }
    });

    // Function to save product
    function saveProduct() {
        const form = document.getElementById('productForm');
        const formData = new FormData(form);
        
        // Show loading state
        const saveButton = form.querySelector('button[type="submit"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        saveButton.disabled = true;

        fetch('{{ route("products.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product created successfully!');
                window.location.href = '{{ route("products.show") }}';
            } else {
                alert('Error creating product: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating product. Please try again.');
        })
        .finally(() => {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        });
    }

    // Product image handling
  function previewSingleImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    preview.classList.add('d-none');

    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = document.createElement('img');
        img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
        preview.appendChild(img);
        preview.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }
  }

  function showError(id, message) {
    const el = document.getElementById(id);
    el.textContent = message;
    el.classList.remove('d-none');
    return false;
  }

  function hideErrors() {
    document.querySelectorAll('.text-danger').forEach(el => {
      el.classList.add('d-none');
      el.textContent = '';
    });
  }

    // Function to save product image
    function saveProductForm() {
        // Get product ID from localStorage
        const productId = localStorage.getItem('current_product_id');
        
        if (!productId) {
            alert('Please save the product details first to get a product ID');
            // Close the modal if it's open
            const modal = bootstrap.Modal.getInstance(document.getElementById('addproduct'));
            if (modal) {
                modal.hide();
            }
            return;
        }

        // Check if CSRF token is available
        if (!csrfToken) {
            console.error('CSRF token is missing!');
            alert('Security token missing. Please refresh the page and try again.');
            return;
        }

        const productImageInput = document.getElementById('productImage');
        const productImage = productImageInput && productImageInput.files && productImageInput.files[0];

        if (!productImage) {
            alert('Please select a product image');
            return;
        }

        // Validate image file
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validImageTypes.includes(productImage.type)) {
            alert('Please select a valid image file (JPEG, PNG, GIF, or WEBP)');
            return;
        }

        // Check file size (max 10MB)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (productImage.size > maxSize) {
            alert('Image size should be less than 10MB');
            return;
        }

        // Show loading state
        const saveButton = document.querySelector('button[onclick="saveProductForm()"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        saveButton.disabled = true;

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('product_detail_id', productId);
        formData.append('product_image', productImage);

        // Safely get values, checking if elements exist
        const pdfNameInput = document.getElementById('pdfName');
        const productCodeInput = document.getElementById('productCode');
        const productColorInput = document.getElementById('productColor');
        const purchaseCostInput = document.getElementById('purchaseCost');
        const sellingPriceInput = document.getElementById('sellingPrice');
        const discountPriceInput = document.getElementById('discountPrice');
        const stockAvailableInput = document.getElementById('stockAvailable');

        // Add form fields with validation
        if (pdfNameInput) formData.append('pdf_name', pdfNameInput.value.trim());
        if (productCodeInput) formData.append('product_code', productCodeInput.value.trim());
        if (productColorInput) formData.append('product_color', productColorInput.value.trim());
        if (purchaseCostInput) formData.append('purchase_cost', purchaseCostInput.value || '0');
        if (sellingPriceInput) formData.append('selling_price', sellingPriceInput.value || '0');
        if (discountPriceInput) formData.append('discount_price', discountPriceInput.value || '0');
        if (stockAvailableInput) formData.append('stock_available', stockAvailableInput.checked ? '1' : '0');

        // Log the form data being sent
        console.log('Sending form data:', {
            product_id: productId,
            product_detail_id: productId,
            pdf_name: pdfNameInput ? pdfNameInput.value : '',
            product_code: productCodeInput ? productCodeInput.value : '',
            product_color: productColorInput ? productColorInput.value : '',
            purchase_cost: purchaseCostInput ? purchaseCostInput.value : '0',
            selling_price: sellingPriceInput ? sellingPriceInput.value : '0',
            discount_price: discountPriceInput ? discountPriceInput.value : '0',
            stock_available: stockAvailableInput ? stockAvailableInput.checked : false,
            has_image: !!productImage,
            image_type: productImage.type,
            image_size: productImage.size
        });

        fetch('{{ route("products.store-form") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Parsed response data:', data);

            // Reset button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;

            if (data.success) {
                alert('Product image saved successfully!');
                
                // Store the product image ID
                const productImageId = data.product_image.id;
                console.log('Product Image ID:', productImageId);
                
                // Store product image ID in localStorage
                localStorage.setItem('current_product_image_id', productImageId);
                
                // Update button states
                updateButtonStates();
                
                // Clear the image preview and file input
                document.getElementById('imagePreview').innerHTML = '';
                if(productImageInput) productImageInput.value = '';
                
                // Clear other form fields
                if(pdfNameInput) pdfNameInput.value = '';
                if(productCodeInput) productCodeInput.value = '';
                if(productColorInput) productColorInput.value = '';
                if(purchaseCostInput) purchaseCostInput.value = '0';
                if(sellingPriceInput) sellingPriceInput.value = '0';
                if(discountPriceInput) discountPriceInput.value = '0';
                if(stockAvailableInput) stockAvailableInput.checked = true;
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addproduct'));
                if (modal) {
                    modal.hide();
                }

            } else {
                const errorMessage = data.message || 'Unknown error occurred';
                console.error('Error saving product image:', errorMessage);
                alert('Error saving product image: ' + errorMessage);
            }
        })
        .catch(error => {
            // Reset button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;

            console.error('Error details:', error);
            alert('Error saving product image: ' + error.message);
        });
    }

    // Function to load product images
    function loadProductImages(productId) {
        if (!productId) {
            console.error('Product ID is missing');
            return;
        }

        // Show loading state
        const tbody = document.getElementById('productTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
        }

        fetch(`/api/products/${productId}/images`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (tbody) {
                if (data.images && data.images.length > 0) {
                    tbody.innerHTML = data.images.map(image => `
                        <tr>
                            <td>
                                <img src="${image.image_path ? '/storage/' + image.image_path : '/images/default.png'}" 
                                     alt="Product Image" 
                                     class="img-fluid" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>${image.pdf_name || '--'}</td>
                            <td>${image.product_code || '--'}</td>
                            <td>${image.product_color || '--'}</td>
                            <td>₹${image.purchase_cost || '0'}</td>
                            <td>₹${image.selling_price || '0'}</td>
                            <td>₹${image.discount_price || '0'}</td>
                            <td>${image.stock_available ? 'Available' : 'Not Available'}</td>
                            <td>
                                <span>
                                    <button type="button" class="btn btn-danger btn-sm remove-row" data-id="${image.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm edit-row" data-id="${image.id}" data-product-id="${productId}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </span>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">No images found</td></tr>';
                }
            }
        })
        .catch(error => {
            console.error('Error loading product images:', error);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>Error loading images: ${error.message}
                </td></tr>`;
            }
        });
    }

    // Function to preview sample images
    function previewSampleImages(event) {
        const files = event.target.files;
        const preview = document.getElementById('sampleImagePreview');
        preview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-2';
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        </div>
                    `;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // Function to save sample images
    function saveSampleImages() {
        console.log('saveSampleImages function called');
        const productImageId = localStorage.getItem('current_product_image_id');
        const productId = localStorage.getItem('current_product_id');
        
        console.log('Debug - Product Image ID:', productImageId);
        console.log('Debug - Product ID:', productId);

        if (!productId) {
            alert('Product ID is missing. Please save the product details first.');
            // Close the modal if it's open
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSampleImageModal'));
            if (modal) {
                modal.hide();
            }
            return;
        }

        if (!productImageId) {
            alert('Please save the product image first to get a product image ID');
            // Close the modal if it's open
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSampleImageModal'));
            if (modal) {
                modal.hide();
            }
            return;
        }

        const sampleImages = document.getElementById('imageUpload').files;
        if (sampleImages.length === 0) {
            alert('Please select at least one sample image');
            return;
        }

        // Show loading state
        const saveButton = document.getElementById('saveSampleImagesBtn');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        saveButton.disabled = true;

        const formData = new FormData();
        formData.append('product_image_id', productImageId);
        formData.append('product_detail_id', productId);
        
        // Add each sample image
        for (let i = 0; i < sampleImages.length; i++) {
            const file = sampleImages[i];
            formData.append('sample_images[]', file);
        }

        // Show loading message
        const loadingAlert = document.createElement('div');
        loadingAlert.className = 'alert alert-info';
        loadingAlert.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving sample images...';
        document.querySelector('#addSampleImageModal .modal-body').prepend(loadingAlert);

        fetch('/products/store-samples', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            console.log('Debug - Response status:', response.status);
            const responseText = await response.text();
            console.log('Debug - Raw response:', responseText);

            if (!response.ok) {
                throw new Error(`Server error: ${response.status} - ${responseText}`);
            }

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Debug - Error parsing response:', e);
                console.error('Debug - Raw response text:', responseText);
                throw new Error('Server returned invalid response format. Please try again.');
            }

            return data;
        })
        .then(data => {
            console.log('Debug - Parsed response data:', data);

            // Remove loading message
            loadingAlert.remove();

            // Reset button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;

            if (data.success) {
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success';
                successAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>Sample images saved successfully!';
                document.querySelector('#addSampleImageModal .modal-body').prepend(successAlert);
                
                // Clear the image preview and file input
                document.getElementById('sampleImagePreview').innerHTML = '';
                document.getElementById('imageUpload').value = '';
                
                // Close the modal after a short delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSampleImageModal'));
                    if (modal) {
                        modal.hide();
                    }
                    // Remove success message after modal is closed
                    successAlert.remove();
                }, 1500);

                // Refresh the sample images display
                loadSampleImages(productImageId);

            } else {
                const errorMessage = data.message || 'Unknown error occurred';
                console.error('Debug - Error saving sample images:', errorMessage);
                
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger';
                errorAlert.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>Error: ${errorMessage}`;
                document.querySelector('#addSampleImageModal .modal-body').prepend(errorAlert);
                
                // Remove error message after 5 seconds
                setTimeout(() => {
                    errorAlert.remove();
                }, 5000);
            }
        })
        .catch(error => {
            // Remove loading message
            loadingAlert.remove();

            // Reset button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;

            console.error('Debug - Error details:', error);
            
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger';
            errorAlert.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>Error: ${error.message}`;
            document.querySelector('#addSampleImageModal .modal-body').prepend(errorAlert);
            
            // Remove error message after 5 seconds
            setTimeout(() => {
                errorAlert.remove();
            }, 5000);
        });
    }

    // Function to load sample images
    function loadSampleImages(productImageId) {
        if (!productImageId) {
            console.error('Product Image ID is missing');
            return;
        }

        const container = document.getElementById('imagePreviewContainer');
        if (container) {
            container.innerHTML = '<div class="col-12 text-center"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</div>';
        }

        fetch(`/api/product-images/${productImageId}/samples`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (container) {
                if (data.samples && data.samples.length > 0) {
                    container.innerHTML = data.samples.map(sample => `
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <img src="/storage/${sample.image_path}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;" 
                                     alt="Sample Image">
                                <div class="card-body p-2 text-center">
                                    <button class="btn btn-sm btn-danger remove-sample" 
                                            data-id="${sample.id}" 
                                            onclick="removeSampleImage(${sample.id})">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="col-12 text-center">No sample images found</div>';
                }
            }
        })
        .catch(error => {
            console.error('Error loading sample images:', error);
            if (container) {
                container.innerHTML = `<div class="col-12 text-center text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>Error loading sample images: ${error.message}
                </div>`;
            }
        });
    }

    // Function to remove sample image
    function removeSampleImage(sampleId) {
        if (!confirm('Are you sure you want to remove this sample image?')) {
            return;
        }

        fetch(`/sample-images/${sampleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sample image removed successfully');
                // Refresh the sample images display
                const productImageId = localStorage.getItem('current_product_image_id');
                if (productImageId) {
                    loadSampleImages(productImageId);
                }
            } else {
                alert('Error removing sample image: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error removing sample image:', error);
            alert('Error removing sample image. Please try again.');
        });
    }

    // Disable image upload buttons initially
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('button[data-bs-toggle="modal"]').disabled = true;
        document.querySelector('button[onclick="saveSampleImages()"]').disabled = true;
    });

    // Add this function for image preview
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        preview.classList.add('d-none');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                preview.appendChild(img);
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    // Add drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.querySelector('.border.p-3');
        const fileInput = document.getElementById('productImage');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('bg-light');
        }

        function unhighlight(e) {
            dropZone.classList.remove('bg-light');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            
            // Trigger the change event
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    // Function to create parameter input row
    function createParamInput(name = '', value = '') {
        const container = document.getElementById('custom-params-container');
        const paramDiv = document.createElement('div');
        paramDiv.className = 'row mb-2 align-items-center';
        paramDiv.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="param_names[]" class="form-control" 
                       placeholder="Parameter Name" value="${name}">
            </div>
            <div class="col-md-5">
                <input type="text" name="param_values[]" class="form-control" 
                       placeholder="Parameter Value" value="${value}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-param">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        // Add event listener for remove button
        paramDiv.querySelector('.remove-param').addEventListener('click', function() {
            paramDiv.remove();
        });

        container.appendChild(paramDiv);
    }

    // Initialize Bootstrap modals
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all modals
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            new bootstrap.Modal(modal);
        });

        // Initialize the save button click handler
        const saveSampleImagesBtn = document.getElementById('saveSampleImagesBtn');
        if (saveSampleImagesBtn) {
            saveSampleImagesBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Save Sample Images button clicked');
                saveSampleImages();
            });
        }

        // Initialize Bootstrap modal
        const sampleImageModal = document.getElementById('addSampleImageModal');
        if (sampleImageModal) {
            sampleImageModal.addEventListener('hidden.bs.modal', function () {
                // Clear the file input and preview when modal is closed
                document.getElementById('imageUpload').value = '';
                document.getElementById('sampleImagePreview').innerHTML = '';
            });
        }
    });
</script>

@endsection
