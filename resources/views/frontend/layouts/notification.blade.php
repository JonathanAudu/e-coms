@if (session("success"))
    <div class="alert alert-primary text-center flash-alert">
        {{ session("success") }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger text-center flash-alert">
        {{ session("error") }}
    </div>
@endif
