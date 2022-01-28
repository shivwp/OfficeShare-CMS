<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    use HasFactory;
    protected $table="message_settings";
    protected $fillable=['id','status','name','subject','message','from_email','reply_email','msg_cat'];
}
