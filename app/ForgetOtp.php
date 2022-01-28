<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class ForgetOtp extends Model

{

    use HasFactory;

    protected $table='forget_password_otp';

    protected $fillable = ['id','user_id','otp'];






}

