@extends('layouts.app')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1 text-primary">Products</h2>
                    <p class="text-muted mb-0">Manage your product inventory</p>
                </div>
                <a class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm hover-lift" href="/products">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>

            <!-- Search & Filter Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <!-- Search -->
                        <div class="input-group search-group" style="max-width: 400px;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-magnifying-glass text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="searchInput" placeholder="Search products...">
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2 hover-lift" id="deleteToggle">
                                <i class="fa-solid fa-trash"></i> Bulk Delete
                            </button>
                            <button type="submit" class="btn btn-danger d-flex align-items-center gap-2 hover-lift d-none" id="confirmDeleteBtn" form="bulkDeleteForm">
                                <i class="fa-solid fa-trash"></i> Delete Selected
                            </button>
                            <button class="btn btn-outline-primary d-flex align-items-center gap-2 hover-lift" id="filterBtn">
                                <i class="fa-solid fa-filter"></i>
                                <span>Filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table Card -->
            <form action="{{ route('products.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                @csrf
                @method('DELETE')
            <div class="card border-0 shadow-sm hover-lift">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                        <th class="border-0 bulk-checkbox-header d-none" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th class="border-0 text-center" style="width: 60px;">Sr No</th>
                                    <th class="border-0">Product</th>
                                    <th class="border-0 text-center">Code</th>
                                    <th class="border-0 text-center">Company</th>
                                    <th class="border-0 text-center">Category</th>
                                    <th class="border-0 text-center">Warranty</th>
                                    <th class="border-0 text-center">Price</th>
                                    <th class="border-0 text-center">Selling Price</th>
                                    <th class="border-0 text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                        <tr class="hover-lift" data-product-id="{{ $product->id }}">
                                            <td class="bulk-checkbox-cell d-none">
                                            <div class="form-check">
                                                    <input class="form-check-input selectItem" type="checkbox" name="ids[]" value="{{ $product->id }}">
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $products->firstItem() + $index }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="product-image shadow-sm" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden;">
                                                    @if ($product->images->isNotEmpty() && $product->images->first()->image_path)
                                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                            alt="{{ $product->name }}" 
                                                            class="w-100 h-100 object-fit-cover">
                                                    @else
                                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark shadow-sm">
                                                {{ $product->images->first()->product_code ?? '--' }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $product->company->name ?? '--' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary shadow-sm">
                                                {{ $product->category->name ?? '--' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($product->warranty_period)
                                                 <span class="badge bg-success-subtle text-success shadow-sm">
                                                     {{ $product->warranty_period }} {{ $product->warranty_type }}
                                                 </span>
                                             @else

                                                <span class="badge bg-secondary-subtle text-secondary shadow-sm">No Warranty</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-medium">
                                                @if($product->images->first())
                                                    {{ $product->images->first()->purchase_cost ? '₹' . $product->images->first()->purchase_cost : '--' }}
                                                @else
                                                    --
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-medium text-success">
                                                @if($product->images->first())
                                                    {{ $product->images->first()->selling_price ? '₹' . $product->images->first()->selling_price : '--' }}
                                                @else
                                                    --
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                <div class="d-flex gap-2">
                                                    <a class="dropdown-item hover-lift" href="{{ route('product.show', $product->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                                    <a class="dropdown-item hover-lift" href="{{ route('product.edit', $product->id) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                                    <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger border-0 bg-transparent" type="submit" title="Delete">
                                            <i class="fa-solid fa-trash fa-lg" style="color: #ec1313;"></i> 
                                        </button>
                                    </form>
                                </div>
                            </td>
                                        

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end mt-3">
            {{-- Previous Button --}}
            @if ($products->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $products->previousPageUrl() }}">Previous</a>
            </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
            @endforeach

            {{-- Next Button --}}
            @if ($products->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
            @endif
        </ul>
    </nav>



    
</div>

<!-- Filter Sidebar -->
<div id="filterSidebar" class="position-fixed bg-white shadow-lg" style="right: -350px; top: 0; height: 100vh; width: 350px; transition: 0.3s; z-index: 1050;">
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 text-primary">Filter Products</h5>
            <button class="btn btn-light btn-sm hover-lift" id="closeFilter">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <h6 class="mb-3 fw-bold">Company</h6>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="company1">
                <label class="form-check-label" for="company1">Company A</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="company2">
                <label class="form-check-label" for="company2">Company B</label>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="mb-3 fw-bold">Price Range</h6>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="price1">
                <label class="form-check-label" for="price1">₹0 - ₹1000</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="price2">
                <label class="form-check-label" for="price2">₹1001 - ₹5000</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="price3">
                <label class="form-check-label" for="price3">₹5000+</label>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="mb-3 fw-bold">Category</h6>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="category1">
                <label class="form-check-label" for="category1">Category X</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="category2">
                <label class="form-check-label" for="category2">Category Y</label>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary shadow-sm hover-lift">Apply Filters</button>
            <button class="btn btn-outline-secondary shadow-sm hover-lift">Reset Filters</button>
        </div>
    </div>
</div>

<!-- Overlay for Filter Sidebar -->
<div id="filterOverlay" class="position-fixed bg-dark bg-opacity-50" style="top: 0; left: 0; width: 100%; height: 100%; display: none; z-index: 1040;"></div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const productRows = document.querySelectorAll('tbody tr');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.toLowerCase().trim();

            searchTimeout = setTimeout(() => {
                productRows.forEach(row => {
                    const productName = row.querySelector('h6').textContent.toLowerCase();
                    const productCode = row.querySelector('.badge.bg-light').textContent.toLowerCase();
                    const company = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    const category = row.querySelector('.badge.bg-primary-subtle').textContent.toLowerCase();

                    const matches = productName.includes(searchTerm) ||
                                  productCode.includes(searchTerm) ||
                                  company.includes(searchTerm) ||
                                  category.includes(searchTerm);

                    row.style.display = matches ? '' : 'none';
                });

                // Show/hide pagination based on search results
                const visibleRows = document.querySelectorAll('tbody tr[style=""]').length;
                const pagination = document.querySelector('.pagination');
                if (pagination) {
                    pagination.style.display = searchTerm ? 'none' : 'flex';
                }
            }, 300); // 300ms debounce
        });

        // Filter Sidebar Elements
    const filterBtn = document.getElementById('filterBtn');
    const filterSidebar = document.getElementById('filterSidebar');
        const closeFilterBtn = document.getElementById('closeFilter');
    const filterOverlay = document.getElementById('filterOverlay');

        // Bulk Delete Elements
        const deleteToggleBtn = document.getElementById('deleteToggle');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.selectItem');
        const bulkCheckboxHeader = document.querySelector('.bulk-checkbox-header');
        const bulkCheckboxCells = document.querySelectorAll('.bulk-checkbox-cell');

        let deleteModeActive = false; // Tracks the state of bulk delete mode

        // --- Filter Sidebar Logic ---
    filterBtn.addEventListener('click', function() {
        filterSidebar.style.right = '0';
        filterOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

        const closeFilter = () => {
        filterSidebar.style.right = '-350px';
        filterOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
        };

        closeFilterBtn.addEventListener('click', closeFilter);
        filterOverlay.addEventListener('click', closeFilter);

        // --- Bulk Delete Logic ---
        deleteToggleBtn.addEventListener('click', function() {
            deleteModeActive = !deleteModeActive; // Toggle the state

            // Toggle visibility of the "Delete Selected" button
            confirmDeleteBtn.classList.toggle('d-none', !deleteModeActive);
            
            // Change "Bulk Delete" button text/icon
            if (deleteModeActive) {
                deleteToggleBtn.innerHTML = '<i class="fa-solid fa-times"></i> Cancel Delete';
                deleteToggleBtn.classList.remove('btn-outline-danger');
                deleteToggleBtn.classList.add('btn-secondary');
            } else {
                deleteToggleBtn.innerHTML = '<i class="fa-solid fa-trash"></i> Bulk Delete';
                deleteToggleBtn.classList.remove('btn-secondary');
                deleteToggleBtn.classList.add('btn-outline-danger');
            }

            // Toggle visibility of checkboxes and their header
            bulkCheckboxHeader.classList.toggle('d-none', !deleteModeActive);
            bulkCheckboxCells.forEach(cell => {
                cell.classList.toggle('d-none', !deleteModeActive);
            });

            // If disabling delete mode, uncheck all checkboxes
            if (!deleteModeActive) {
                selectAllCheckbox.checked = false;
                itemCheckboxes.forEach(cb => cb.checked = false);
            }
        });

        // "Select All" checkbox functionality
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Individual item checkbox functionality
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // If any individual checkbox is unchecked, uncheck "Select All"
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // If all individual checkboxes are checked, check "Select All"
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });

        // Handle individual delete form submissions
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                    const url = this.action;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Remove the row from the table
                            const row = this.closest('tr');
                            row.remove();
                            // Show success message
                            alert(data.message);
                        } else {
                            throw new Error(data.message || 'Error deleting product');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting product: ' + error.message);
                    });
                }
            });
        });

        // Add form submission handler for bulk delete
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        if (bulkDeleteForm) {
            bulkDeleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const selectedIds = Array.from(document.querySelectorAll('.selectItem:checked'))
                    .map(checkbox => checkbox.value);

                if (selectedIds.length === 0) {
                    alert('Please select at least one product to delete');
                    return;
                }

                if (confirm(`Are you sure you want to delete ${selectedIds.length} selected product(s)? This action cannot be undone.`)) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove deleted rows
                            selectedIds.forEach(id => {
                                const row = document.querySelector(`tr[data-product-id="${id}"]`);
                                if (row) row.remove();
                            });
                            // Reset select all checkbox
                            selectAllCheckbox.checked = false;
                            // Exit delete mode
                            deleteModeActive = false;
                            deleteToggleBtn.click();
                            // Show success message
                            alert(data.message);
                        } else {
                            // Show error message without proceeding
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting products: ' + error.message);
                    });
                }
            });
        }
    });
</script>

@endsection
