<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    public function comments()
    {
        return $this->morphTo(Comment::class, 'commentable');
    }

    public function ratings()
    {
        return $this->morphTo(Rating::class, 'rateable');
    }
}
