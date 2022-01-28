<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;
      protected $fillable =['name',"phone","address","address2","address_type","city","country",'state','pincode','landmark','user_id'];
}
