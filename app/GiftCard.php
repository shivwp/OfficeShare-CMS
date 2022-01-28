<?php
namespace App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class GiftCard extends Model
{

     use HasFactory;
     protected $table='giftcards';
     protected $fillable=['id','title','description',
    'amount','valid_days'];

}

