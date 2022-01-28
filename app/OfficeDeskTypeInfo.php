<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeDeskTypeInfo extends Model
{
    use HasFactory;
    protected $fillable = ['office_id', 'office_desk_type_id', 'cost', 'discount_type', 'discount', 'image', 'no_of_desk', 'available_desk'];
}
