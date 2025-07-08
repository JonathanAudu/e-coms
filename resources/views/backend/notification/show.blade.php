@extends('backend.layouts.master')

@section('main-content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            Notification Details
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $notification->data['title'] ?? 'No Title' }}</h5>
            <p class="card-text">
                <strong>Action URL:</strong>
                <a href="{{ $notification->data['actionURL'] ?? '#' }}" target="_blank">
                    {{ $notification->data['actionURL'] ?? 'N/A' }}
                </a>
            </p>
            <p>
                <strong>Icon:</strong>
                <i class="fas {{ $notification->data['fas'] ?? '' }}"></i>
            </p>
            <p>
                <strong>Received:</strong>
                {{ $notification->created_at->format('F d, Y h:i A') }}
            </p>
        </div>
    </div>
</div>
@endsection
