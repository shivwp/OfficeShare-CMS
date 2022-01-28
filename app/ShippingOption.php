<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingOption extends Model
{
    use HasFactory;
    protected $fillable=['selling_zone_id',"shipping_option","order_above","label",'cost','default_status'];
}
