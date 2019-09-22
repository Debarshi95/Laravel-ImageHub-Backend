<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'caption', 'filename', 'mime', 'original_filename'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
