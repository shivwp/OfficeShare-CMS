<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

     protected $table='booking_payment_refund';
    protected $fillable = [
        'id','title',
        'message'];

    
}
