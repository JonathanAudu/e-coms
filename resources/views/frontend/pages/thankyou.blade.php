@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')
@php
    $currency = session('currency', 'NGN');
@endphp

@section("title", "Thank You")

@section("main-content")

<!-- Single Page Header Start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Order Complete</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">Thank You</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Thank You Message Start -->
<div class="container-fluid contact py-5">
    <div class="container">
        <div class="p-5 bg-light rounded text-center">
            <div class="row g-4">
                <div class="col-12">
                    <div class="mx-auto" style="max-width: 700px;">
                        <h1 class="text-success mb-3"><i class="fas fa-check-circle"></i> Thank You for Your Order!</h1>
                        <p class="mb-4">
                            We’ve received your order and will process it shortly. A confirmation email has been sent to your inbox.
                            You can expect delivery within the next few days depending on your location and the delivery method selected.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Thank You Message End -->

@endsection
