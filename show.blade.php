@extends('layouts.app')
@section('content')

<div class="content">
    <div class="d-flex flex-row justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0">View Product</h2>
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
                        <label class="form-label fw-bold">Company</label>
                        <p class="form-control-plaintext">{{ $product->company->name ?? '--' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <p class="form-control-plaintext">{{ $product->category->name ?? '--' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name</label>
                        <p class="form-control-plaintext">{{ $product->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Application Area</label>
                        <p class="form-control-plaintext">{{ $product->application_area ?? '--' }}</p>
                    </div>

                    <h5 class="text-primary mb-3">Product Size</h5>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="form-label">Length</label>
                            <p class="form-control-plaintext">{{ $product->length ?? '0' }}</p>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Width</label>
                            <p class="form-control-plaintext">{{ $product->width ?? '0' }}</p>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Thickness</label>
                            <p class="form-control-plaintext">{{ $product->thickness ?? '0' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Unit</label>
                            <p class="form-control-plaintext">{{ $product->unit ?? '--' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Additional Parameters</label>
                            @if(isset($product->other_parameters))
                                @php
                                    $customParams = is_string($product->other_parameters) ? json_decode($product->other_parameters, true) : $product->other_parameters;
                                @endphp
                                @if(!empty($customParams))
                                    @foreach($customParams as $key => $value)
                                        <p class="form-control-plaintext"><strong>{{ $key }}:</strong> {{ $value }}</p>
                                    @endforeach
                                @else
                                    <p class="form-control-plaintext">--</p>
                                @endif
                            @else
                                <p class="form-control-plaintext">--</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">GST Applied (%)</label>
                        <p class="form-control-plaintext">{{ $product->gst_percentage ?? '0' }}%</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Warranty Period</label>
                        <p class="form-control-plaintext">
                            @if($product->warranty_period)
                                {{ $product->warranty_period }} {{ $product->warranty_type }}
                            @else
                                No Warranty
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="m-3">
                    <label class="form-label fw-bold">Adhesive</label>
                    <p class="form-control-plaintext">{{ $product->adhesive->name ?? '--' }}</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Labor Charges (per sq.ft)</label>
                        <p class="form-control-plaintext">₹{{ $product->labor_charges ?? '0' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Estimated Delivery Time</label>
                        <p class="form-control-plaintext">
                            @if($product->delivery_duration)
                                {{ $product->delivery_duration }} {{ $product->delivery_unit }}
                            @else
                                --
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product Images Section -->
            <div class="card shadow-sm mb-4">
                <div class="m-3">
                    <h5 class="text-primary mb-4"><i class="fas fa-images me-2"></i>Product Images</h5>

                    <div class="container mt-4">
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
                                    </tr>
                                </thead>
                                <tbody>
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
                                            <td>
                                                @if($p->stock_available)
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Not Available</span>
                                                @endif
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
            <div class="card shadow-sm mb-4">
                <div class="m-3">
                    <h5 class="text-primary mb-4"><i class="fas fa-images me-2"></i>Sample Images</h5>
                    
                    @if($product->sampleImages->isNotEmpty())
                        <div class="row g-3">
                            @foreach($product->sampleImages as $sample)
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-2">
                                            @if($sample->image_path)
                                                <img src="{{ asset('storage/' . $sample->image_path) }}" 
                                                     alt="Sample Image" 
                                                     class="img-fluid rounded cursor-pointer"
                                                     style="width: 100%; height: 300px; object-fit: contain; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal{{ $sample->id }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-white rounded" 
                                                     style="width: 100%; height: 300px;">
                                                    <i class="fas fa-image text-muted fa-2x"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for each image -->
                                <div class="modal fade" id="imageModal{{ $sample->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $sample->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel{{ $sample->id }}">Sample Image</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $sample->image_path) }}" 
                                                     alt="Sample Image" 
                                                     class="img-fluid"
                                                     style="max-height: 80vh;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-images text-muted fa-3x mb-3"></i>
                            <p class="text-muted mb-0">No sample images available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 