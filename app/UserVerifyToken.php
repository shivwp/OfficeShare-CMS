<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use User;

class UserVerifyToken extends Model
{
    use HasFactory;

     protected $table = "user_verify_token";

    protected $fillable = ['id','user_id','token'];


 
}
