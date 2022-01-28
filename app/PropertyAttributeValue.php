<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAttributeValue extends Model
{
    use HasFactory;

    protected $table = "property_attributes_values";

    protected $fillable =["property_id","attribute_id","attribute_value_id"];

}
