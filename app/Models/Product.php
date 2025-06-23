<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
class Product extends Model
{
    protected $fillable=['name','slug','description','category_id','price','weight','discount','photo','stock',];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class)->whereNotNull('order_id');
    }

    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }


    public static function countActiveProduct(){
        $data=Product::count();
        if($data){
            return $data;
        }
        return 0;
    }


}
