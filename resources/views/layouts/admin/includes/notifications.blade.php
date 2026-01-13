<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
    <i class="far fa-bell" style="font-size: 22px"></i>
    <span class="badge bg-danger navbar-badge" id="notiicationCount">{{ count(Auth::user()->unreadNotifications) }}</span>
</a>
<div class="dropdown-menu dropdown-menu-xl dropdown-menu-right notification-dd" style="left: inherit; right: 0px;">
    <span class="dropdown-item dropdown-header">
        Notifications
        @if (count(Auth::user()->unreadNotifications) > 0)
        <a href="javascript:;" class="mark-all-as-read"><i class="fa fa-circle mr-1"></i> Mark all
            as read</a>
        @endif
    </span>
    <div class="dropdown-divider"></div>

    @forelse(Auth::user()->notifications as $notification)
        <div class="media notifications-box">
            <button type="button" data-notification-id="{{ $notification->id }}"
                class="notification-close remove-notification"><span aria-hidden="true">&times;</span></button>
            <img src="{{ asset('frontend/images/notifications.svg') }}" class="mr-3" alt="">
            <div class="media-body">
                <a href="javascript:;" data-notification-id="{{ $notification->id }}" class="mark-and-view">
                    <p>{{ $notification->data['heading'] }} @if (!$notification->read_at)
                            <i class="fa fa-circle custom-color-danger ml-1 fs-10"></i>
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
