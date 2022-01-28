<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePageSetting extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['id',"page_module","pricing_type","min_pricing","max_pricing",
                      'product_category',"content_title","contents",
                      'attributes',"content_position","content_priority",
                      'total_product_to_show',"total_product_in_row","meta_title",
                      "meta_description"
                    ];
}
