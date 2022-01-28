<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    
    public $table = 'user_notification';
    protected $fillable = [
        'user_id', 
        'title', 
        'body',
        'status', 
        'created_at', 
        'updated_at',
    ];
      
}
