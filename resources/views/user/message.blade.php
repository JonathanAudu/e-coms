@extends('user.layouts.master')

@section('main-content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Your Messages</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($messages->isEmpty())
                        <div class="alert alert-info">You have not sent any messages yet.</div>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Sent At</th>
                                    <th>Admin Reply</th>
                                    <th>Replied At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                    <tr>
                                        <td>{{ $message->subject }}</td>
                                        <td>{{ $message->message }}</td>
                                        <td>{{ $message->created_at->format('F d, Y h:i A') }}</td>
                                        <td>
                                            @if($message->reply)
                                                <span class="text-success">{{ $message->reply }}</span>
                                            @else
                                                <span class="text-muted">No reply yet</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($message->replied_at)
                                                {{ \Carbon\Carbon::parse($message->replied_at)->format('F d, Y h:i A') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $messages->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
