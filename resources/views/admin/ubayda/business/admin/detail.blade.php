@extends('admin.template-base', ['searchNavbar' => false])

@section('page-title', 'Detail of User Business')

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
                    <h3 class="card-header">Detail of Business with id : {{ $data->id }}</h3>
                </div>

            </div>

            <div class="row m-2">

                <div class="col-md-8 col-xs-12">

                    @if (isset($alerts))
                        @include('admin.components.notification.general', $alerts)
                    @endif

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th scope="col" class="bg-dark text-white">Business Name</th>
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
                                    <th scope="col" class="bg-dark text-white">Is Active</th>
                                    <td>
                                        @if ($data->is_active)
                                            <span class="badge rounded-pill bg-success"> Yes </span>
                                        @else
                                            <span class="badge rounded-pill bg-danger"> No </span>
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



            {{-- ROW FOR ADDITIONAL FUNCTIONALITY BUTTON --}}
            <div class="m-4">
                <a onclick="goBack()" class="btn btn-outline-secondary me-2"><i
                        class="tf-icons bx bx-left-arrow-alt me-2"></i>Back</a>

                {{-- SUSPEND or UNSUSPEND Button --}}
                @if ($data->is_active)
                    <a class="btn btn-danger me-2" href="{{ route('ubayda.business.admin.suspend', ['id' => $data->id]) }}"
                        title="Suspend user">
                        <i class='tf-icons bx bx-pause me-2'></i>Deactivate</a>
                @else
                    <a class="btn btn-success me-2" href="{{ route('ubayda.business.admin.unsuspend', ['id' => $data->id]) }}"
                        title="Unsuspend user">
                        <i class='tf-icons bx bx-play me-2'></i>Activate</a>
                @endif

                {{-- NEW SUBSCRIPTION BUTTON --}}
                <a class="btn btn-success me-2" href="{{ route('ubayda.business.admin.add', ['user' => $data->user_id]) }}"
                    title="Suspend user">
                    <i class='tf-icons bx bx-plus me-2'></i>New Business</a>

            </div>


            {{-- SUBSCRIPTION HISTORY --}}

            <div class="row m-2">
                <div class="d-flex justify-content-between">

                    <div class="bd-highlight">
                        <h3 class="card-header">Business PIC</h3>
                    </div>

                </div>

                <div class="col-md-8 col-xs-12">
                    <div class="table-responsive text-nowrap">
                        @php
                            $startNumber = 1;
                        @endphp
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->users as $user)
                                    <tr>
                                        <td>{{ $startNumber++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if (strcasecmp($user->pivot->role, config('ubayda.UBAYDA_BUSINESS_OWNER')) == 0)
                                                <span class="badge rounded-pill bg-label-danger"> {{ $user->pivot->role }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-label-primary"> {{ $user->pivot->role }}
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <br />
                    <br />
                </div>

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
