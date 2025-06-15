@extends('frontend.layouts.master')

@section('title','')

@section('main-content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active text-white">Shop</li>
    </ol>
</div>
  <!-- Fruits Shop Start-->
  <div class="container-fluid fruite py-5">
    <div class="container py-5">
        <h1 class="mb-4 text-center">{{ $category->title }} shopping</h1>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4 mb-4">
                    <div class="col-xl-3">
                        <div class="input-group w-100 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-6"></div>
                </div>

                <div class="row g-4">

                    <div class="col-lg-3">
                        <div class="row g-4">
                            <div>
                                <form action="{{ route('category.products', $category->slug) }}" method="GET">

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4>Categories</h4>
                                    <hr>
                                    <ul class="list-unstyled fruite-categorie">
                                        @foreach($categories as $cat)
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
                                <h4 class="mb-2">Price</h4>
                                <input type="range" class="form-range w-100" id="rangeInput" name="max_price"
                                       min="0" max="100000" value="{{ request('max_price', 100000) }}"
                                       oninput="amount.value = rangeInput.value">
                                <output id="amount">{{ request('max_price', 100000) }}</output>
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                            </div>
                                </form>
                        </div>
                        <div class="col-lg-12">
                            <h4 class="mb-3">Featured products</h4>
                            @foreach($featuredProducts as $fProduct)
                                <div class="d-flex align-items-center justify-content-start mb-3">
                                    <div class="rounded me-3" style="width: 100px; height: 100px;">
                                        @php
                                        $photo=explode(',',$fProduct->photo);
                                    @endphp
                                        <img src="{{$photo[0]}}" alt="{{$photo[0]}}" class="img-fluid rounded" alt="{{ $fProduct->title }}">
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $fProduct->name }}</h6>
                                        <div class="d-flex">
                                            <h6 class="fw-bold me-2">₦{{ number_format($fProduct->price, 2) }}</h6>
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
                                        @php
                                        $photo=explode(',',$product->photo);
                                    @endphp
                                        <div class="fruite-img">
                                            <img src="{{$photo[0]}}" alt="{{$photo[0]}}" class="img-fluid height:100px rounded-top" alt="{{ $product->name }}">
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                            {{ $category->title }}
                                        </div>
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4>{{ $product->name }}</h4>
                                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($product->description, 80)) }}</p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0"> ₦{{ number_format($product->price, 2) }}</p>
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
                                {{ $products->withQueryString()->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop End-->

@endsection



