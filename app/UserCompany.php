<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    use HasFactory;
    protected $table="user_company";
    protected $fillable=['id','entity','entity_type',
    "company_name","address","indentity_type","identity_info","authorised_signature"];
}
