<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeDesk extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'office_id', 'office_desk_type_id', 'desk_id', 'desk_name', 'is_available', 'status'];
}
