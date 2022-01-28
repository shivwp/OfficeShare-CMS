<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharerAddresses extends Model
{
    use HasFactory;

     protected $table  = 'sharer_address';

    protected $fillable = [
        'id', 'office_id', 'country_id',
        'state_id', 'city_id', 'postcode',
        'address', 'address2', 'longitude', 'latitude','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
