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
                    <h3 class="card-header">List of Business</h3>
                </div>
                <div class="p-2">
                    <a class="btn btn-primary" href="{{ route('ubayda.business.add') }}">
                        <span class="tf-icons bx bx-plus"></span>&nbsp;
                        Add New Business
                    </a>
                </div>

            </div>

            {{-- SECOND ROW,  FOR DISPLAY PER PAGE AND SEARCH FORM --}}
            <div class="d-flex justify-content-between">

                {{-- OPTION TO SHOW LIST PER PAGE --}}
                <div class="p-2 bd-highlight">
                    @include('admin.components.paginator.perpageform')
                </div>

                {{-- SEARCH FORMS --}}
                <div class="p-2 d-flex align-items-center">
                    <form action="{{ url()->full() }}" method="get" class="d-flex align-items-center">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input type="text" class="form-control border-1 shadow-none bg-light bg-gradient"
                            placeholder="Search name or address.." aria-label="Search name or address..." name="keyword"
                            value="{{ isset($keyword) ? $keyword : '' }}" />
                        <input type="hidden" name="sort_order" value="{{ request()->input('sort_order') }}" />
                        <input type="hidden" name="sort_field" value="{{ request()->input('sort_field') }}" />
                        <input type="hidden" name="per_page" value="{{ request()->input('per_page') }}" />
                    </form>
                </div>

            </div>

            {{-- THIRD ROW, FOR THE MAIN DATA PART --}}
            {{-- //to display any error if any --}}
            @if (isset($alerts))
                @include('admin.components.notification.general', $alerts)
            @endif

            <div class="table-responsive text-nowrap">
                <!-- Table data with Striped Rows -->
                <table class="table table-striped table-hover align-middle">

                    {{-- TABLE HEADER --}}
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th style="max-width: 250px; word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                                <a
                                    href="{{ route('ubayda.business.index', [
                                        'sort_field' => 'name',
                                        'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc',
                                        'keyword' => $keyword,
                                    ]) }}">
                                    Business Name
                                    @include('components.arrow-sort', [
                                        'field' => 'name',
                                        'sortField' => $sortField,
                                        'sortOrder' => $sortOrder,
                                    ])
                                </a>
                            </th>
                            <th style="max-width: 300px; word-wrap: break-word; white-space: normal; overflow-wrap: break-word;">
                                <a
                                    href="{{ route('ubayda.business.index', [
                                        'sort_field' => 'address',
                                        'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc',
                                        'keyword' => $keyword,
                                    ]) }}">
                                    Business Address
                                    @include('components.arrow-sort', [
                                        'field' => 'address',
                                        'sortField' => $sortField,
                                        'sortOrder' => $sortOrder,
                                    ])
                                </a>
                            </th>
                            <th>
                                <a
                                    href="{{ route('ubayda.business.index', ['sort_field' => 'type', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'keyword' => $keyword]) }}">
                                    Business Type
                                    @include('components.arrow-sort', [
                                        'field' => 'type',
                                        'sortField' => $sortField,
                                        'sortOrder' => $sortOrder,
                                    ])
                                </a>
                            </th>
                            <th>
                                <a
                                    href="{{ route('ubayda.business.index', ['sort_field' => 'owner_email', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'keyword' => $keyword]) }}">
                                    Owner
                                    @include('components.arrow-sort', [
                                        'field' => 'owner_email',
                                        'sortField' => $sortField,
                                        'sortOrder' => $sortOrder,
                                    ])
                                </a>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>


                    <tbody>
                        @php
                            $startNumber = $perPage * ($page - 1) + 1;
                        @endphp
                        @foreach ($businesses as $business)
                            <tr>
                                <td>{{ $startNumber++ }}</td>
                                <td style="max-width: 250px; overflow-wrap: break-word; white-space: normal;">{{ $business->name }}</td>
                                <td style="max-width: 300px; overflow-wrap: break-word; white-space: normal;">
                                    {{ $business->address }}
                                </td>
                                <td>{{ $business->type }}</td>
                                <td><a href="{{ route('admin.user.detail', ['id' => $business->owner_id]) }}"
                                        target="_blank"> {{ $business->owner_name }}
                                        <br />{{ $business->owner_email }}</a></td>

                                {{-- ============ CRUD LINK ICON =============  --}}
                                <td>
                                    <a class="action-icon"
                                        href="{{ route('ubayda.business.detail', ['id' => $business->id]) }}"
                                        title="detail">
                                        <i class='bx bx-search'></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="action-icon"
                                        href="{{ route('ubayda.business.edit', ['id' => $business->id]) }}" title="edit">
                                        <i class='bx bx-pencil'></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="action-icon"
                                        href="{{ route('ubayda.business.delete', ['id' => $business->id]) }}"
                                        title="delete">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                </td>
                            </tr>
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
@endsection
