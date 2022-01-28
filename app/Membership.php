<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $fillable=['id','name','price','plateform_charges',
    'subscribstion_type','noofproperty','description','slug',
    "meta_title",'meta_keyword',"meta_description","features"];
}
