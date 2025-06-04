@extends('layouts.app')
@section('content')
<div class="content">
    <div class="d-flex flex-row justify-content-between">
        <h2 class="mb-4 text-primary">Edit Product Image</h2>
    </div>

    <div class="card shadow-sm">
        <div class="m-3">
            <form id="editImageForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Image</label>
                            <div class="border p-3 text-center rounded" style="border-style: dashed;">
                                <input type="file" class="form-control d-none" id="productImage" name="product_image" accept="image/*" onchange="previewImage(event)">
                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                <p class="mb-1">Drag your file(s) or <a href="#" onclick="document.getElementById('productImage').click(); return false;">browse</a></p>
                                <small class="text-muted">Max 10 MB files are allowed</small>
                                <div id="imagePreview" class="mt-3">
                                    @if($productImage->image_path)
                                        <img src="{{ asset('storage/' . $productImage->image_path) }}" class="img-fluid" style="max-height: 150px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">PDF Name<span class="text-danger">*</span></label>
                            <input type="text" name="pdf_name" class="form-control" value="{{ old('pdf_name', $productImage->pdf_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Code</label>
                            <input type="text" name="product_code" class="form-control" value="{{ old('product_code', $productImage->product_code) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Color</label>
                            <input type="text" name="product_color" class="form-control" value="{{ old('product_color', $productImage->product_color) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Purchase Cost<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="purchase_cost" class="form-control" value="{{ old('purchase_cost', $productImage->purchase_cost) }}" required min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Selling Priceeee<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price', $productImage->selling_price) }}" required min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Discount Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price', $productImage->discount_price) }}" min="0">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="stock_available" id="stockAvailable" {{ $productImage->stock_available ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="stockAvailable">Stock Available</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Image
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <img src="${e.target.result}" class="img-fluid" style="max-height: 150px;">
            `;
        };
        reader.readAsDataURL(file);
    }
}

document.getElementById('editImageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('stock_available', document.getElementById('stockAvailable').checked ? 1 : 0);

    fetch('{{ route("product.image.update", $productImage->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product image updated successfully');
            window.location.href = '{{ route("product.edit", $product->id) }}';
        } else {
            alert('Error updating product image: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating product image');
    });
});
</script>
@endsection 