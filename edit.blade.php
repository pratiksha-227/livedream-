@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Zone Form Card -->
            <form action="{{ route('zones.update', $zone->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0 text-primary">Edit Zone</h2>

                    <button class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm hover-lift" type="submit">
                        <i class="fas fa-edit"></i>
                        <span>Update Zone</span>
                    </button>
                </div>

                <div class="card border-0 shadow-sm hover-lift">
                    <div class="card-body p-4">
                        <h5 class="mb-4 text-primary">Zone Details</h5>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Zone Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                placeholder="Enter Zone Name"
                                value="{{ old('name', $zone->name) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                Please enter a Zone Name.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Coverage Area</label>
                            <textarea 
                                class="form-control" 
                                name="area" 
                                rows="3" 
                                placeholder="Enter Coverage Area Details"
                                required
                            >{{ old('area', $zone->area) }}</textarea>
                            <div class="invalid-feedback">
                                Please enter Coverage Area.
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Form Validation Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>

@endsection
