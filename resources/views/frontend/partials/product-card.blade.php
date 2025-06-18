<div class="col-md-6 col-lg-4 col-xl-3">
    <div class="rounded position-relative fruite-item">

        <div class="fruite-img " style="width: 100%; height: 250px; overflow: hidden;">
            <a href="{{ route("product-detail", $product->slug) }}">
                <img src="{{ asset($product->photo ?? "asset/img/default.jpg") }}" alt="{{ $product->name }}"
                    style="width: 100%; height: 100%; object-fit:cover; object-position: center;" class="rounded-top">
            </a>
        </div>
        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
            {{ $product->category->title ?? "Uncategorized" }}
        </div>
        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
            <a href="{{ route("product-detail", $product->slug) }}">
                <h4>{{ $product->name }}</h4>
            </a>
            <p>{{ \Illuminate\Support\Str::words(strip_tags($product->description), 4, "...") }}</p>
            <div class="d-flex justify-content-between flex-lg-wrap">
                <p class="text-dark fs-5 fw-bold mb-0">{{ $currencyService->convert($product->price, $currency) }}</p>
                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary">
                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                </a>
            </div>
        </div>
    </div>
</div>
