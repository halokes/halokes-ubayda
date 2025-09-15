@extends('admin/template-base')

@section('page-title', 'Edit Business')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        @include('admin.components.breadcrumb.simple', $breadcrumbs)

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Edit Business</h5>
                        <small class="text-muted float-end">* : must be filled</small>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ubayda.business.admin.update', $business->id) }}">
                            @csrf
                            @method('PUT') <!-- Required for PUT request -->

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">Business Name*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $business->name) }}" required />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="address">Business Address*</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $business->address) }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="type">Business Type*</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Business Type</option>
                                        @foreach ($listType as $type)
                                            <option value="{{ $type }}" {{ old('type', $business->type) == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <a href="{{ route('ubayda.business.admin.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Business</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
