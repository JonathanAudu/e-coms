@extends("frontend.layouts.master")
@inject("currencyService", "App\Services\CurrencyService")
@php
    $currency = session("currency", "NGN");
@endphp

@section("title", $post->title)

@section("main-content")
    <!-- Page Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Blog Details</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route("home") }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("blog") }}">Blog</a></li>
            <li class="breadcrumb-item active text-white">{{ $post->title }}</li>
        </ol>
    </div>

    <!-- Blog Details Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Blog Content -->
            <div class="col-lg-8">
                <div class="mb-4">
                    @if ($post->photo)
                        <img src="{{ $post->photo }}" class="img-fluid rounded mb-4 " alt="{{ $post->title }}">
                    @endif

                    <h2 class="fw-bold">{{ $post->title }}</h2>
                    <p class="text-muted">
                        <i class="fa fa-calendar text-primary"></i> {{ $post->created_at->format("M d, Y") }} | &nbsp;
                        <i class="fa fa-user text-primary"></i> {{ $post->author_info->name ?? "Anonymous" }} | &nbsp;
                        <i class="fa fa-comments text-primary"></i> Comments ({{ $post->allComments->count() }}) &nbsp;

                    </p>

                    <div class="content mb-4">
                        @if ($post->quote)
                            <div class="bg-primary text-white p-3 ps-4 border-start border-4 border-secondary rounded mb-4">
                                <blockquote class="mb-0">
                                    <i class="fa fa-quote-left me-2"></i> {!! $post->quote !!}
                                </blockquote>
                            </div>
                        @endif

                        {!! $post->description !!}
                    </div>

                    @if (!empty($post->tags))
                        @php $tags = explode(',', $post->tags); @endphp
                        <div class="mt-3">
                            <i class="fa fa-tags text-muted"></i>Tags:
                            @foreach ($tags as $tag)
                                <a href="{{ route("blog.tag", trim($tag)) }}" class="badge bg-light text-dark me-1">
                                    {{ ucwords(str_replace("-", " ", trim($tag))) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @auth
                    <div class="col-12 mt-5">
                        <div class="reply">
                            <div class="reply-head comment-form" id="commentFormContainer">
                                <h2 class="reply-title mb-3">Leave a Comment</h2>

                                <!-- Comment Form -->
                                <form class="form comment_form" id="commentForm"
                                    action="{{ route("post-comment.store", $post->slug) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="form-group comment_form_body">
                                                <label for="comment" class="form-label mb-2">Your Message <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="comment" id="comment" rows="5" class="form-control" placeholder="Write your comment here..."
                                                    required></textarea>
                                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                <input type="hidden" name="parent_id" id="parent_id" value="">
                                            </div>
                                        </div>

                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <span class="comment_btn comment">Post Comment</span>
                                                <span class="comment_btn reply" style="display: none;">Reply Comment</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- End Comment Form -->
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12 text-center p-5 bg-light rounded">
                        <p class="mb-0">
                            You need to
                            <a href="{{ route("login.form") }}" class="text-primary fw-semibold">Login</a> or
                            <a href="{{ route("register.form") }}" class="text-primary fw-semibold">Register</a> to leave a
                            comment.
                        </p>
                    </div>
                @endauth

                <!-- Display Comments -->
                @if ($post->allComments->count())
                    <div class="col-12 mt-5">
                        <div class="comments">
                            <h3 class="comment-title mb-4">Comments ({{ $post->allComments->count() }})</h3>

                            @include("frontend.pages.comment", [
                                "comments" => $post->comments,
                                "post_id" => $post->id,
                                "depth" => 3,
                            ])
                        </div>
                    </div>
                @endif


            </div>



            <!-- Sidebar -->
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
                                    <i class="fa fa-calendar  me-2"></i><small
                                        class="text-muted">{{ $recent->created_at->format("M d, Y") }}, </small>
                                    <i class="fa fa-user me-2 ms-2"></i><small class="text-muted">By
                                        {{ $recent->author_info->name }}</small>
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
                                <li class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="{{ route("blog.category", $cat->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $cat->title }}
                                    </a>
                                    <span class="badge bg-primary">{{ $cat->post->count() }}</span>
                                </li>
                            @endforeach
                        </form>
                    </ul>
                </div>


                <!-- Tags -->
                <div class="mb-5">
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
@push("styles")
    <script type='text/javascript'
        src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons'
        async='async'></script>
@endpush
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const replyButtons = document.querySelectorAll(".reply");
        const cancelButtons = document.querySelectorAll(".cancel");
        const parentIdInput = document.getElementById("parent_id");
        const commentForm = document.getElementById("commentForm");
        const commentTextarea = document.getElementById("comment");
        const replyText = document.querySelector(".comment_btn.reply");
        const commentText = document.querySelector(".comment_btn.comment");

        replyButtons.forEach(button => {
            button.addEventListener("click", function() {
                const commentId = this.dataset.id;

                if (commentId) {
                    parentIdInput.value = commentId;
                }

                commentForm.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                replyText.style.display = "inline";
                commentText.style.display = "none";

                // Hide all other cancel buttons
                document.querySelectorAll(".cancel").forEach(c => c.style.display = "none");

                // Show cancel button next to this reply button
                const cancelBtn = this.nextElementSibling;
                if (cancelBtn && cancelBtn.classList.contains("cancel")) {
                    cancelBtn.style.display = "inline";
                }

                commentTextarea.focus();
            });
        });

        cancelButtons.forEach(button => {
            button.addEventListener("click", function() {
                parentIdInput.value = "";
                this.style.display = "none";
                replyText.style.display = "none";
                commentText.style.display = "inline";
            });
        });
    });
</script>
