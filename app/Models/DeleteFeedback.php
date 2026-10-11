<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeleteFeedback extends Model
{
    // karena tabelnya singular
    protected $table = 'delete_feedback';

    protected $fillable = [
        'user_id', 'user_email', 'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
