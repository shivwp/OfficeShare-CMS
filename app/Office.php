<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use User;

class Office extends Model
{
    use HasFactory;

    protected $fillable = ['id','office_name','short_description','long_description','cost','discount','thumbnail','total_desk','user_id','city','is_approved'];




 

 
}
