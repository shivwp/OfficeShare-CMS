<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
     protected $table	= 'questions';
    protected $fillable = [
        'id', 'questions'];

   
}