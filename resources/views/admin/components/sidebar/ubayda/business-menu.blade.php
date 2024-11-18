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
            'subMenuUrl' => route('ubayda.business.index'),
        ],
    ],
])

@endif
