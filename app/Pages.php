<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{

    use HasFactory,SoftDeletes;
    protected $fillable=['id',
        'name',
        'title',
        'content',
        'slugs',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'sections',
    ];
}
