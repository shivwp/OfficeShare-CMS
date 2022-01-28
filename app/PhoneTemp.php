<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneTemp extends Model
{
    use HasFactory;
    protected $fillable=['id','region','phone','otp'];
}
