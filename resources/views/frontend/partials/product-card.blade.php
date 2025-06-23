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
                <div class="d-flex gap-2">
                @auth
                    <form action="{{ route('wishlist.store', $product->id) }}" method="POST" >
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                                class="btn btn-outline-danger rounded-circle"
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
</div>
