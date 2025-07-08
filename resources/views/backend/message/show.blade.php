@extends('backend.layouts.master')
@section('main-content')
<div class="card">
  <h5 class="card-header">Message</h5>
  <div class="card-body">
    @if($message)
        @if($message->photo)
        <img src="{{$message->photo}}" class="rounded-circle " style="margin-left:44%;">
        @else
        <img src="{{asset('backend/img/avatar.png')}}" class="rounded-circle " style="margin-left:44%;">
        @endif
        <div class="py-4">From: <br>
           Name :{{$message->name}}<br>
           Email :{{$message->email}}<br>
           Phone :{{$message->phone}}
        </div>
        <hr/>
  <h5 class="text-center" style="text-decoration:underline"><strong>Subject :</strong> {{$message->subject}}</h5>
        <p class="py-5">{{$message->message}}</p>

        @if($message->reply)
            <hr/>
            <h5 class="text-center" style="text-decoration:underline"><strong>Admin Reply:</strong></h5>
            <p class="py-3">{{$message->reply}}</p>
            <div class="text-right text-muted small">Replied at: {{ $message->replied_at ? \Carbon\Carbon::parse($message->replied_at)->format('F d, Y h:i A') : '' }}</div>
        @else
            <hr/>
            <form method="POST" action="{{ route('message.reply', $message->id) }}">
                @csrf
                <div class="form-group">
                    <label for="reply">Reply to User</label>
                    <textarea class="form-control" id="reply" name="reply" rows="4" required>{{ old('reply') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Reply</button>
            </form>
        @endif

    @endif

  </div>
</div>
@endsection
