<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['id','name','cid'];

    public function product()
    {
    	return $this->hasMany(Product::class,'categories');
    }
     public function attributeOnProduct()
    {
    	return $this->hasMany(AttributeOnCategory::class,'category_id');
    }
}
