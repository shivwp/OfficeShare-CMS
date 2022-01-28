<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExtraDetails extends Model
{
    use HasFactory;
    protected $table = 'property_extra_details';
    protected $fillable = [
        'id', 'property_id', 'disability_access','describe_your_space','how_to_find_us','insurance','covid_19_secure'
    ];

  
}
