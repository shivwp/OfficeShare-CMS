<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'media_type', 'title',
        'short_description', 'description', 'meta_title',
        'meta_keyword', 'meta_description', 'user_id', 'feature', "category_id", "slug", "sticky","status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
