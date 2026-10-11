<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password',
        'age','job','location','about',
        'hobby','lifestyle','personality','love_language',
        'cover_image','gender','religion','languages','social_links',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'album_photos'      => 'array',
    ];

    public function getAlbumPhotosAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $decoded = is_string($value) ? json_decode($value, true) : null;
        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($value) && trim($value) !== '') {
            return array_map('trim', explode(',', $value));
        }

        return [];
    }

    public function getAvatarUrlAttribute()
{
    $placeholder = asset('assets/img/icons/card-placeholder.png');

    if (empty($this->avatar)) {
        return $placeholder;
    }

    // path baru: assets/...
    if (Str::startsWith($this->avatar, 'assets/')) {
        $full = public_path($this->avatar);
        return file_exists($full) ? asset($this->avatar) : $placeholder;
    }

    // path lama: storage public
    if (Storage::disk('public')->exists($this->avatar)) {
        return Storage::disk('public')->url($this->avatar);
    }

    return $placeholder;
}

public function getCoverImageUrlAttribute()
{
    if (empty($this->cover_image)) {
        return null;
    }

       if (Str::startsWith($this->avatar, 'https://lh3.googleusercontent.com')) {
        return $placeholder;
    }
    
    // path baru: assets/...
    if (Str::startsWith($this->cover_image, 'assets/')) {
        $full = public_path($this->cover_image);
        return file_exists($full) ? asset($this->cover_image) : null;
    }

    // path lama: storage public
    if (Storage::disk('public')->exists($this->cover_image)) {
        return Storage::disk('public')->url($this->cover_image);
    }

    return null;
}

    public function likes()
    {
        return $this->hasMany(UserLike::class, 'liker_id', 'id');
    }

    public function likedBy()
    {
        return $this->hasMany(UserLike::class, 'liked_id', 'id');
    }

    public function getLikedByAuthAttribute(): bool
    {
        $authId = auth()->id();
        if (!$authId) return false;

        // cek apakah ada baris like dari auth ke this user
        return $this->likedBy()
            ->where('liker_id', $authId)
            ->exists();
    }

    public function deleteFeedbacks()
{
    return $this->hasMany(\App\Models\DeleteFeedback::class);
}

}
