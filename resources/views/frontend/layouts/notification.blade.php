@if (session("success"))
    <div class="alert alert-primary text-center flash-alert">
        {{ session("success") }}
    </div>
@endif
