@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold text-primary">Category Management</h2>
                <a href="{{ route('category.create') }}" class="btn btn-primary px-2 hover-lift">
                    <i class="fas fa-plus"></i> Add New Category
                </a>
            </div>

            <form action="{{ route('categories.index') }}" method="GET" class="mb-3">
                <div class="card p-3 border-0 shadow-sm w-100 custom-card">
                    <div class="row g-2 align-items-center">
                        <!-- Search Bar: Full width on mobile, fixed width on larger screens -->
                        <div class="col-12 col-md-6">
                            <div class="input-group" style="width: 100%; max-width: 400px;">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fa-solid fa-magnifying-glass text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0"
                                    placeholder="Search categories..." name="search"
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary d-none" id="searchSubmitBtn"></button>
                            </div>
                        </div>

                        <!-- Buttons: On mobile, shown in second row -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <button type="button" class="btn btn-outline-danger hover-lift btn-sm" id="deleteToggle">
                                    <i class="fa-solid fa-trash"></i> Bulk Delete
                                </button>

                                <button type="submit" class="btn btn-danger hover-lift btn-sm d-none"
                                    id="confirmDeleteBtn" form="bulkDeleteForm">
                                    <i class="fa-solid fa-trash"></i> Delete Selected
                                </button>

                                <button class="btn btn-outline-primary hover-lift btn-sm" id="filterBtn" type="button">
                                    <i class="fa-solid fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>




 {{-- Close the search form here --}}


            {{-- Bulk Delete Form (now separate from search form) --}}
            <form action="{{ route('categories.bulk-delete') }}" method="POST" id="bulkDeleteForm" onsubmit="return confirm('Are you sure you want to delete selected categories?')">
                @csrf
                @method('DELETE')

                <div class="card mt-3 border-0 shadow-sm table-responsive custom-card">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="bulk-checkbox-header d-none"><input type="checkbox" id="selectAll"></th>
                                <th>Sr No</th>
                                <th>Category Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $index => $category)
                                <tr>
                                    <td class="bulk-checkbox-cell d-none"><input type="checkbox" name="ids[]" value="{{ $category->id }}" class="selectItem"></td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <div class="edit">
                                                <a class="dropdown-item text-info" href="{{ route('categories.show', $category->id) }}" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="edit">
                                                <a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                            <div class="edit">
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger border-0 bg-transparent" type="submit" title="Delete">
                                                        <i class="fa-solid fa-trash fa-lg" style="color: #ec1313;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form> {{-- Close the bulk delete form here --}}
        </div>
    </div>
</div>

<div id="filterSidebar" class="position-fixed bg-white shadow-lg p-4 custom-sidebar" style="right: -300px; top: 0; height: 100vh; width: 300px; transition: 0.3s; z-index: 1050;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 text-primary">Filter Options</h5>
        <button class="btn btn-light btn-sm hover-lift" id="closeFilter">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <hr>
    <div class="mb-4">
        <h6 class="mb-3 fw-bold">Status</h6>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="status1">
            <label class="form-check-label" for="status1">Active</label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="status2">
            <label class="form-check-label" for="status2">Inactive</label>
        </div>
    </div>
    <button class="btn btn-primary mt-3 w-100 hover-lift">Apply</button>
    <button class="btn btn-outline-secondary mt-2 w-100 hover-lift" id="closeFilterBottom">Close</button>
</div>

<div id="filterOverlay" class="position-fixed bg-dark bg-opacity-50" style="top: 0; left: 0; width: 100%; height: 100%; display: none; z-index: 1040;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter Sidebar Elements
        const filterBtn = document.getElementById('filterBtn');
        const filterSidebar = document.getElementById('filterSidebar');
        const closeFilterBtn = document.getElementById('closeFilter');
        const closeFilterBottomBtn = document.getElementById('closeFilterBottom');
        const filterOverlay = document.getElementById('filterOverlay');

        // Bulk Delete Elements
        const deleteToggleBtn = document.getElementById('deleteToggle');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.selectItem');
        const bulkCheckboxHeader = document.querySelector('.bulk-checkbox-header');
        const bulkCheckboxCells = document.querySelectorAll('.bulk-checkbox-cell');

        // Search Elements
        const searchInput = document.querySelector('input[name="search"]'); // Select by name
        const searchForm = searchInput.closest('form'); // Get the parent form of the search input

        let deleteModeActive = false; // Tracks the state of bulk delete mode

        // --- Filter Sidebar Logic ---
        filterBtn.addEventListener('click', function() {
            filterSidebar.style.right = '0';
            filterOverlay.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
        });

        const closeFilter = () => {
            filterSidebar.style.right = '-300px';
            filterOverlay.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        };

        closeFilterBtn.addEventListener('click', closeFilter);
        closeFilterBottomBtn.addEventListener('click', closeFilter);
        filterOverlay.addEventListener('click', closeFilter);

        // --- Bulk Delete Logic ---
        deleteToggleBtn.addEventListener('click', function() {
            deleteModeActive = !deleteModeActive; // Toggle the state

            // Toggle visibility of the "Delete Selected" button
            confirmDeleteBtn.classList.toggle('d-none', !deleteModeActive);
            // Change "Bulk Delete" button text/icon for better UX (optional)
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

        // --- Search Logic (Submit form on Enter key) ---
        searchInput.addEventListener('keypress', function(event) {
            // Check if the Enter key was pressed (key code 13)
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent default form submission to handle it manually
                searchForm.submit(); // Submit the search form
            }
        });
    });
</script>

@endsection