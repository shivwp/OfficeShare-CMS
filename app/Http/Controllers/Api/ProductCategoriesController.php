<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\ProductApiController;
use Illuminate\Http\Request;
use App\Category;
use App\Wishlist;
use App\AttributeOnCategory;
use Illuminate\Support\Facades\Auth;
class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $cat= Category::where('cid',"No Parent")->where('status',1)->get();
      $categ=[];
      $i=0;
   
      foreach($cat as $value) {

         $subc=Category::select('id','name')->where('cid',$value->name)->get();
         $categ[$i++]=array('id'=>$value->id,"main_menu"=>$value->name,"sub_menu"=>$subc);
         } 
     return response()->json(["product_categories"=>$categ],200);  
    }

    public function wishlist(Request $r)
    {
         if(Auth::guard('api')->check()){
            $user=Auth::guard('api')->user();
            if($user->id==$r->user_id){
                    $w=Wishlist::updateOrCreate(['product_id'=>$r->product_id],[
                        'product_id'=>$r->product_id,
                        'user_id'=>$r->user_id
                    ]);
                    $w=Wishlist::select("id as wishlist_item_id","product_id","user_id")->get();
                    return response()->json(['status'=>true,"msg"=>"Added to wishlist",'data'=>$w],200); 
            }
               else{
                return response()->json(["msg"=>"Invalid user id","status"=>false],200);
            } 
            
         }
   
    }
    public function getWishlist($id)
    {
        
        if(Auth::guard('api')->check()){
            $user=Auth::guard('api')->user();
            if($user->id==$id){
       $w=Wishlist::with('product')->where('user_id',$id)->get();
        $pCollection=[];
        $i=0;$j=0;$wid="";
        if(isset($w)){
            foreach ($w as $p) {
                    foreach(json_decode($p->product['thumbnails'],true) as $thmb){
                        $thumb[$j++]=url("product/thumbnail")."/".$thmb;
                    }
                $pCollection[$i++]=[
                    "id"=>$p->product['id'],
                    "wishlist_item_id"=>$p->id,
                    "is_in_wishlist"=>true,
                    "sku_id"=>$p->product->sku_id,
                    "quantity"=>$p->product['stock'],
                    "product_name"=>$p->product['pname'],
                    "product_price"=>$p->product['s_price'],
                    "discount"=>$p->product->discount."%",
                    "total_price"=>round($p->product->s_price-($p->product->s_price*$p->product->discount/100)),
                    "total_saving"=>round((($p->product->discount/100)*$p->product->s_price)),
                    "meta_key"=>$p->product->meta_key,
                    "meta_title"=>$p->product->meta_title,
                    'return_policy'=>$p->product->return_policy,
                    "shipping"=>$p->product->shipping=="paid"?$p->product->shipping_charge:"free",
                    "stock"=>$p->product->stock>0?"In stock":"Out of stock",
                    "short_descript"=>$p->product->p_s_description,
                    "long_descript"=>$p->product->p_description,
                    "feature_description"=>$p->product->feature,
                    "product_thumbnail"=> $thumb,
                     ];
                     $thumb=[];
                     $j=0;
            }
        } 
        if(!empty($pCollection)){
           return response()->json($pCollection,200);  
        }else{
           return response()->json([$pCollection,'msg'=>"Wishlist is empty",'status'=>false],200);  
        }        
        }else{
                return response()->json(["msg"=>"Invalid token"],200);
            }
        }
       
    }
    public function removeWishlist($id)
    {
        $d="";
        $st=Wishlist::destroy($id);
         if(Auth::guard('api')->check()){
            $user=Auth::guard('api')->user();
            $d=Wishlist::select("id as wishlist_item_id","product_id","user_id")->where("user_id",$user->id)->get();
         }

        if($st){
        return response()->json(['status'=>true,'msg'=>"Removed product from wishlist",'data'=>$d],200);
        }else{
            return response ()->json(["msg"=>"No wishlist product to remove"],200);
        }
    }
}
