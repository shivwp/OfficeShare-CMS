<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = "wishlist";
    public function officedesk()
    {
        return $this->belongsTo(OfficeDesk::class, 'desk_id');
    }
    protected $fillable = ['property_id', 'user_id'];
}
