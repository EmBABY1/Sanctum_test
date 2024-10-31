<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'body', 'cover_image', 'pinned', 'user_id'];

    // Relationship with the User model (A post belongs to a user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-Many relationship with Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }


    protected static function booted()
    {
        static::created(function () {
            Cache::forget('stats');
        });

        static::updated(function () {
            Cache::forget('stats');
        });

        static::deleted(function () {
            Cache::forget('stats');
        });

        static::restored(function () {
            Cache::forget('stats');
        });
    }

}