<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Category;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController extends Controller
{
    public function blog()
    {
        $post = Post::query();

        // Filter by category slugs
        if (!empty($_GET['category'])) {
            $slugs = explode(',', $_GET['category']);
            $cat_ids = PostCategory::whereIn('slug', $slugs)->pluck('id')->toArray();
            $post->whereIn('post_cat_id', $cat_ids);
        }

        // Filter by tag slugs
        if (!empty($_GET['tag'])) {
            $slugs = explode(',', $_GET['tag']);
            $tag_ids = PostTag::whereIn('slug', $slugs)->pluck('id')->toArray();
            $post->whereIn('post_tag_id', $tag_ids);
        }

        // Handle pagination with optional 'show' parameter
        $perPage = !empty($_GET['show']) ? (int) $_GET['show'] : 4;
        $posts = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate($perPage);

        // Get recent posts
        $recent_posts = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        // Get categories that have products
        $categories = Category::withCount('products')
            ->has('products')
            ->orderBy('title', 'ASC')
            ->limit(8)
            ->get();

        return view('frontend.pages.blog', [
            'posts' => $posts,
            'recent_posts' => $recent_posts,
            'categories' => $categories,
        ]);
    }


    public function blogDetail($slug)
{
    $post = Post::where('slug', $slug)
        ->with([
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->where('status', 'active')
                    ->with([
                        'replies' => function ($q) {
                            $q->where('status', 'active')->with('user_info');
                        },
                        'user_info'
                    ]);
            },
            'allComments',
        ])
        ->firstOrFail();

    $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

    $categories = Category::withCount('products')
        ->has('products')
        ->orderBy('title', 'ASC')
        ->limit(8)
        ->get();

    return view('frontend.pages.blog-detail', [
        'post' => $post,
        'recent_posts' => $rcnt_post,
        'categories' => $categories,
    ]);
}




    public function blogSearch(Request $request){
        // return $request->all();
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $posts=Post::orwhere('title','like','%'.$request->search.'%')
            ->orwhere('quote','like','%'.$request->search.'%')
            ->orwhere('summary','like','%'.$request->search.'%')
            ->orwhere('description','like','%'.$request->search.'%')
            ->orwhere('slug','like','%'.$request->search.'%')
            ->orderBy('id','DESC')
            ->paginate(8);

            $categories = Category::withCount('products')
        ->has('products')
        ->orderBy('title', 'ASC')
        ->limit(8)
        ->get();
        return view('frontend.pages.blog', [
            'posts' => $posts,
            'recent_posts' => $rcnt_post,
            'categories' => $categories,
        ]);
    }


    public function blogFilter(Request $request){
        $data=$request->all();
        // return $data;
        $catURL="";
        if(!empty($data['category'])){
            foreach($data['category'] as $category){
                if(empty($catURL)){
                    $catURL .='&category='.$category;
                }
                else{
                    $catURL .=','.$category;
                }
            }
        }

        $tagURL="";
        if(!empty($data['tag'])){
            foreach($data['tag'] as $tag){
                if(empty($tagURL)){
                    $tagURL .='&tag='.$tag;
                }
                else{
                    $tagURL .=','.$tag;
                }
            }
        }
        // return $tagURL;
            // return $catURL;
        return redirect()->route('blog',$catURL.$tagURL);
    }

    public function blogByCategory(Request $request)
    {
        $category = PostCategory::getBlogByCategory($request->slug);

        // Get posts from the category (this is likely a Collection)
        $posts = $category->post;

        // Paginate manually
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 4; // or get from query like: (int) $request->get('show', 4);
        $items = collect($posts);
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedPosts = new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $recentPosts = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $categories = Category::withCount('products')
        ->has('products')
        ->orderBy('title', 'ASC')
        ->limit(8)
        ->get();

        return view('frontend.pages.blog', [
            'posts' => $paginatedPosts,
            'recent_posts' => $recentPosts,
            'categories' => $categories,
        ]);
    }

    public function blogByTag(Request $request)
    {
        $tag = $request->slug;

        $posts = Post::getBlogByTag($tag);

        $recentPosts = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        $categories = Category::withCount('products')
            ->has('products')
            ->orderBy('title', 'ASC')
            ->limit(8)
            ->get();

        return view('frontend.pages.blog', [
            'posts' => $posts,
            'recent_posts' => $recentPosts,
            'categories' => $categories,
        ]);
    }


}
