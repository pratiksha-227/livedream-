@extends('layouts.app')

@section('content')
<form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-primary">Edit Category</h2>
                        <button class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm" type="submit">
                            <i class="fas fa-save"></i>
                            <span>Update Category</span>
                        </button>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="text-primary">Category Details</h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Category Name<span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   value="{{ old('name', $category->name) }}" 
                                   placeholder="Enter Category Name" 
                                   required>
                            <div class="invalid-feedback">
                                Please enter a Category Name.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Description</label>
                            <textarea class="form-control" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Enter Category Description">{{ old('description', $category->description) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('categoryForm');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endsection
