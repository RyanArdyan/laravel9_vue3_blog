<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['title', 'slug', 'user_id', 'category_id', 'imagePath', 'body'];

    protected $with = ['user'];

    // 1 postingan ditulis oleh satu user, tidak mungkin 2
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
