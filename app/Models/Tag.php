<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Many-to-Many relationship with Post
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}