@extends("frontend.layouts.master")
@inject('currencyService', 'App\Services\CurrencyService')
@php
    $currency = session('currency', 'NGN');
@endphp
@section("title", "")

@section("main-content")

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">{{ ucfirst($product_detail->slug) }} Details</h1>
    </div>
    <!-- Single Page Header End -->


    <!-- Single Product Start -->
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                @php
                                    $photo = explode(",", $product_detail->photo);
                                @endphp
                                <img src="{{ $photo[0] }}" alt="{{ $photo[0] }}" class="img-fluid rounded"
                                    alt="{{ $product_detail->name }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3">{{ ucfirst($product_detail->slug) }}</h4>
                            <p class="fw-bold mb-3">Category: {{ ucfirst($product_detail->category->slug) }}</p>
                            <h5 class="fw-bold mb-3">{{ $currencyService->convert($product_detail->price, $currency) }}</h5>

                            <p class="mb-4">
                                {{ \Illuminate\Support\Str::limit(strip_tags($product_detail->description), 150) }}
                            <div class="input-group quantity mb-5" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center border-0"
                                    value="1">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                </button>
                            </form>

                        </div>
                        <div class="col-lg-12">
                            <nav>
                                <div class="nav nav-tabs mb-3">
                                    <button class="nav-link active border-white border-bottom-0" type="button"
                                        role="tab" id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                        aria-controls="nav-about" aria-selected="true">Description</button>
                                    <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                        id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                        aria-controls="nav-mission" aria-selected="false">Reviews</button>
                                </div>
                            </nav>
                            <div class="tab-content mb-5">
                                <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                    <p>{{ strip_tags($product_detail->description) }}</p>
                                </div>
                                <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                    <div class="d-flex">
                                        <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3"
                                            style="width: 100px; height: 100px;" alt="">
                                        <div class="">
                                            <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                            <div class="d-flex justify-content-between">
                                                <h5>Jason Smith</h5>

                                            </div>
                                            <p>The generated Lorem Ipsum is therefore always free from repetition injected
                                                humour, or non-characteristic
                                                words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="nav-vision" role="tabpanel">
                                    <p class="text-dark">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor
                                        sit. Aliqu diam
                                        amet diam et eos labore. 3</p>
                                    <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos
                                        labore.
                                        Clita erat ipsum et lorem et sit</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="row g-4 fruite">
                        <div class="col-lg-12">

                            <div class="mb-4">
                                <h4 class="fw-bold">Categories</h4>
                                <hr>
                                <ul class="list-unstyled fruite-categorie">
                                    @foreach ($catwithCount as $cat)
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="{{ route("category.products", $cat->slug) }}">
                                                    {{ $cat->title }}
                                                </a>
                                                <span>({{ $cat->products_count }})</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h4 class="mb-4">Featured products</h4>
                            @foreach ($featuredProducts as $fProduct)
                                <div class="d-flex align-items-center justify-content-start">


                                    <div class="rounded me-3" style="width: 100px; height: 100px;">
                                        @php
                                            $photo = explode(",", $fProduct->photo);
                                        @endphp
                                        <a href="{{ route("product-detail", $fProduct->slug) }}">
                                            <img src="{{ $photo[0] }}" alt="{{ $photo[0] }}"
                                                class="img-fluid rounded" alt="{{ $fProduct->title }}">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route("product-detail", $fProduct->slug) }}">
                                            <h6 class="mb-2">{{ $fProduct->name }}</h6>
                                        </a>
                                        <div class="d-flex mb-2">
                                            <h6 class="fw-bold me-2">{{ $currencyService->convert($fProduct->price, $currency) }}</h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="fw-bold mb-2">Related Products</h1>
            <div class="vesitable mt-2">
                <div class="owl-carousel vegetable-carousel justify-content-center">
                    @foreach ($relatedProducts as $product)
                        @php $photo = explode(',', $product->photo); @endphp
                        <div class="border border-primary rounded position-relative vesitable-item">
                            <a href="{{ route("product-detail", $fProduct->slug) }}">
                            <div class="vesitable-img" style="height: 200px; overflow: hidden;">
                                <img src="{{ asset($photo[0]) }}" class="img-fluid w-100 h-100 object-fit-cover rounded-top" alt="{{ $product->name }}">
                            </div>
                            <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">
                                {{ $product->category->title ?? 'Category' }}
                            </div>
                            </a>
                            <div class="p-4 pb-0 rounded-bottom">
                                <a href="{{ route("product-detail", $fProduct->slug) }}">
                                <h4>{{ $product->name }}</h4>
                                </a>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 20) }}</p>
                                <div class="d-flex justify-content-between flex-lg-wrap">
                                    <p class="text-dark fs-5 fw-bold">{{ $currencyService->convert($fProduct->price, $currency) }}</p>
                                    <a href="{{ route('product-detail', $product->slug) }}"
                                       class="btn border border-secondary rounded-pill px-3 py-1 mb-4 text-primary">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Single Product End -->

@endsection
