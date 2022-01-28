<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $table="attributes";
    protected $fillable=['id','name','display_name'];
    public function attributeValue()
    {
        return $this->hasMany(AttributeValue::class,'attribute_id');
    }
     public function category()
    {
        return $this->hasMany(AttributeOnCategory::class,'attribute_id');
    }

}
