@extends('admin/template-base')

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
                        <form method="POST" action="{{ route('ubayda.business.user.update', $business->id) }}">
                            @csrf
                            @method('PUT') <!-- Required for PUT request -->

                            {{-- NAME FIELD --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">Name*</label>
                                <div class="col-sm-10">
                                    @include('admin.components.notification.error-validation', [
                                        'field' => 'name',
                                    ])
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control form-control-lg"
                                        id="name"
                                        placeholder="Business Name"
                                        value="{{ old('name', $business->name) }}"
                                        required>
                                </div>
                            </div>

                            {{-- ADDRESS FIELD --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="address">Address*</label>
                                <div class="col-sm-10">
                                    @include('admin.components.notification.error-validation', [
                                        'field' => 'address',
                                    ])
                                    <textarea
                                        name="address"
                                        class="form-control form-control-lg"
                                        id="address"
                                        placeholder="Business Address"
                                        required>{{ old('address', $business->address) }}</textarea>
                                </div>
                            </div>

                            {{-- TYPE FIELD --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="type">Type*</label>
                                <div class="col-sm-10">
                                    @include('admin.components.notification.error-validation', [
                                        'field' => 'type',
                                    ])
                                    <select name="type" class="form-control" id="type" required>
                                        <option value="" disabled>Select a type</option>
                                        @foreach ($listType as $type)
                                            <option value="{{ $type }}"
                                                {{ old('type', $business->type) == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- IS_ACTIVE RADIO BUTTONS --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="is_active">Is Active*</label>
                                <div class="col-sm-10">
                                    @include('admin.components.notification.error-validation', ['field' => 'is_active'])
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_active"
                                            id="is_active_true"
                                            value="1"
                                            {{ old('is_active', $business->is_active) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active_true">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_active"
                                            id="is_active_false"
                                            value="0"
                                            {{ old('is_active', $business->is_active) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active_false">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
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
