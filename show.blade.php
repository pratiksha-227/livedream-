@extends('layouts.app')
@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1 text-primary">View Zone</h2>
                </div>
                <a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm hover-lift">
                    <i class="fas fa-edit"></i>
                    <span>Edit Zone</span>
                </a>
            </div>

            <!-- Zone Form Card -->
            <div class="card border-0 shadow-sm hover-lift">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="mb-0 text-primary">Zone Details</h5>
                    </div>

                    <form action="{{ route('zones.update', $zone->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Zone Name<span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="name"  
                                    value="{{ $zone->name }}"
                                    class="form-control" 
                                    placeholder="Enter zone name"
                                    readonly>
                            <div class="invalid-feedback">
                                Please enter a zone name.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Coverage Area</label>
                            <textarea class="form-control" 
                                      name="area" 
                                      rows="3" 
                                      placeholder="Enter coverage area details"
                                      readonly>{{ $zone->area }}</textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const mainContent = document.getElementById('mainContent');
    const saveBtn = document.getElementById('zone');
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
    const form = document.querySelector('form');
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
