<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory,SoftDeletes;
    public $dates=[
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable=['id','tax_type','tax'];
}
