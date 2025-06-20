@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')
@php
    $currency = session('currency', 'NGN');
@endphp


@section("title", "Cart")

@section("main-content")

 <!-- Single Page Header start -->
 <div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Cart</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active text-white">Cart</li>
    </ol>
</div>
<!-- Single Page Header End -->


<!-- Cart Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Products</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                    <th scope="col">Handle</th>
                  </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp

                    @forelse($cartItems as $key => $item)
                        @php
                            $product = auth()->check() ? $item->product : null;
                            $name = auth()->check() ? $product->name : $item['name'];
                            $photo = auth()->check() ? explode(',', $product->photo)[0] : $item['photo'];
                            $price = auth()->check() ? $product->price : $item['price'];
                            $quantity = auth()->check() ? $item->quantity : $item['quantity'];
                            $productId = auth()->check() ? $product->id : $key;
                            $itemTotal = $price * $quantity;
                            $subtotal += $itemTotal;
                        @endphp

                        <tr>
                            <td><img src="{{ asset($photo) }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px;"></td>
                            <td><p class="mb-0 mt-4">{{ $name }}</p></td>
                            <td><p class="mb-0 mt-4">{{ $currencyService->convert($price, $currency) }} </p></td>
                            <td>
                                <form action="{{ route('cart.update') }}" method="POST" class="update-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <div class="input-group quantity mt-4" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="quantity" class="form-control form-control-sm text-center border-0 quantity-input" value="{{ $quantity }}">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </td>
                            <td><p class="mb-0 mt-4">{{ $currencyService->convert($itemTotal, $currency) }}</p></td>
                            <td>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <button type="submit" class="btn btn-md rounded-circle bg-light border mt-4">
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Your cart is empty.</td>
                        </tr>
                    @endforelse
                    </tbody>

            </table>
        </div>
        <div class="mt-5">
            <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code">
            <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="button">Apply Coupon</button>
        </div>
        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal:</h5>
                            <p class="mb-0">{{ $currencyService->convert($subtotal, $currency) }}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 me-4">Shipping Fee</h5>
                            <div class="">

                                @php $shipping = 2000; @endphp

                                <p class="mb-0">Flat rate: {{ $currencyService->convert($shipping, $currency) }} </p>

                            </div>
                        </div>
                        <p class="mb-0 text-end">Shipping</p>
                    </div>
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                        <p class="mb-0 pe-4">{{ $currencyService->convert($subtotal + $shipping, $currency) }}</p>
                    </div>
                    <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Page End -->
@endsection
@push('scripts')
<script>
    document.querySelectorAll('.update-cart-form').forEach(form => {
        const minusBtn = form.querySelector('.btn-minus');
        const plusBtn = form.querySelector('.btn-plus');
        const input = form.querySelector('.quantity-input');

        // Remove previous event listeners if script re-initializes
        minusBtn.onclick = null;
        plusBtn.onclick = null;

        // MINUS
        minusBtn.addEventListener('click', function (e) {
            e.preventDefault();
            let value = parseInt(input.value) || 1;
            if (value > 1) {
                input.value = value;
                setTimeout(() => form.submit(), 150);
            }
        });

        // PLUS
        plusBtn.addEventListener('click', function (e) {
            e.preventDefault();
            let value = parseInt(input.value) || 1;
            input.value = value ;
            setTimeout(() => form.submit(), 150);
        });

        // INPUT MANUAL CHANGE
        input.addEventListener('change', function () {
            let value = parseInt(input.value);
            if (value >= 1) {
                form.submit();
            }
        });
    });
</script>
@endpush


