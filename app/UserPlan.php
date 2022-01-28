<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;

    public $table = 'users_plan';
    protected $fillable = [
        'user_id', 
        'plan_id', 
        'description',
        'validity', 
        'price', 
        'from_date',
        'to_date', 
        'status',
        'transaction_id',
        'payment_status',
        'charges_id',
        'balance_transaction',
    ];

 	public function user()
    {
        return $this->belongsTo(User::class);
    }
   
}
