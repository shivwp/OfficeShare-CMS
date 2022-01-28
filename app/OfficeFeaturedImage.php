<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeFeaturedImage extends Model
{
    use HasFactory;

    protected $table = 'office_featured_image';

    protected $fillable = ['id','office_id','name','url'];
}
