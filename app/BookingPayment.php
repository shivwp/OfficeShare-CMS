<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BookingPayment extends Model

{

    use HasFactory;

    protected $table='booking_payment';

    protected $fillable = [
        'id',
        'user_id',
        'booking_id',
        'space_id',
        'status',
        'message',
        'trans_id',
        'charges_id',
        'balance_transaction',
        'trans_status',
    ];


}

