<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
	protected $fillable =['user_id','first_name','last_name','dob','email','postcode','address_1','address_2','city','state','country','booking_id','phone'];
	 public $timestamps=true;
    use HasFactory;

    public function user()
    {

    	return $this->belongsTo("App\Models\User");
    }
}
