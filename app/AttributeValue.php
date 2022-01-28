<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;
    protected $table="attributevalues";
    protected $fillable=['id','value','attribute_id','icon'];
    public function attributeName()
    {
        return $this->belongsTo(Attribute::class,'attribute_id');
    }

}
