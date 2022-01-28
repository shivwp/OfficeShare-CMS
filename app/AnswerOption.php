<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    use HasFactory;
     protected $table	= 'answer_options';
    protected $fillable = [
        'id', 'question_id','answers'];

   
}
