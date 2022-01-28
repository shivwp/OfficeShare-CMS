<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CouponProductCategory extends Model
{

     use HasFactory;
     protected $table='coupon_product_categories';
     protected $fillable=['id','coupon_id','category_id','exclude_category_id'];


}

