<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

     use HasFactory;
     protected $table='coupon';
     protected $fillable=['id','code','description',
    'discount_type','coupon_amount','allow_free_shipping',
    'start_date','expiry_date','minimum_spend','maximum_spend',
    'is_indivisual','exclude_sale_item',
    'limit_per_coupon','limit_per_user'];
    public function product()
    {
      return $this->hasOne(CouponProduct::class,'coupon_id');
    }
    public function productCategory()
    {
      return $this->hasOne(CouponProductCategory::class,'coupon_id');
    }
    public function user()
    {
      return $this->hasOne(CouponUser::class,'coupon_id');
    }

}

