@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')
@php
    $currency = session('currency', 'NGN');
    $rates = $currencyService->getRates();
    $rate = isset($rates[$currency]) && is_numeric($rates[$currency]) && $rates[$currency] > 0
        ? floatval($rates[$currency])
        : 1;

    $minConverted = request('min_price') ?? 0;
    $maxConverted = request('max_price') ?? intval(100000 * $rate);
@endphp


@section("title", "All Products")

@section("main-content")
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop</h1>
    </div>

    <!-- Products Shop Start -->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4 text-center">All Products</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4 mb-4">
                        <div class="col-xl-3">
                            <form action="{{ route('product-lists') }}" method="GET" class="input-group w-100 mx-auto d-flex mb-4">
                                <input type="search" name="keyword" value="{{ request('keyword') }}" class="form-control p-3" placeholder="Search products...">
                                <button class="input-group-text p-3 bg-primary text-white border-0" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-6"></div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-3">
                            <div class="row g-4">
                                <div>
                                    <form action="{{ route('product-lists') }}" method="GET">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <h4>Categories</h4>
                                                <hr>
                                                <ul class="list-unstyled fruite-categorie">
                                                    @foreach ($categories as $cat)
                                                        <li>
                                                            <div class="d-flex justify-content-between fruite-name">
                                                                <a href="{{ route('category.products', $cat->slug) }}">
                                                                    {{ $cat->title }}
                                                                </a>
                                                                <span>({{ $cat->products_count }})</span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                            <h4 class="mb-2">Price ({{ $currency }})</h4>

                                            <label for="min_price">Min {{ ($currency) }}</label>
                                            <input type="number" name="min_price" class="form-control mb-2"
                                                   value="{{ $minConverted }}" step="0.01" min="0">

                                            <label for="max_price">Max {{ ($currency) }}</label>
                                            <input type="number" name="max_price" class="form-control"
                                                   value="{{ $maxConverted }}" step="0.01" min="0">
                                        </div>

                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-12">
                                    <h4 class="mb-3">Featured products</h4>
                                    @foreach ($featuredProducts as $fProduct)
                                        <div class="d-flex align-items-center justify-content-start mb-3">
                                            <div class="rounded me-3" style="width: 100px; height: 100px;">
                                                @php $photo = explode(",", $fProduct->photo); @endphp
                                                <a href="{{ route('product-detail', $fProduct->slug) }}">
                                                    <img src="{{ $photo[0] }}" class="img-fluid rounded" alt="{{ $fProduct->title }}">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{ route('product-detail', $fProduct->slug) }}">
                                                    <h6 class="mb-1">{{ $fProduct->name }}</h6>
                                                </a>
                                                <div class="d-flex">
                                                    <h6 class="fw-bold me-2">{{ $currencyService->convert($fProduct->price, $currency) }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="row g-4 justify-content-center">
                                @forelse($products as $product)
                                    <div class="col-md-6 col-lg-6 col-xl-4">
                                        <div class="rounded position-relative fruite-item">
                                            @php $photo = explode(",", $product->photo); @endphp
                                            <a href="{{ route('product-detail', $product->slug) }}">
                                                <div class="fruite-img" style="width: 100%; height: 250px; overflow: hidden;">
                                                    <img src="{{ $photo[0] }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit:cover;" class="rounded-top">
                                                </div>
                                            </a>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <a href="{{ route('product-detail', $product->slug) }}">
                                                    <h4>{{ $product->name }}</h4>
                                                </a>
                                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 20) }}</p>
                                                <div class="d-flex justify-content-between flex-lg-wrap">
                                                    <p class="text-dark fs-5 fw-bold mb-0">
                                                        {{ $currencyService->convert($product->price, $currency) }}
                                                    </p>
                                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center">
                                        <p>No products found.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="col-12">
                                <div class="pagination d-flex justify-content-start mt-5">
                                    {{ $products->withQueryString()->links("vendor.pagination.custom") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
