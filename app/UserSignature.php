<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSignature extends Model
{
    use HasFactory;

     protected $table='user_singnature';
    protected $fillable = [
        'id','user_id','signature','booking_id',
        'ip'];

    
}
