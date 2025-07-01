<?php
namespace App\Helpers;


use App\Models\Message;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Shipping;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

// use Auth;
class Helpers
{
    public static function messageList()
    {
        return Message::whereNull('read_at')->orderBy('created_at', 'desc')->get();
    }


    public static function calculateShippingFee(string $productName, int $quantity): int
    {
        $productName = strtolower(trim($productName));

        if ($productName === 'palm oil') {
            return 7000 * $quantity;
        }

        // For other products: flat 4000 per 1–10 units, else scales
        return 4000 * ceil($quantity / 10);
    }



    public static function postTagList($option = 'all')
    {
        if ($option = 'all') {
            return PostTag::orderBy('id', 'desc')->get();
        }
        return PostTag::has('posts')->orderBy('id', 'desc')->get();
    }

    public static function postCategoryList($option = "all")
    {
        if ($option = 'all') {
            return PostCategory::orderBy('id', 'DESC')->get();
        }
        return PostCategory::has('posts')->orderBy('id', 'DESC')->get();
    }
    // Cart Count
    public static function cartCount($user_id = '')
    {

        if (Auth::check()) {
            if ($user_id == "") $user_id = auth()->user()->id;
            return Cart::where('user_id', $user_id)->where('order_id', null)->sum('quantity');
        } else {
            return 0;
        }
    }



    // public static function totalCartPrice($user_id = '')
    // {
    //     if (Auth::check()) {
    //         if ($user_id == "") $user_id = auth()->user()->id;
    //         return Cart::where('user_id', $user_id)->where('order_id', null)->sum('amount');
    //     } else {
    //         return 0;
    //     }
    // }
    // Wishlist Count
    public static function wishlistCount($user_id = '')
    {

        if (Auth::check()) {
            if ($user_id == "") $user_id = auth()->user()->id;
            return Wishlist::where('user_id', $user_id)->where('cart_id', null)->sum('quantity');
        } else {
            return 0;
        }
    }
    // public static function getAllProductFromWishlist($user_id = '')
    // {
    //     if (Auth::check()) {
    //         if ($user_id == "") $user_id = auth()->user()->id;
    //         return Wishlist::with('product')->where('user_id', $user_id)->where('cart_id', null)->get();
    //     } else {
    //         return 0;
    //     }
    // }
    public static function totalWishlistPrice($user_id = '')
    {
        if (Auth::check()) {
            if ($user_id == "") $user_id = auth()->user()->id;
            return Wishlist::where('user_id', $user_id)->where('cart_id', null)->sum('amount');
        } else {
            return 0;
        }
    }

    // Total price with shipping and coupon
    public static function grandPrice($id, $user_id)
    {
        $order = Order::find($id);
        dd($id);
        if ($order) {
            $shipping_price = (float)$order->shipping->price;
            $order_price = self::orderPrice($id, $user_id);
            return number_format((float)($order_price + $shipping_price), 2, '.', '');
        } else {
            return 0;
        }
    }


    // Admin home
    public static function earningPerMonth()
    {
        $month_data = Order::where('status', 'delivered')->get();
        // return $month_data;
        $price = 0;
        foreach ($month_data as $data) {
            $price = $data->cart_info->sum('price');
        }
        return number_format((float)($price), 2, '.', '');
    }

    public static function shipping()
    {
        return Shipping::orderBy('id', 'DESC')->get();
    }

    public static function generateUniqueSlug($title, $modelClass)
{
    $slug = Str::slug($title);
    $count = $modelClass::where('slug', $slug)->count();

    if ($count > 0) {
        $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
    }

    return $slug;
}

}






