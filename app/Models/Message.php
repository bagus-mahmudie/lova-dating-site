<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'body', 'read_at'];

    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }

     protected $casts = [
        'sender_id'   => 'integer',
        'receiver_id' => 'integer',
        'id'          => 'integer',
    ];
    // Ambil percakapan 2 arah antara user A dan B
    public function scopeBetween($q, int $a, int $b)
    {
        return $q->where(function ($q) use ($a, $b) {
            $q->where('sender_id', $a)->where('receiver_id', $b);
        })->orWhere(function ($q) use ($a, $b) {
            $q->where('sender_id', $b)->where('receiver_id', $a);
        });
    }
}
