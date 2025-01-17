@extends('admin/template-base')

@section('page-title', 'Add Business (Step 2/2)')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        @include('admin.components.breadcrumb.simple', $breadcrumbs)

        @if ($userIsSet)
            {{-- MAIN PARTS --}}
            <div class="row">
                <!-- Basic Layout -->
                <div class="col-xxl">

                    <div class="card mb-4">
                        @if (isset($alerts))
                            @include('admin.components.notification.general', $alerts)
                        @endif

                        @php
                            $disabled = !$userFound->is_active;
                        @endphp

                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Detail of the User</h4>
                        </div>
                        <div class="row m-2">

                            <div class="col-md-8 col-xs-12">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <th style="width: 250px;" scope="col" class="bg-secondary text-white">
                                                    Name</th>
                                                <td>{{ $userFound->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="bg-secondary text-white">Email</th>
                                                <td>{{ $userFound->email }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="bg-secondary text-white">Phone Number</th>
                                                <td>{{ $userFound->phone_number }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="bg-secondary text-white">Is Active</th>
                                                <td>
                                                    @if ($userFound->is_active)
                                                        <span class="badge rounded-pill bg-success"> Yes </span>
                                                    @else
                                                        <span class="badge rounded-pill bg-danger"> No </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Add Business</h5>
                            <small class="text-muted float-end">* : must be filled</small>
                        </div>
                        {{-- form validation error --}}
                        @include('admin.components.notification.error-validation', [
                            'field' => 'is_active',
                        ])

                        <div class="card-body">

                            <form method="POST" action="{{ route('ubayda.business.admin.store') }}">
                                @csrf



                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">User Id*</label>
                                    <div class="col-sm-10">
                                        {{-- form validation error --}}
                                        @include('admin.components.notification.error-validation', [
                                            'field' => 'user',
                                        ])


                                        {{-- input form --}}
                                        <input type="text" name="name" class="form-control" id="name" disabled
                                            placeholder="User" value="{{ $userFound->id }}">

                                        <input type="hidden" name="user" value="{{ $userFound->id }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">Business Name</label>
                                    <div class="col-sm-10">
                                        {{-- form validation error --}}
                                        @include('admin.components.notification.error-validation', [
                                            'field' => 'name',
                                        ])

                                        {{-- input form --}}
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Business Name" value="{{ old('name', null) }}">

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">Business Address</label>
                                    <div class="col-sm-10">
                                        {{-- form validation error --}}
                                        @include('admin.components.notification.error-validation', [
                                            'field' => 'address',
                                        ])


                                        {{-- input form --}}
                                        <textarea type="text" name="address" class="form-control" id="address" placeholder="Business Address"
                                            value="">
                                            {{ old('address', null) }}
                                        </textarea>

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">Business Type</label>
                                    <div class="col-sm-10">
                                        {{-- form validation error --}}
                                        @include('admin.components.notification.error-validation', [
                                            'field' => 'type',
                                        ])

                                        {{-- input form --}}
                                        <select name="type" class="form-control" id="type"
                                            placeholder="Business Type">
                                            <option disabled {{ old('type') ? '' : 'selected' }}>Choose your Business Type
                                            </option>
                                            @foreach (config('ubayda.BUSINESS_TYPE') as $businessType)
                                                <option value="{{ $businessType }}"
                                                    {{ old('type') == $businessType ? 'selected' : '' }}>
                                                    {{ $businessType }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                                {{-- IS_ACTIVE RADIO BUTTONS --}}
                                <div class="row mb-3">
                                    @php
                                        $oldIsActive = old('is_active', null);
                                    @endphp
                                    <label class="col-sm-2 col-form-label" for="is_active">Is Active*</label>
                                    <div class="col-sm-10">
                                        {{-- form validation error --}}
                                        @include('admin.components.notification.error-validation', [
                                            'field' => 'is_active',
                                        ])

                                        {{-- input form --}}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active"
                                                id="is_active_true" value="1"
                                                {{ (isset($oldIsActive) && $oldIsActive == 1) || !old('is_active') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active_true">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active"
                                                id="is_active_false" value="0"
                                                {{ isset($oldIsActive) && $oldIsActive == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active_false">No</label>
                                        </div>
                                    </div>
                                </div>

                                @if (!$disabled)
                                    <div class="row justify-content-end">
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Add Business</button>
                                        </div>
                                    </div>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </div>
@endsection
