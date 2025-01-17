{{-- SAAS MENU -- ADMIN ONLY --}}
@if (auth()->user()->hasRole('ROLE_ADMIN'))
    {{-- EXAMPLE MENU HEADER FOR GROUPING --}}
    @include('admin.components.sidebar.menu-header', ['textMenuHeader' => 'Ubayda Related'])

    {{-- PACKAGE MENU --}}
    {{-- EXAMPLE MENU WITH SUB MENU --}}
    @include('admin.components.sidebar.item', [
        'menuId' => 'ubayda-business',
        'menuText' => 'Business',
        'menuUrl' => '#',
        'menuIcon' => 'bx bx-briefcase', //check here for the icons https://boxicons.com/cheatsheet
        'subMenuData' => [
            [
                'subMenuText' => 'Business',
                'subMenuUrl' => route('ubayda.business.admin.index'),
            ],
        ],
    ])
@endif

{{-- ============================================================================================================================== --}}
{{--                    LIMIT OF USER RELATED BUSINESS                  --}}
{{-- ============================================================================================================================== --}}

{{-- SAAS MENU -- USER ONLY --}}
@if (auth()->user()->hasRole('ROLE_USER'))
    {{-- EXAMPLE MENU HEADER FOR GROUPING --}}
    @include('admin.components.sidebar.menu-header', ['textMenuHeader' => 'My Ubayda'])

    {{-- PACKAGE MENU --}}
    {{-- EXAMPLE MENU WITH SUB MENU --}}
    @include('admin.components.sidebar.item', [
        'menuId' => 'ubayda-business',
        'menuText' => 'My Business',
        'menuUrl' => '#',
        'menuIcon' => 'bx bx-briefcase', //check here for the icons https://boxicons.com/cheatsheet
        'subMenuData' => [
            [
                'subMenuText' => 'List My Business',
                'subMenuUrl' => route('ubayda.business.user.index'),
            ],
            [
                'subMenuText' => 'Access Sharing',
                'subMenuUrl' => route('ubayda.business.user.add'),
            ],
        ]
    ])
@endif
