@extends("frontend.layouts.master")
@inject("currencyService", "App\Services\CurrencyService")
@php
    $currency = session("currency", "NGN");
@endphp

@section("title", "Blog")

@section("main-content")
    <!-- Page Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Blog</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route("home") }}">Home</a></li>
            <li class="breadcrumb-item active text-white">Blog</li>
        </ol>
    </div>

    <!-- Blog Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Blog Posts Section -->
            <div class="col-lg-8">
                <div class="row">
                    @forelse($posts as $post)
                        <div class="col-md-6 mb-4">
                            <div class="rounded position-relative fruite-item shadow-sm border h-100">
                                <a href="{{ route("blog.detail", $post->slug) }}">
                                    <div class="fruite-img" style="width: 80%; height: 130px; overflow: hidden;">
                                        <img src="{{ $post->photo }}" alt="{{ $post->title }}" style="height: 100%;"
                                            class="rounded-top">
                                    </div>
                                </a>
                                <div class="p-3 border border-secondary border-top-0 rounded-bottom">
                                    <a href="{{ route("blog.detail", $post->slug) }}">
                                        <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                                    </a>
                                    <div class="small text-muted mb-2">
                                        <i class="fa fa-calendar"></i> {{ $post->created_at->format("M d, Y") }} &nbsp;
                                        <i class="fa fa-user"></i> {{ $post->author_info->name ?? "Anonymous" }}
                                    </div>
                                    <p class="mb-2">{{ Str::limit(strip_tags($post->summary), 70) }}</p>
                                    <a href="{{ route("blog.detail", $post->slug) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Read More
                                    </a>

                                    @if (!empty($post->tags))
                                        @php
                                            $tags = explode(",", $post->tags);
                                        @endphp
                                        <div class="mt-2">
                                            <i class="fa fa-tags text-muted"></i>
                                            @foreach ($tags as $tag)
                                                <a href="{{ route("blog.tag", trim($tag)) }}"
                                                    class="badge bg-light text-dark me-1">
                                                    {{ ucwords(str_replace("-", " ", trim($tag))) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No blog posts found.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mb-4">
                    {{ $posts->appends(request()->query())->links("vendor.pagination.custom") }}
                </div>
            </div>


            <!-- Sidebar Section -->
            <div class="col-lg-4">
                <!-- Search -->
                <div class="mb-4">
                    <form action="{{ route("blog.search") }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Search blog...">
                        <button class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <!-- Recent Posts -->
                <div class="mb-5">
                    <h4 class="mb-3">Recent Posts</h4>
                    <ul class="list-group list-group-flush">
                        @foreach ($recent_posts as $recent)
                            <li class="list-group-item">
                                <a href="{{ route("blog.detail", $recent->slug) }}">
                                    <strong>{{ $recent->title }}</strong><br>
                                    <small class="text-muted">{{ $recent->created_at->format("M d, Y") }}</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Post Categories -->
                <div class="mb-5 ms-4">
                    <h4 class="mb-3">Blog Categories</h4>
                    <ul class="list-group list-group-flush">
                        @if (!empty($_GET["category"]))
                            @php
                                $filter_cats = explode(",", $_GET["category"]);
                            @endphp
                        @endif
                        <form action="{{ route("blog.filter") }}" method="POST">
                            @csrf
                            @foreach (App\Helpers\Helpers::postCategoryList("posts") as $cat)
                                <li>
                                    <div class="d-flex justify-content-between fruite-name ">
                                        <a href="{{ route("blog.category", $cat->slug) }}">
                                            {{ $cat->title }}
                                        </a>
                                    </div>
                                    <span class="badge bg-primary">{{ $cat->posts_count }}</span>
                                </li>
                            @endforeach
                        </form>
                    </ul>
                </div>

                <!-- Tags -->
                <div class="mb-5 ms-2">
                    <h4 class="mb-3">Tags</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (App\Helpers\Helpers::postTagList("posts") as $tag)
                            <a href="{{ route("blog.tag", $tag->title) }}"
                                class="badge bg-secondary text-white">{{ $tag->title }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
