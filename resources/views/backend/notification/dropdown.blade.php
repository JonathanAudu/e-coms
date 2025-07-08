@php
    $notifications = Auth::user()->unreadNotifications->take(5);
@endphp
<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-bell fa-fw"></i>
    <span class="badge badge-danger badge-counter">{{ $notifications->count() }}</span>
</a>
<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
    <h6 class="dropdown-header">Notifications Center</h6>
    @forelse($notifications as $notification)
        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.notification', $notification->id) }}">
            <div class="mr-3">
                <div class="icon-circle bg-primary">
                    <i class="fas {{ $notification->data['fas'] ?? '' }} text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">{{ $notification->created_at->format('F d, Y h:i A') }}</div>
                <span class="font-weight-bold">{{ $notification->data['title'] ?? 'No Title' }}</span>
            </div>
        </a>
    @empty
        <span class="dropdown-item text-center small text-gray-500">No new notifications</span>
    @endforelse
    <a class="dropdown-item text-center small text-gray-500" href="{{ route('all.notification') }}">Show All Notifications</a>
</div>
