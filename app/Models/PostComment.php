<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'comment', 'replied_comment', 'parent_id', 'status'];

    public function user_info()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public static function getAllComments()
    {
        return self::with(['user_info', 'post'])->orderByDesc('created_at')->paginate(10);
    }

    public static function getAllUserComments()
    {
        return PostComment::where('user_id', auth()->user()->id)->with('user_info')->paginate(10);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }


    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id')->where('status', 'active');
    }
}
