<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ProductAttributeValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\DB;
use App\Attribute;
use App\AttributeValue;

class FilterProductApiController extends Controller
{

    public function __construct(Request $request)
    {

        //dd($request->api_key);

        $apitoken = $request->header('api_key');

        if (empty($apitoken)) {
            $response = json_encode(array(
                'status' => false,
                'message' => 'Please Provide Api Token',
            ));
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
        if ($apitoken != env("api_key")) {
            $response = json_encode(array(
                'status' => false,
                'message' => 'Api Token Not valid',
            ));
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
    }


    public function filterProduct(Request $r)
    {
        if (!empty($r->category_id)) {
            $sql = "SELECT distinct(p.id) , p.* FROM eshakti_products as p ";
            if ($r->filter_data[0]['type'] == "attribute" && !empty($r->filter_data[0]) && !empty($r->filter_data[0]['attributes_id'])) {
                $imp = implode(',', $r->filter_data[0]['attributes_id']);
                $sql .= " INNER JOIN eshakti_product_attributes_values attr on p.id=attr.product_id  AND attr.attribute_value_id IN($imp) AND p.product_type='simple'";
            }
            if ($r->filter_data[1]['type'] == "color" && !empty($r->filter_data[1]) && !empty($r->filter_data[1]['attributes_id'])) {
                $imp = implode(',', $r->filter_data[1]['attributes_id']);
                $sql .= " INNER JOIN  eshakti_set_product_variants clr on p.id=clr.product_id AND clr.deleted_at IS NULL AND clr.color_id IN($imp) AND p.product_type='simple'";
            }
            if ($r->filter_data[2]['type'] == "price" && !empty($r->filter_data[2]) && !empty($r->filter_data[2]['min_price']) && !empty($r->filter_data[2]['max_price'])) {
                $sql .= " WHERE p.selling_price >=" . $r->filter_data[2]['min_price'] . " AND p.selling_price <= " . $r->filter_data[2]['max_price'] . " AND p.product_type='simple' AND p.categories=" . $r->category_id . " OR p.sub_category=" . $r->category_id;
            } else {
                $sql .= " WHERE   p.product_type='simple' AND p.categories=" . $r->category_id . " OR p.sub_category=" . $r->category_id . " order by s_price " . $r->sorting;
            }

            $product = DB::select($sql);
            // return $product;
            $obj = new ProductApiController;
            $p = $obj->productLists($product, null, null);
            //return $p;
            return response()->json(['product' => $p, 'status' => true], 200);
        } else {
            return response()->json(['status' => false, ''], 200);
        }
    }

    public function filters(Request $request)
    {
       if(isset($request->filter_key) && !empty($request->filter_key)){
        $Attributes = Attribute::select('id','display_name as filer_title','description','type as filter_type')
                   ->where('name','=',$request->filter_key)
                   ->get();
       }else{
        $Attributes = Attribute::select('id','display_name as filer_title','description','type as filter_type')
        ->where('id','!=',7)
                   ->get();
       }

       foreach ($Attributes as $key => $value) {

                if($value->description == Null){

                    $value->description = '';
                 }

            $Attr_values = AttributeValue::select('id','attribute_id','value as title','icon','is_selected')
            ->where('attribute_id','=',$value->id)
            ->get();
            foreach ($Attr_values as $attr_k => $attr_val) {
                if(!empty($attr_val->icon)){
                    $attr_val->icon = url($attr_val->icon);
                }
                if($attr_val->is_selected == 1){
                    $attr_val->is_selected = true;
                }else{
                    $attr_val->is_selected = false;
                }
              
            }

            $Attributes[$key]['filter_list'] = $Attr_values;

       }


        return response()->json(['status' => true, 'message' => "success", 'filter' => $Attributes], 200);
    }

     public function filters_bk(Request $request)
    {

       

     $Attributes = Attribute::select('id','display_name as filer_title','description','type as filter_type')
                   ->where('id','=','2')
                   ->get();

        foreach ($Attributes as $key => $value) {

                if($value->description == Null){

                    $value->description = '';
                 }

                $Attr_values = AttributeValue::select('id','attribute_id','value as title','icon','is_selected')
                ->where('attribute_id','=',$value->id)
                ->get();
                foreach ($Attr_values as $attr_k => $attr_val) {
                    if(!empty($attr_val->icon)){
                        $attr_val->icon = url($attr_val->icon);
                    }
                    if($attr_val->is_selected == 1){
                        $attr_val->is_selected = true;
                    }else{
                        $attr_val->is_selected = false;
                    }
                  
                }

                $Attributes[$key]['filter_list'] = $Attr_values;

        }

          $attr_data = [
           
            [
                "id" =>  3,
                "filer_title" => "Experience rating",
                "description" => "0",
                "filter_type" => "star",
                "filter_list" =>[
                    [
                        "id"            => 10,
                        "attribute_id"  => 3,
                        "title"         => "star",
                        "icon"          => "http://officeshare-cms.ewtlive.in/attribute_icon/60d2c020611bajpg",
                        "is_selected"   => false
                    ]

                ]

            ],
            [
                "id" => 6,
                "filer_title" => "Number of desks",
                "description" => "",
                "filter_type" => "desk",
                "filter_list" => []
            ]

        ];
        $a = (object)$attr_data;



        
    $variable = [];

    array_push($variable, $Attributes[0]);
    array_push($variable, $a);

//return($variable);

        return response()->json(['status' => true, 'message' => "success", 'filter' => $variable], 200);




       if(isset($request->filter_key) && !empty($request->filter_key)){
        $Attributes = Attribute::select('id','display_name as filer_title','description','type as filter_type')
                   ->where('name','=',$request->filter_key)
                   ->get();
       }else{
        $Attributes = Attribute::select('id','display_name as filer_title','description','type as filter_type')
        ->where('id','!=',7)
                   ->get();
       }

       foreach ($Attributes as $key => $value) {

                if($value->description == Null){

                    $value->description = '';
                 }

            $Attr_values = AttributeValue::select('id','attribute_id','value as title','icon','is_selected')
            ->where('attribute_id','=',$value->id)
            ->get();
            foreach ($Attr_values as $attr_k => $attr_val) {
                if(!empty($attr_val->icon)){
                    $attr_val->icon = url($attr_val->icon);
                }
                if($attr_val->is_selected == 1){
                    $attr_val->is_selected = true;
                }else{
                    $attr_val->is_selected = false;
                }
              
            }

            $Attributes[$key]['filter_list'] = $Attr_values;

       }

        $attr_data = [
           
            [
                "id" =>  3,
                "filer_title" => "Experience rating",
                "description" => "0",
                "filter_type" => "star",
                "filter_list" =>[
                    [
                        "id"            => 10,
                        "attribute_id"  => 3,
                        "title"         => "star",
                        "icon"          => "http://officeshare-cms.ewtlive.in/attribute_icon/60d2c020611bajpg",
                        "is_selected"   => false
                    ]

                ]

            ],
            [
                "id" => 6,
                "filer_title" => "Number of desks",
                "description" => "",
                "filter_type" => "desk",
                "filter_list" => []
            ]

        ];




        return response()->json(['status' => true, 'message' => "success", 'filter' => $Attributes], 200);
    }
}
