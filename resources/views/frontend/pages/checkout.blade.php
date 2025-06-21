@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')

@php
    $currency = session('currency', 'NGN');
@endphp

@section("title", "Checkout")

@section("main-content")
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Checkout</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active text-white">Checkout</li>
    </ol>
</div>

<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Billing details</h1>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <div class="row g-5">
                <div class="col-md-12 col-lg-6 col-xl-7">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">First Name<sup>*</sup></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">Last Name<sup>*</sup></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-item">
                        <label class="form-label my-3">Address <sup>*</sup></label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Town/City<sup>*</sup></label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Country<sup>*</sup></label>
                        <input type="text" name="country" class="form-control">
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Postcode/Zip<sup>*</sup></label>
                        <input type="text" name="post_code" class="form-control">
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Mobile<sup>*</sup></label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Email Address<sup>*</sup></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <hr>
                    <div class="form-item">
                        <textarea name="notes" class="form-control" spellcheck="false" cols="30" rows="6" placeholder="Order Notes (Optional)"></textarea>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 col-xl-5">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @foreach($cart as $item)
                                    @php
                                        $product = auth()->check() ? $item->product : \App\Models\Product::find($item['product_id']);
                                        $qty = auth()->check() ? $item->quantity : $item['quantity'];
                                        $price = $product->price;
                                        $total = $price * $qty;
                                        $grandTotal += $total;
                                    @endphp
                                    <tr>
                                        <td><img src="{{ $product->photo ?? 'img/default.jpg' }}" style="width: 70px; height: 70px;" alt=""></td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $currencyService->convert($price, $currency) }}</td>
                                        <td>{{ $qty }}</td>
                                        <td>{{ $currencyService->convert($total, $currency) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-end">Subtotal</td>
                                    <td><strong>{{ $currencyService->convert($grandTotal, $currency) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-check my-3">
                        <input type="radio" class="form-check-input" name="payment_method" id="cod" value="cash_on_delivery" checked>
                        <label class="form-check-label" for="cod">
                           Cash On Delivery  <i class="fas fa-truck me-1 text-secondary"></i>
                        </label>
                    </div>

                    <div class="form-check my-3">
                        <input type="radio" class="form-check-input" name="payment_method" id="paystack" value="paystack">
                        <label class="form-check-label" for="paystack">
                            Paystack <i class="fas fa-credit-card me-1 text-primary"></i>
                        </label>
                    </div>

                    <div class="form-check my-3">
                        <input type="radio" class="form-check-input" name="payment_method" id="stripe" value="stripe">
                        <label class="form-check-label" for="stripe">
                            Stripe  <i class="fab fa-cc-stripe me-1 text-info"></i>
                        </label>
                    </div>

                    <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                        <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
