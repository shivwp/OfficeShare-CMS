<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBankDetails extends Model
{
    use HasFactory;
    protected $table = 'user_bank_details';
    protected $fillable = [
        'id', 'card_name', 'card_number',
        'cvv', 'exp_date','user_id'
    ];

    
}
