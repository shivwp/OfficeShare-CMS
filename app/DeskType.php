<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeskType extends Model
{
    use HasFactory;
    protected $fillable=['id','types','status'];
}
