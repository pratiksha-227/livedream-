@extends('layouts.app')

@section('content')
<form action="{{ route('categories.store') }}" method="POST" id="categoryForm" class="needs-validation" novalidate>
    @csrf           

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1 text-primary">Create Category</h2>
                    </div>
                    <button class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm hover-lift" type="submit" id="category">
                        <i class="fas fa-save"></i>
                        <span>Save Category</span>
                    </button>
                </div>

                <!-- Category Form Card -->
                <div class="card border-0 shadow-sm hover-lift">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="mb-0 text-primary">Category Details</h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Category Name<span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
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
                                      placeholder="Enter Category Description (optional)"></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const mainContent = document.getElementById('mainContent');
    const saveBtn = document.getElementById('category');
    const scrollThreshold = 100;

    if (mainContent && saveBtn) {
        mainContent.addEventListener('scroll', () => {
            if (mainContent.scrollTop > scrollThreshold) {
                saveBtn.classList.add('fixed-save-btn');
            } else {
                saveBtn.classList.remove('fixed-save-btn');
            }
        });
    }

    // Form validation
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
