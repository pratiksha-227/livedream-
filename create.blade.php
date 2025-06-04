@extends('layouts.app')
@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Zone Form Card -->
            <form action="{{ route('zones.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1 text-primary">Create Zone</h2>
                    </div>
                    <button class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm hover-lift" type="submit">
                        <i class="fas fa-save"></i>
                        <span>Save Zone</span>
                    </button>
                </div>

                <div class="card border-0 shadow-sm hover-lift">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="mb-0 text-primary">Zone Details</h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Zone Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Enter Zone Name"
                                   required>
                            <div class="invalid-feedback">
                                Please enter a Zone Name.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Coverage Area</label>
                            <textarea class="form-control" 
                                      name="area" 
                                      rows="3" 
                                      placeholder="Enter Coverage Area Details"></textarea>
                        </div>

                        <!-- Pricing Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-primary">Pricing Settings</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Base Price Multiplier <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="base_price_multiplier" 
                                           class="form-control" 
                                           value="1.00"
                                           step="0.01"
                                           min="0"
                                           required>
                                    <div class="form-text">Multiply base product price by this factor</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Shipping Cost <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="shipping_cost" 
                                           class="form-control" 
                                           value="0.00"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Minimum Price</label>
                                    <input type="number" 
                                           name="minimum_price" 
                                           class="form-control" 
                                           step="0.01"
                                           min="0">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Maximum Price</label>
                                    <input type="number" 
                                           name="maximum_price" 
                                           class="form-control" 
                                           step="0.01"
                                           min="0">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               name="apply_tax" 
                                               class="form-check-input" 
                                               id="applyTax"
                                               value="1">
                                        <label class="form-check-label" for="applyTax">Apply Tax</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Tax Percentage <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="tax_percentage" 
                                           class="form-control" 
                                           value="0.00"
                                           step="0.01"
                                           min="0"
                                           max="100"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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