@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">Category Details</h2>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i>Edit Category</a>
            </div>

            <div class="card border-0 shadow-sm hover-lift">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="mb-0 text-primary">Category Details</h5>
                    </div>

                    <form>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Category Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ $category->name }}"
                                   readonly>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Description</label>
                            <textarea class="form-control"
                                      name="description"
                                      rows="3"
                                      readonly>{{ $category->description }}</textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
