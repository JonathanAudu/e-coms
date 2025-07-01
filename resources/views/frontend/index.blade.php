@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')
@php
    $currency = session('currency', 'NGN');
@endphp

@section("title", "HOME PAGE")
@section("main-content")
    <!-- Hero Start -->
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h3 class="mb-3 text-secondary">100% Foods & Vegetable</h3>
                    <h1 class="mb-5 display-2 text-primary">Organic Seafoods and Fresh Palm Oil</h1>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active rounded">
                                <img src={{ asset("asset/img/hero-img-1.png") }}
                                    class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                                <a href="#" class="btn px-4 py-2 text-white rounded">Fruites</a>
                            </div>
                            <div class="carousel-item rounded">
                                <img src={{ asset("asset/img/hero-img-2.jpg") }} class="img-fluid w-100 h-100 rounded"
                                    alt="Second slide">
                                <a href="#" class="btn px-4 py-2 text-white rounded">Vegitables</a>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>Our Produce</h1>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                                    <span class="text-dark" style="width: 130px;">All Produce</span>
                                </a>
                            </li>
                            @foreach($catgroup as $category)
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-{{ $category->id }}">
                                        <span class="text-dark" style="width: 130px;">{{ $category->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    {{-- All Products Tab --}}
                    <div id="tab-all" class="tab-pane fade show active p-0">
                        <div class="row g-4">
                            @foreach($catProducts as $product)
                                @include('frontend.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>

                    {{-- Category Tabs --}}
                    @foreach($catgroup as $category)
                        <div id="tab-{{ $category->id }}" class="tab-pane fade p-0">
                            <div class="row g-4">
                                @forelse($category->products->take(12) as $product)
                                    @include('frontend.partials.product-card', ['product' => $product])
                                @empty
                                    <p class="text-center">No products in this category yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    <!-- Fruits Shop End-->


    <!-- Featurs Section Start -->
    <div class="container-fluid featurs py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-car-side fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Free Shipping</h5>
                            <p class="mb-0">Free on order over $300</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Security Payment</h5>
                            <p class="mb-0">100% security payment</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-money-bill fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Best Prices</h5>
                            <p class="mb-0">Guaranteed price</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fa fa-phone-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>24/7 Support</h5>
                            <p class="mb-0">Support every time fast</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Featurs Section End -->



    <!-- Vesitable Shop Start-->
    <div class="container-fluid vesitable py-5">
        <div class="container py-5">
            <h1 class="mb-2">Fresh Vegetables</h1>
            <div class="owl-carousel vegetable-carousel justify-content-center">
                @foreach ($relatedProducts as $product)
                    @php $photo = explode(',', $product->photo); @endphp
                    <div class="border border-primary rounded position-relative vesitable-item">
                        <a href="{{ route("product-detail", $product->slug) }}">
                        <div class="vesitable-img" style="height: 200px; overflow: hidden;">
                            <img src="{{ asset($photo[0]) }}" class="img-fluid w-100 h-100 object-fit-cover rounded-top" alt="{{ $product->name }}">
                        </div>
                        <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">
                            {{ $product->category->title ?? 'Category' }}
                        </div>
                        </a>
                        <div class="p-4 pb-0 rounded-bottom">
                            <a href="{{ route("product-detail", $product->slug) }}">
                            <h4>{{ $product->name }}</h4>
                            </a>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 20) }}</p>
                            <div class="d-flex justify-content-between flex-lg-wrap">
                                <p class="text-dark fs-5 fw-bold">{{ $currencyService->convert($product->price, $currency) }}</p>
                                <div class="d-flex gap-2">
                                @auth
                                <form action="{{ route("wishlist.store", $product->id) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id"
                                        value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-outline-danger rounded-circle"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Add to Wishlist">
                                        <i class="fa fa-heart"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route("login.form") }}"
                                    class="btn btn-outline-danger rounded-circle"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Login to Wishlist">
                                    <i class="fa fa-heart"></i>
                                </a>
                            @endauth
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit"
                                            class="btn border border-secondary rounded-circle text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart">
                                        <i class="fa fa-shopping-bag"></i>
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Vesitable Shop End -->


    <!-- Banner Section Start-->
    <div class="container-fluid banner bg-secondary my-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="py-4">
                        <h1 class="display-3 text-white">Fresh Exotic Fruits</h1>
                        <p class="fw-normal display-3 text-dark mb-4">in Our Store</p>
                        <p class="mb-4 text-dark">The generated Lorem Ipsum is therefore always free from repetition
                            injected humour, or non-characteristic words etc.</p>
                        <a href="#"
                            class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">BUY</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src={{ asset("asset/img/baner-1.png") }} class="img-fluid w-100 rounded" alt="">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute"
                            style="width: 140px; height: 140px; top: 0; left: 0;">
                            <h1 style="font-size: 100px;">1</h1>
                            <div class="d-flex flex-column">
                                <span class="h2 mb-0">50$</span>
                                {{-- <span class="h4 text-muted mb-0">kg</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Section End -->


    <!-- Bestsaler Product Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="display-4">Bestselling Fresh Picks</h1>
                <p>From farm to table, explore our most loved fruits, vegetables, and food essentials. Handpicked for freshness and quality, these top choices are customer favorites for a reason.</p>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light h-100">
                            <div class="row align-items-center g-3">
                                <div class="col-6">
                                    @php $photo = explode(",", $product->photo); @endphp
                                    <a href="{{ route('product-detail', $product->slug) }}">
                                        <img src="{{ $photo[0] }}" class="img-fluid rounded-circle w-100" alt="{{ $product->name }}">
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{ route('product-detail', $product->slug) }}" class="h5 d-block mb-2">
                                        {{ $product->name }}
                                    </a>
                                    <h4 class="mb-3">{{ $currencyService->convert($product->price, $currency) }}</h4>

                                    <div class="d-flex gap-2">
                                        @auth
                                            <form action="{{ route('wishlist.store', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn btn-outline-danger rounded-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wishlist">
                                                    <i class="fa fa-heart"></i>
                                                </button>
                                            </form>
                                        @else
                                                <a href="{{ route('login.form') }}" class="btn btn-outline-danger rounded-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Login to Wishlist">
                                                    <i class="fa fa-heart"></i>
                                                </a>
                                        @endauth

                                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn border border-secondary rounded-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart">
                                                <i class="fa fa-shopping-bag"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No products available at the moment.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bestsaler Product End -->


    <!-- Tastimonial Start -->
    {{-- <div class="container-fluid testimonial py-5">
        <div class="container py-5">
            <div class="testimonial-header text-center">
                <h4 class="text-primary">Our Testimonial</h4>
                <h1 class="display-5 mb-5 text-dark">Our Client Saying!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel">
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">Lorem Ipsum is simply dummy text of the printing Ipsum has been the
                                industry's standard dummy text ever since the 1500s,
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Client Name</h4>
                                <p class="m-0 pb-3">Profession</p>
                                <div class="d-flex pe-5">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">Lorem Ipsum is simply dummy text of the printing Ipsum has been the
                                industry's standard dummy text ever since the 1500s,
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Client Name</h4>
                                <p class="m-0 pb-3">Profession</p>
                                <div class="d-flex pe-5">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item img-border-radius bg-light rounded p-4">
                    <div class="position-relative">
                        <i class="fa fa-quote-right fa-2x text-secondary position-absolute"
                            style="bottom: 30px; right: 0;"></i>
                        <div class="mb-4 pb-4 border-bottom border-secondary">
                            <p class="mb-0">Lorem Ipsum is simply dummy text of the printing Ipsum has been the
                                industry's standard dummy text ever since the 1500s,
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap">
                            <div class="bg-secondary rounded">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded"
                                    style="width: 100px; height: 100px;" alt="">
                            </div>
                            <div class="ms-4 d-block">
                                <h4 class="text-dark">Client Name</h4>
                                <p class="m-0 pb-3">Profession</p>
                                <div class="d-flex pe-5">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Tastimonial End -->

@endsection
