<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOffice extends Model
{
    use HasFactory;
     protected $table	= 'office_booking_availability';
    protected $fillable = [
        'user_id', 'office_id','desk_id','start_date','end_date'];

   
}
