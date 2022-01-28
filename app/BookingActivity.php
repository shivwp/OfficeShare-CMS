<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BookingActivity extends Model

{

    use HasFactory;

    protected $table='booking_activity';

    protected $fillable = ['id','order_id','message'];


}

