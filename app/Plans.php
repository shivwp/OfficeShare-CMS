<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'title', 'description',
        'validity', 'price','number','type'
    ];
    

 	public function propertyspace()
    {
        return $this->belongsToMany(SpaceType::class);
    }

    public function features()
    {
        return $this->belongsToMany(PlanFeature::class);
    }

   
}
