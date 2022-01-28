<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
	protected $table	= 'user_card_details';

	protected $fillable =[
		'user_id',
		'user_customer_id',
		'card_token',
		'last4',
		'expiry_month',
		'expiry_year',
		'card_type',
		'gateway',
		'default_card',
		'card_id'
	];
	public $timestamps=true;
    use HasFactory;

   
}
