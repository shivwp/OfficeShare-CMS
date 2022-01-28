<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageMeta extends Model
{

    use HasFactory;
    protected $table = "page_meta";
    protected $fillable=[
        'id',
        'page_id',
        'meta_key',
        'meta_value',
    ];
}
