<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class UserLike extends Model
{
    use HasFactory;

    protected $table = 'user_likes';

    protected $fillable = ['liker_id', 'liked_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function likedUser()
    {
        return $this->belongsTo(User::class, 'liked_id');
    }

    /**
     * Jaga likes_count di tabel users agar selalu sinkron
     */
    protected static function booted(): void
    {
        // Saat like dibuat → increment
        static::created(function (UserLike $like) {
            User::whereKey($like->liked_id)->increment('likes_count');
        });

        // Saat like dihapus → decrement (tidak boleh minus)
        static::deleted(function (UserLike $like) {
            User::whereKey($like->liked_id)
                ->where('likes_count', '>', 0)
                ->decrement('likes_count');
        });
    }
}
