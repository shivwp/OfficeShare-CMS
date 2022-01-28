<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceExtraDetails extends Model
{
    use HasFactory;
    protected $table = 'property_space_extra_details';
    protected $fillable = [
        'id', 'min_term', 'max_term','key_feature','things_not_included','space_id'];

  
}
