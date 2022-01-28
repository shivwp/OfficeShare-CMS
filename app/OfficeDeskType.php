<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeDeskType extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'office_id', 'desk_type_id', 'desk_type', 'status'];
}
