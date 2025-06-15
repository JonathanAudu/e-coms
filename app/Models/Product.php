<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
class Product extends Model
{
    protected $fillable=['name','slug','description','category_id','price','discount','photo','stock',];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class)->whereNotNull('order_id');
    }

    public function wishlists(){
        return $this->hasMany(Wishlist::class)->whereNotNull('cart_id');
    }

    public static function countActiveProduct(){
        $data=Product::count();
        if($data){
            return $data;
        }
        return 0;
    }


}
