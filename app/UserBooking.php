<?php

namespace App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBooking extends Model
{
    use HasFactory;
    protected $table	= 'booking';
    protected $fillable  = [
        'id',
        'is_recurring',
        'user_id',
        'space_id',
        'price',
        'start_date',
        'data',
        'is_approved',
        'landload_id',
        'user_name',
        'property_id',
        'request_together',
        'period_of_day',
        'end_date',
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }
   
}