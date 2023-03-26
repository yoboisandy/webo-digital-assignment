<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'blog_category_id',
        'status',
    ];

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
    }
}
