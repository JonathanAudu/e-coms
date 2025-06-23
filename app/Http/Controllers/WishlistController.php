<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        $categories = Category::withCount('products')
        ->has('products')
        ->orderBy('title', 'ASC')
        ->limit(8)
        ->get();
        return view('frontend.pages.wishlist', compact('wishlists', 'categories'));
    }

    public function store($productId)
    {
        $user = Auth::user();

        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($exists) {
            return back()->with('error', 'Product already in wishlist.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return back()->with('success', 'Added to wishlist!');
    }


    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        if ($wishlist->user_id == Auth::id()) {
            $wishlist->delete();
            return back()->with('success', 'Removed from wishlist.');
        }

        return back()->with('error', 'Unauthorized action.');
    }
}
