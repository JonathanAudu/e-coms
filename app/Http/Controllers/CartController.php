<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;


class CartController extends Controller
{

    public function addToCart(Request $request){
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if (auth()->check()) {
            // Store in DB for logged-in users
            $item = Cart::firstOrNew([
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            $item->quantity += $quantity;
            $item->save();
        } else {
            // Store in session
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->price,
                    "photo" => explode(',', $product->photo)[0],
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }


    public function index()
{
    if (auth()->check()) {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
    } else {
        $cartItems = session()->get('cart', []);
    }
    $categories = Category::withCount('products')
    ->has('products')
    ->orderBy('title', 'ASC')
    ->limit(8)
    ->get();
    

    return view('frontend.pages.cart', compact('cartItems', 'categories'));
}


    public function cartDelete(Request $request){
        if (auth()->check()) {
            Cart::where('user_id', auth()->id())
                    ->where('product_id', $request->product_id)
                    ->delete();
        } else {
            $cart = session()->get('cart');
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function cartUpdate(Request $request){
        if (auth()->check()) {
            Cart::where('user_id', auth()->id())
                    ->where('product_id', $request->product_id)
                    ->update(['quantity' => $request->quantity]);
        } else {
            $cart = session()->get('cart');
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }
}
