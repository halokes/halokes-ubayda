@extends('admin/template-base')

@section('page-title', 'List of Businesss')

{{-- MAIN CONTENT PART --}}
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- FOR BREADCRUMBS --}}
        @include('admin.components.breadcrumb.simple', $breadcrumbs)

        {{-- MAIN PARTS --}}

        <div class="card">

            {{-- FIRST ROW,  FOR TITLE AND ADD BUTTON --}}
            <div class="d-flex justify-content-between">

                <div class="p-2 bd-highlight">
                    <h2 class="card-header">List of My Business</h2>
                </div>

                <div class="p-2">
                    <a class="btn btn-primary" href="{{ route('ubayda.business.user.add') }}">
                        <span class="tf-icons bx bx-plus"></span>&nbsp;
                        Add New Business
                    </a>
                </div>
            </div>

            {{-- ============================================================================================== --}}
            {{--                                  BUSINESS THAT OWNER                                       --}}
            {{-- ============================================================================================== --}}
            <div class="card-body">
                {{-- THIRD ROW, FOR THE MAIN DATA PART --}}
                @if (isset($alerts))
                    @include('admin.components.notification.general', $alerts)
                @endif

                <div class="p-2 bd-highlight">
                    <h4 class="card-header">My Owned Business</h4>
                </div>

                <div class="table-responsive text-nowrap">
                    <!-- Table data with Striped Rows -->
                    <table class="table table-striped table-hover align-middle">

                        {{-- TABLE HEADER --}}
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th
                                    style="max-width: 250px; word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                                    Business Name
                                </th>
                                <th
                                    style="max-width: 300px; word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                                    Business Address
                                </th>
                                <th>
                                    Business Type
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>


                        <tbody>
                            @php
                                $startNumber = 1;
                            @endphp
                            @foreach ($businesses as $business)
                                @if ($business->role == config('ubayda.UBAYDA_BUSINESS_OWNER'))
                                    <tr>
                                        <td>{{ $startNumber++ }}</td>
                                        <td style="max-width: 250px; overflow-wrap: break-word; white-space: normal;">
                                            {{ $business->name }}</td>
                                        <td style="max-width: 250px; overflow-wrap: break-word; white-space: normal;">
                                            {{ $business->address }}
                                        </td>
                                        <td>
                                            {{ $business->type }}
                                        </td>
                                        {{-- ============ CRUD LINK ICON =============  --}}
                                        <td>
                                            @if (!is_null($activeBusiness) && ($business->id == $activeBusiness))
                                                <strong class="text-danger">Current Active Business</strong>
                                            @else
                                                <a class="action-icon btn btn-primary text-white"
                                                    href="{{ route('ubayda.business.user.select', ['id' => $business->id]) }}"
                                                    title="detail">
                                                    <i class='bx bx-search text-white'></i>
                                                    Switch to This Business
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="action-icon"
                                                href="{{ route('ubayda.business.user.edit', ['business' => $business->id]) }}"
                                                title="edit">
                                                <i class='bx bx-pencil'></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="action-icon"
                                                href="{{ route('ubayda.business.user.delete', ['id' => $business->id]) }}"
                                                title="delete">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    <br />

                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            {{ $businesses->onEachSide(5)->links('admin.components.paginator.default') }}
                        </div>
                    </div>
                </div>

                <hr />
                {{-- ============================================================================================== --}}
                {{--                                  BUSINESS THAT NOT OWNER                                       --}}
                {{-- ============================================================================================== --}}

                <div class="p-2 bd-highlight">
                    <h4 class="card-header">Business I Have Access To</h4>
                </div>

                <div class="table-responsive text-nowrap">
                    <!-- Table data with Striped Rows -->
                    <table class="table table-striped table-hover align-middle">

                        {{-- TABLE HEADER --}}
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th
                                    style="max-width: 250px; word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                                    Business Name
                                </th>
                                <th
                                    style="max-width: 300px; word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                                    Business Address
                                </th>
                                <th>
                                    Business Type
                                </th>
                                <th>Role</th>
                                <th></th>
                            </tr>
                        </thead>


                        <tbody>
                            @php
                                $startNumber = 1;
                            @endphp
                            @foreach ($businesses as $business)
                                @if ($business->role != config('ubayda.UBAYDA_BUSINESS_OWNER'))
                                    <tr>
                                        <td>{{ $startNumber++ }}</td>
                                        <td style="max-width: 250px; overflow-wrap: break-word; white-space: normal;">
                                            {{ $business->name }}</td>
                                        <td style="max-width: 250px; overflow-wrap: break-word; white-space: normal;">
                                            {{ $business->address }}
                                        </td>
                                        <td>
                                            {{ $business->type }}
                                        </td>
                                        <td>
                                            {{ $business->role }}
                                        </td>
                                        {{-- ============ CRUD LINK ICON =============  --}}
                                        <td>
                                            @if (!is_null($activeBusiness) && ($business->id == $activeBusiness))
                                                <strong class="text-danger">Current Active Business</strong>
                                            @else
                                                <a class="action-icon btn btn-primary text-white"
                                                    href="{{ route('ubayda.business.user.select', ['id' => $business->id]) }}"
                                                    title="detail">
                                                    <i class='bx bx-search text-white'></i>
                                                    Switch to This Business
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    <br />

                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            {{ $businesses->onEachSide(5)->links('admin.components.paginator.default') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
