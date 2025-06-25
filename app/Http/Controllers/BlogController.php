<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Category;
use App\Models\PostCategory;
use Illuminate\Http\Request;

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


}
