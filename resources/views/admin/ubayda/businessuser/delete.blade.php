@extends('admin/template-base')

@section('page-title', 'Detail of Business')

{{-- MAIN CONTENT PART --}}
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- FOR BREADCRUMBS --}}
        @include('admin.components.breadcrumb.simple', $breadcrumbs)

        {{-- MAIN PARTS --}}

        <div class="card">

            {{-- FIRST ROW,  FOR TITLE AND ADD BUTTON --}}
            <div class="d-flex justify-content-between">

                <div class="bd-highlight">
                    <h3 class="card-header">Detail of Business: {{ $data->name }}</h3>
                </div>

            </div>

            <div class="row m-2">

                <div class="col-md-8 col-xs-12">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 250px;" scope="col" class="bg-dark text-white">Business Name</th>
                                    <td>{{ $data->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="col" class="bg-dark text-white">Business Address</th>
                                    <td>{{ $data->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="col" class="bg-dark text-white">Business Type</th>
                                    <td>{{ $data->type }}</td>
                                </tr>
                                <tr>
                                    <th scope="col" class="bg-dark text-white">Status</th>
                                    <td>
                                        @if ($data->is_active)
                                            <span class="badge rounded-pill bg-success"> Active </span>
                                        @else
                                            <span class="badge rounded-pill bg-danger"> Inactive </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        @if (config('constant.CRUD.DISPLAY_TIMESTAMPS'))
                            @include('components.crud-timestamps', $data)
                        @endif

                    </div>

                </div>

            </div>

            {{-- BUSINESS OWNERS SECTION --}}
            <div class="card-body">
                <div class="p-2 bd-highlight">
                    <h4 class="card-header">Business Owners</h4>
                </div>

                <div class="table-responsive text-nowrap">
                    <!-- Table data with Striped Rows -->
                    <table class="table table-striped table-hover align-middle">

                        {{-- TABLE HEADER --}}
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $startNumber = 1;
                            @endphp
                            @foreach ($owners as $owner)
                                <tr>
                                    <td>{{ $startNumber++ }}</td>
                                    <td>{{ $owner->name }}</td>
                                    <td>{{ $owner->email }}</td>
                                    <td>{{ $owner->phone_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ROW FOR ADDITIONAL FUNCTIONALITY BUTTON --}}
            <div class="m-4">
                <a onclick="goBack()" class="btn btn-outline-secondary me-2"><i
                        class="tf-icons bx bx-left-arrow-alt me-2"></i>Back</a>

                @if (Session::get('MY_ACTIVE_BUSINESS') != $data->id)
                    <a class="btn btn-primary me-2" href="{{ route('ubayda.business.user.select', ['id' => $data->id]) }}"
                        title="switch to this business">
                        <i class='tf-icons bx bx-search me-2'></i>Switch to This Business</a>
                @endif

                <a class="btn btn-primary me-2" href="{{ route('ubayda.business.user.edit', ['business' => $data->id]) }}"
                    title="edit this business">
                    <i class='tf-icons bx bx-pencil me-2'></i>Edit</a>
            </div>

        </div>
    </div>

@endsection

@section('footer-code')

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

@endsection
