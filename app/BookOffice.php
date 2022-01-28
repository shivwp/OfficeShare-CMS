<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOffice extends Model
{
    use HasFactory;
     protected $table	= 'book_property_space';
    protected $fillable = [
        'user_id', 'property_id','space_id','booked_desk','	available_desk','booked_date','booking_status','booking_id','booking_price','day','period_of_day'];

   
}
