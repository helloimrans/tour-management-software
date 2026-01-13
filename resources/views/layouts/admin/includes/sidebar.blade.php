<aside class="main-sidebar sidebar-dark-primary custom-bg-blue elevation-4">
    <a href="{{ url('/') }}" class="brand-link logo-switch pb-4 border-0">
        @auth
            <span class="logo-xl">
                <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" style="height: 38px; margin-left: 0px" alt="{{ $settings->app_name ?? 'Logo' }}" />
            </span>
            <span class="logo-xs">
                <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" width="90%" alt="{{ $settings->app_name ?? 'Logo' }}" />
            </span>
        @endauth
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="overflow-y: auto;">
        <!-- Sidebar Menu -->
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @if(auth()->check() && auth()->user()->user_type == \App\Models\User::NORMAL_USER_CODE)
                    {{-- Member Menu --}}
                    <x-nav-item routeName="member.dashboard" iconClass="fa-gauge-high" label="Dashboard" />
                    <x-nav-item routeName="member.profile" iconClass="fa-user" label="My Profile" />
                    <x-nav-item routeName="" iconClass="fa-map-location-dot" label="Browse Tours" />
                    <x-nav-item routeName="" iconClass="fa-plane-departure" label="My Current Tour" />
                    <x-nav-item routeName="" iconClass="fa-history" label="Tour History" />
                    <x-nav-item routeName="" iconClass="fa-money-bill-wave" label="Add Payment" />
                    <x-nav-item routeName="" iconClass="fa-credit-card" label="Payment History" />
                @else
                    {{-- Admin Menu --}}
                    <x-nav-item routeName="admin.dashboard" permissionKey="dashboard-menu" iconClass="fa-gauge-high"
                        label="Dashboard" />

                    {{-- Tour Management --}}
                    <x-nav-item routeName="" iconClass="fa-map-marked-alt" permissionKey="tour-management-menu"
                        label="Tour Management" :submenu="[
                            [
                                'route' => '',
                                'icon' => 'fa-map-location-dot',
                                'permissionKey' => 'tour-menu',
                                'label' => 'Tours',
                            ],
                            [
                                'route' => '',
                                'icon' => 'fa-users',
                                'permissionKey' => 'member-management-menu',
                                'label' => 'Member Management',
                            ],
                        ]" />

                    {{-- Financial Management --}}
                    <x-nav-item routeName="" iconClass="fa-money-bill-wave" permissionKey="financial-menu"
                        label="Financial Management" :submenu="[
                            [
                                'route' => '',
                                'icon' => 'fa-receipt',
                                'permissionKey' => 'expense-menu',
                                'label' => 'Expenses',
                            ],
                            [
                                'route' => '',
                                'icon' => 'fa-list',
                                'permissionKey' => 'expense-menu',
                                'label' => 'Expense Categories',
                            ],
                            [
                                'route' => '',
                                'icon' => 'fa-credit-card',
                                'permissionKey' => 'payment-menu',
                                'label' => 'Payments',
                            ],
                        ]" />

                    {{-- User Management --}}
                    <x-nav-item routeName="" iconClass="fa-users-gear" permissionKey="user-management-menu"
                        label="User Management" :submenu="[
                            [
                                'route' => 'admin.user.index',
                                'icon' => 'fa-user-shield',
                                'permissionKey' => 'admin-user-menu',
                                'label' => 'Admin Users',
                            ],
                            [
                                'route' => 'general.user.index',
                                'icon' => 'fa-users',
                                'permissionKey' => 'general-user-menu',
                                'label' => 'Members',
                            ],
                        ]" />

                    {{-- Role & Permissions --}}
                    <x-nav-item routeName="" iconClass="fa-shield-halved" permissionKey="role-permission-menu"
                        label="Roles & Permissions" :submenu="[
                            [
                                'route' => 'roles.index',
                                'icon' => 'fa-user-tag',
                                'permissionKey' => 'roles-menu',
                                'label' => 'Roles',
                            ],
                            [
                                'route' => 'permissions.index',
                                'icon' => 'fa-lock',
                                'permissionKey' => 'permissions-menu',
                                'label' => 'Permissions',
                            ],
                        ]" />

                    <x-nav-item routeName="setting" iconClass="fa-gear" permissionKey="settings-menu" label="Settings" />
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
