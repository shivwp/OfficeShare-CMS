<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeAttributeValue extends Model
{
    use HasFactory;

    protected $fillable =["office_id","attribute_id","attribute_value_id"];

}
