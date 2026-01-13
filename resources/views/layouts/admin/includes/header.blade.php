<nav class="main-header navbar navbar-expand navbar-white navbar-dark custom-bg-blue">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa-solid fa-bars"></i></a>
        </li>
        @auth
        <li class="nav-item d-none d-sm-block">
            <a class="nav-link" href="{{ route('landing') }}" target="_blank" title="Go to Website">
                <i class="fa-solid fa-globe"></i> Go To Website
            </a>
        </li>
        @endauth
    </ul>

    <ul class="navbar-nav ml-auto">
        @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fa-regular fa-user"></i>
                </a>
            </li>
        @else
            <li class="nav-item dropdown" id="notificationContainer">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa-regular fa-bell" style="font-size: 22px"></i>
                    <span class="badge bg-danger navbar-badge"
                        id="notiicationCount">{{ count(Auth::user()->unreadNotifications) }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right notification-dd">
                    <span class="dropdown-item dropdown-header">
                        Notifications
                        @if (count(Auth::user()->unreadNotifications) > 0)
                            <a href="" class="mark-all-as-read"><i class="fa-solid fa-circle mr-1"></i> Mark all
                                as read</a>
                        @endif
                    </span>
                    <div class="dropdown-divider"></div>

                    @forelse(Auth::user()->notifications as $notification)
                        <div class="media notifications-box">
                            <button type="button" data-notification-id="{{ $notification->id }}"
                                class="notification-close remove-notification"><span
                                    aria-hidden="true">&times;</span></button>
                            <img src="{{ asset('frontend/images/notifications.svg') }}" class="mr-3" alt="">
                            <div class="media-body">
                                <a href="javascript:;" data-notification-id="{{ $notification->id }}" class="mark-and-view">
                                    <p>{{ $notification->data['heading'] }} @if (!$notification->read_at)
                                            <i class="fa-solid fa-circle custom-color-danger ml-1 fs-10"></i>
                                        @endif
                                    </p>
                                    <h6>{{ $notification->data['text'] }}</h6>
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center p-3 fs-15">No notifications found.</p>
                    @endforelse
                </div>

            </li>
            <li class="nav-item dropdown">
                <a class="nav-link text-sm" data-toggle="dropdown" href="#">
                    <i class="fa-solid fa-circle-user" style="font-size: 22px"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right profile-dd">
                    <span class="dropdown-header">
                        <div class="profile-dd-logo custom-bg-blue">
                            <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" style="height: 100px;" alt="{{ $settings->app_name ?? 'Logo' }}">
                        </div>
                        <div class="profile-dd-info">
                            @if (auth()->user()->profile_pic)
                                <img class="profile-dd-avatar" src="{{ asset('storage/' . auth()->user()->profile_pic) }}"
                                    alt="img">
                            @else
                                <img class="profile-dd-avatar" src="{{ asset('defaults/avatar/avatar.png') }}"
                                    alt="img">
                            @endif
                            <div class="profile-dd-name d-flex justify-content-between">
                                <div>
                                    <h6>{{ auth()->user()->name }}</h6>
                                    <p>Admin</p>
                                    <h6 class="mt-3"><i class="fa-solid fa-phone mr-3"></i> {{ auth()->user()->phone }}</h6>
                                </div>
                                <div>
                                    {{-- <a href="javascript:;" class="custom-bg-secondary text-light">Edit</a> --}}
                                </div>
                            </div>
                        </div>
                    </span>

                    <div class="dropdown-divider"></div>
                    <div class="profile-dd-footer">
                        <a href="{{ route('admin.logout') }}"
                            class="dropdown-item dropdown-footer custom-bg-blue text-light"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>

<style>
    .main-header-search {
        background: #F79522;
    }
</style>

@push('js')
    <script>
        function getNotifications() {
            $.ajax({
                url: "{{ url('/notification/get-all-notifications') }}",
                success: function(data) {
                    $('#notificationContainer').html(data);
                }
            });
        }
        getNotifications();
    </script>

    <script>
        $(document).ready(function() {
            //Mark and view
            $(document).on('click', '.mark-and-view', function(event) {
                event.preventDefault();
                var notificationId = $(this).data('notification-id');
                $.ajax({
                    url: '/notification/mark-as-read/' + notificationId,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.data.data.url !== '#') {
                                window.location.href = response.data.data.url
                            } else {
                                getNotifications();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            //Mark all as read
            $(document).on('click', '.mark-all-as-read', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '/notification/mark-all-as-read',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            getNotifications();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            //Remove notification
            $(document).on('click', '.remove-notification', function(event) {
                event.preventDefault();
                var notificationId = $(this).data('notification-id');
                $.ajax({
                    url: '/notification/remove/' + notificationId,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            getNotifications();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
