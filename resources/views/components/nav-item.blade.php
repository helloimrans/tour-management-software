@props([
    'routeName',
    'iconClass',
    'label',
    'submenu' => [],
    'routeParams' => [],
    'dynamicRoutes' => [],
    'permissionKey' => [],
])

@php
    $isActive = $routeName ? request()->routeIs($routeName) : false;

    if (!$isActive && count($submenu) > 0) {
        foreach ($submenu as $subitem) {
            if (request()->routeIs($subitem['route'])) {
                $isActive = true;
                break;
            }
        }
    }

    // Check if the current route matches any of the dynamic routes
    $isActive =
        $isActive ||
        collect($dynamicRoutes)->contains(function ($route) {
            return request()->routeIs($route);
        });

    // Check if the user has the necessary permissions
    $hasPermission = true;

    if (!empty($permissionKey)) {
        if (auth()->check() && auth()->user()) {
            if (!auth()->user()->hasPermission($permissionKey)) {
                $hasPermission = false;
            }
        } else {
            // If user is not authenticated and permission is required, hide the menu item
            $hasPermission = false;
        }
    }
@endphp

@if($hasPermission)
    <li class="nav-item {{ $isActive ? 'menu-is-opening menu-open' : '' }}">
        <a href="{{ $routeName ? route($routeName, $routeParams ?? []) : '#' }}"
           class="nav-link {{ $isActive ? 'active' : '' }}">
            <i class="nav-icon fa-solid {{ $iconClass }}"></i>
            <p>
                {{ $label }}
                @if(count($submenu) > 0)
                    <i class="right fa-solid fa-chevron-left"></i>
                @endif
            </p>
        </a>

        @if(count($submenu) > 0)
            <ul class="nav nav-treeview">
                @foreach($submenu as $subitem)
                    <x-nav-item :routeName="$subitem['route']" :permissionKey="$subitem['permissionKey']" :iconClass="$subitem['icon']"  :label="$subitem['label']"
                                :dynamicRoutes="$dynamicRoutes"/>
                @endforeach
            </ul>
        @endif
    </li>
@endif
