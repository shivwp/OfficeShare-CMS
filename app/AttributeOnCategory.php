<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOnCategory extends Model
{
    use HasFactory;
    protected $table="attribute_on_categories";
      public function attribute()
    {
     return $this->belongsTo(Attribute::class,'attribute_id');
    }
   public function name()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
