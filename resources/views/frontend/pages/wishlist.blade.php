@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')
@php
    $currency = session('currency', 'NGN');
@endphp

@section("title", "Wishlist")

@section("main-content")

<!-- Single Page Header Start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Wishlist</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active text-white">Wishlist</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Wishlist Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wishlists as $item)
                        @php
                            $product = $item->product;
                            $photo = $product ? explode(',', $product->photo)[0] : 'backend/img/thumbnail-default.jpg';
                        @endphp
                        <tr>
                            <td><img src="{{ asset($photo) }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px;"></td>
                            <td><p class="mb-0 mt-4">{{ $product->name ?? 'N/A' }}</p></td>
                            <td><p class="mb-0 mt-4">{{ $currencyService->convert($product->price ?? 0, $currency) }}</p></td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-0 mt-4">
                                    {{-- Add to Cart --}}
                                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn border border-secondary rounded-circle text-primary"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart">
                                            <i class="fa fa-shopping-bag"></i>
                                        </button>
                                    </form>

                                    {{-- Remove from Wishlist --}}
                                    <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn border border-danger rounded-circle text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Remove from Wishlist">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Your wishlist is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Wishlist Page End -->
@endsection
