<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'caption', 'user_id', 'filename', 'mime', 'original_filename', 'url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
