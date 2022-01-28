<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeAttribute extends Model
{
    use HasFactory;

    protected $fillable =["office_id","attribute_id"];
}
