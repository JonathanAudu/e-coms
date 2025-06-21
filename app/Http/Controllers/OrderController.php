<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkoutForm()
    {
        if (!auth()->check()) {
            return redirect()->route('login.form')->with('error', 'Please log in to proceed to checkout.');
        }
        $cart = auth()->check()
            ? Cart::with('product')->where('user_id', auth()->id())->get()
            : session('cart', []);

            $categories = Category::withCount('products')
            ->has('products')
            ->orderBy('title', 'ASC')
            ->limit(8)
            ->get();

        return view('frontend.pages.checkout', compact('cart', 'categories'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',
            'post_code'       => 'nullable|string|max:20',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
            'payment_method'  => 'required|in:cash_on_delivery,stripe,paystack',
        ]);

        $cart = auth()->check()
            ? Cart::with('product')->where('user_id', auth()->id())->get()
            : session('cart', []);

        if (!$cart || (is_countable($cart) && count($cart) === 0)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();
        try {
            $total = 0;

            foreach ($cart as $key => $item) {
                $price = auth()->check() ? $item->product->price : $item['price'];
                $qty = auth()->check() ? $item->quantity : $item['quantity'];
                $total += $price * $qty;
            }

            $order = Order::create([
                'order_number'     => $this->generateOrderNumber(),
                'user_id'          => auth()->id(),
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'address'          => $request->address,
                'post_code'        => $request->post_code,
                'city'             => $request->city,
                'country'          => $request->country,
                'state'            => $request->state,
                'notes'            => $request->notes,
                'total'            => $total,
                'payment_method'   => $request->payment_method,
            ]);

            foreach ($cart as $key => $item) {
                $product = auth()->check() ? $item->product : Product::find($key);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'price'      => $product->price,
                    'quantity'   => auth()->check() ? $item->quantity : $item['quantity'],
                ]);
            }

            // Clear cart
            if (auth()->check()) {
               Cart::where('user_id', auth()->id())->delete();
            } else {
                session()->forget('cart');
            }

            DB::commit();
            if ($request->payment_method === 'cash_on_delivery') {
                return redirect()->route('checkout.thankyou')->with('success', 'Order placed successfully!');
            }

            if ($request->payment_method === 'paystack') {
                return redirect()->route('pay');
            }

            if ($request->payment_method === 'stripe') {
                return redirect()->route('stripe.checkout', ['order' => $order->id]);
            }

            return redirect()->route('checkout.thankyou')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function thankYou()
    {
        $categories = Category::withCount('products')
        ->has('products')
        ->orderBy('title', 'ASC')
        ->limit(8)
        ->get();
        return view('frontend.pages.thankyou', compact('categories'));
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(Str::random(8));
    }
}
