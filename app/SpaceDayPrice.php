<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceDayPrice extends Model
{
	protected $table	= 'space_days_price_list';

	protected $fillable =[
		'id',
		'space_id',
		'day',
		'price'
	];
	public $timestamps=true;
    use HasFactory;

   
}
