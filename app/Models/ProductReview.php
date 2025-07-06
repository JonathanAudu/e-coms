<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rate', 'review', 'status'];

    // Review belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Review belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Get all reviews (admin or public)
    public static function getAllReview()
    {
        return self::with(['user', 'product'])->paginate(10);
    }

    // Get current logged-in user's reviews
    public static function getAllUserReview()
    {
        return self::where('user_id', auth()->id())
            ->with(['user', 'product'])
            ->paginate(10);
    }
}
