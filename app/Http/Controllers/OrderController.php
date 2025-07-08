<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Helpers\Helpers;
use App\Models\Category;
use Barryvdh\DomPDF\PDF;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with('orderItems')->latest()->paginate(10);
        return view('backend.order.index')->with('orders',$orders);
    }


    public function show($id)
    {
        $order=Order::find($id);
        return view('backend.order.show')->with('order',$order);
    }

    public function edit($id)
    {
        $order=Order::find($id);
        return view('backend.order.edit')->with('order',$order);
    }

    public function update(Request $request, $id)
    {
        $order=Order::find($id);
        $this->validate($request,[
            'status'=>'required|in:new,process,delivered,cancel'
        ]);
        $data=$request->all();
        // return $request->status;
        if($request->status=='delivered'){
            foreach($order->orderItems as $item){
                $product = $item->product;
                if ($product) {
                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }
        }
        $status=$order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated order');
        }
        else{
            request()->session()->flash('error','Error while updating order');
        }
        return redirect()->route('order.index');
    }

     // PDF generate
     public function pdf(Request $request){
        $order=Order::getAllOrder($request->id);
        // return $order;
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        // return $file_name;
        $pdf=PDF::loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }

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
            $shipping = 0;

            foreach ($cart as $key => $item) {
                $price = auth()->check() ? $item->product->price : $item['price'];
                $qty = auth()->check() ? $item->quantity : $item['quantity'];
                $name = auth()->check() ? $item->product->name : $item['name'];
                $total += $price * $qty;

                $shipping += Helpers::calculateShippingFee($name, $qty);
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
               'total'            => $total + $shipping,
                'shipping_fee'     => $shipping,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart as $key => $item) {
                $product = auth()->check() ? $item->product : Product::find($key);

            OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'price'      => $product->price,
                    'quantity'   =>  $qty,
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
                return redirect()->route('paystack.redirect', $order->id);
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

    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }
}
