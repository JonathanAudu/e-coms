@foreach($comments as $comment)
    @php $dep = $depth - 1; @endphp

    <div class="display-comment mb-4" @if($comment->parent_id != null) style="margin-left: 40px;" @endif>
        <div class="comment-list">
            <div class="single-comment d-flex align-items-start">
                {{-- Avatar --}}
                <div class="me-3">
                    <img src="{{ $comment->user_info['photo'] ?? asset('backend/img/avatar.png') }}" alt="avatar"
                         class="rounded-circle" style="width: 40px; height: 40px;">
                </div>

                {{-- Content --}}
                <div class="content w-100">
                    <h6 class="mb-1">
                        {{ $comment->user_info['name'] }}
                        <small class="text-muted ms-2">at {{ $comment->created_at->format('g:i a') }} on {{ $comment->created_at->format('M d Y') }}</small>
                    </h6>
                    <p class="mb-2">{{ $comment->comment }}</p>

                    @if($dep)
                        <div class="button mb-2">
                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary reply" data-id="{{ $comment->id }}">
                                <i class="fa fa-reply"></i> Reply
                            </a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger cancel" style="display: none;">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    @endif

                    {{-- Replies toggle --}}
                    @if ($comment->replies->count() === 1)
                    {{-- Show the single reply immediately --}}
                    <div class="child-replies mt-3">
                        @include('frontend.pages.comment', [
                            'comments' => $comment->replies,
                            'depth' => $dep
                        ])
                    </div>
                @elseif ($comment->replies->count() > 1)
                    {{-- Show toggle only for multiple replies --}}
                    <a href="javascript:void(0);"
                       class="show-replies-toggle text-decoration-underline small text-primary"
                       data-target="replies-{{ $comment->id }}">
                        View {{ $comment->replies->count() }} {{ Str::plural('Reply', $comment->replies->count()) }}
                    </a>

                    <div id="replies-{{ $comment->id }}" class="child-replies d-none mt-3">
                        @include('frontend.pages.comment', [
                            'comments' => $comment->replies,
                            'depth' => $dep
                        ])
                    </div>
                @endif

                </div>
            </div>
        </div>
    </div>
@endforeach
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".show-replies-toggle").forEach(toggle => {
            toggle.addEventListener("click", function () {
                const targetId = this.dataset.target;
                const repliesDiv = document.getElementById(targetId);

                if (!repliesDiv) return;

                if (repliesDiv.classList.contains("d-none")) {
                    repliesDiv.classList.remove("d-none");
                    this.textContent = "Hide Replies";
                } else {
                    const count = repliesDiv.querySelectorAll(".display-comment").length;
                    repliesDiv.classList.add("d-none");
                    this.textContent = `View ${count} ${count > 1 ? "Replies" : "Reply"}`;
                }
            });
        });
    });
</script>

