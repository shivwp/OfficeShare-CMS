<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Coupon;
use App\CouponProduct;
use App\CouponProductCategory;
use App\CouponUser;
use App\Category;
use App\User;
use App\Product;
use App\Setting;
use App\MailTemplate;
use App\Mail\CouponMail;
use Illuminate\Support\Facades\Mail;

class CouponController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $d['title'] = "Manage Coupons";
      $d['coupon'] = Coupon::get();
      return view('admin.coupon.index', $d);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      $d['title'] = "Add Coupon";
      $d['products'] = Product::orderBy('pname', 'asc')->get();
      $d['categories'] = Category::where('cid', 'No Parent')->orderBy('name', 'asc')->get();
      $d['users'] = User::orderBy('id', 'desc')->get();
      return view('admin.coupon.add', $d);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      // coupon data
      $cop = Coupon::updateOrCreate(
         ['id' => $request->id],
         [
            'code' => $request->coupon,
            'description' => $request->description,
            'discount_type' => $request->type,
            'coupon_amount' => $request->coupon_amount,
            'allow_free_shipping' => $request->free_shipping == 1 ? 1 : 0,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'minimum_spend' => $request->minimum_spend,
            'maximum_spend' => $request->maximum_spend,
            'is_indivisual' => $request->indivisual == 1 ? 1 : 0,
            'exclude_sale_item' => $request->exclude_sale_item == 1 ? 1 : 0,
            'allowed_email' => $request->opt,
            'limit_per_coupon' => $request->usage_limit_per_coupon,
            'limit_per_user' => $request->usage_limit_per_user
         ]
      );
      // product id entry data
      if ($request->has('products') || $request->has('exclude_products')) {
         CouponProduct::updateOrCreate(['id' => $request->pid], [
            'coupon_id' => $cop->id,
            'product_id' => $request->has('products') ? json_encode($request->products) : '',
            'exclude_product_id' => $request->has('exclude_products') ?
               json_encode($request->exclude_products) : ''
         ]);
      }
      if (
         $request->has('pid') && !$request->has('products')
         && !$request->has('exclude_products')
      ) {
         CouponProduct::destroy($request->pid);
      }
      // categories id entry data
      if ($request->has('product_categories') || $request->has('exclude_categories')) {
         CouponProductCategory::updateOrCreate(['id' => $request->cid], [
            'coupon_id' => $cop->id,
            'category_id' => $request->has('product_categories') ? json_encode($request->product_categories) : '',
            'exclude_category_id' => $request->has('exclude_categories') ?
               json_encode($request->exclude_categories) : ''
         ]);
      }
      if (
         $request->has('cid') && !$request->has('product_categories')
         && !$request->has('exclude_categories')
      ) {
         CouponProductCategory::destroy($request->cid);
      }
      // user id entry data
      if ($request->has('allowed_email')) {
         CouponUser::updateOrCreate(['id' => $request->uid], [
            'coupon_id' => $cop->id,
            'email_id' => json_encode($request->allowed_email)
         ]);
      }
      if ($request->has('uid') && !$request->has('allowed_email')) {
         CouponUser::destroy($request->uid);
      }
      return redirect('dashboard/coupon')
         ->with('msg', 'Coupon added or Updated successfully');
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      $d['title'] = "Edit Coupon";
      $d['edcoupon'] = $cop = Coupon::find($id);
      $cup = CouponProduct::where('coupon_id', $cop->id)->first();
      $cupcat = CouponProductCategory::where('coupon_id', $cop->id)->first();
      $cupuser   = CouponUser::select('email_id', 'id')->where('coupon_id', $cop->id)->first();
      if (!empty($cupuser)) {
         $d['uid'] = $cupuser->id;
         $d['cup_users'] = json_decode($cupuser->email_id, true);
      }
      $d['pid'] = $cup->id;
      if (!empty($cup->product_id) && !empty($cup)) {
         $d['inc_products'] = Product::whereIn('id', json_decode($cup->product_id, true))
            ->orderBy('pname', 'asc')->get();
      }
      if (!empty($cup->exclude_product_id) && !empty($cup)) {
         $d['exc_products'] = Product::whereIn('id', json_decode($cup->exclude_product_id, true))
            ->orderBy('pname', 'asc')->get();
      }
      if (!empty($cupcat) && !empty($cupcat->category_id)) {
         $d['cid'] = $cupcat->id;
         $d['inc_prod_cats'] = Category::whereIn('id', json_decode($cupcat->category_id, true))
            ->orderBy('name', 'asc')->get();
      }
      if (!empty($cupcat) && !empty($cupcat->exclude_category_id)) {
         $d['exc_prod_cats'] = Category::whereIn('id', json_decode($cupcat->exclude_category_id, true))->orderBy('name', 'asc')->get();
      }
      $d['products'] = Product::orderBy('pname', 'asc')->get();
      $d['categories'] = Category::where('cid', 'No Parent')->orderBy('name', 'asc')->get();
      $d['users'] = User::orderBy('id', 'desc')->get();
      return view('admin.coupon.add', $d);
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      Coupon::destroy($id);
   }

   public function couponMail($id)
   {
      $coupon = Coupon::with(['user', 'product', 'productCategory'])->find($id);
      if (isset($coupon->user)) {
         $user = json_decode($coupon->user['email_id'], true);
         foreach ($user as  $value) {
            $usr = User::where('email', $value)->first();
            if (isset($usr))
               $abc = $this->sendmail($usr, $coupon);
            return $abc;
         }
      } else {
         $user = User::all();
         if (count($user) > 0) {
            foreach ($user as $value) {
               $this->sendmail($value, $coupon);
            }
         }
      }
      return back()->with('msg', "Email sent successfully");
   }

   public function sendmail($user, $coupon)
   {
      $root = (new Request())->root();
      $base_url = $_SERVER["HTTP_HOST"];
      $base_url = $root . $base_url;
      $setting = Setting::first();
      $basicinfo = [
         '{user_name}' => $user->name,
         '{coupon_code}' => $coupon->code,
         '{code_expiry_date}' => $coupon->expiry_date,
         '{coupon_description}' => $coupon->description,
         '{code_amount}' => $coupon->coupon_amount,
         '{limit_per_user}' => $coupon->limit_per_user,
         '{limit_per_coupon}' => $coupon->limit_per_coupon,
         '{site_url}' => $setting->site_url,
         '{business_logo}' => '<img src="' . url('storage/app') . '/' . $setting->logo . '" style="width:200px;height:60px;">',
         '{business_name}' => $setting->business_name,
      ];
      //check for product list
      if (!empty($coupon->product['product_id'])) {
         $ids = json_decode($coupon->product['product_id'], true);
         $product = Product::whereIn('id', $ids)->get();
         $productCollect = "<table style='width:100%'><tr>";
         foreach ($product as  $value) {
            $thumb = json_decode($value->thumbnails, true);
            $productCollect .= "<td><img src='" . url('product/thumbnail') . "/" . $thumb[0] . "' style='width:120px;height:80px;'  /> </td>" .
               "<td>" . $value->pname . "</td>" .
               "<td><a href='" . $base_url . "/product-details/" . $value->id . "' target='_blank'>Shop Now</a></td>";
         }
         $productCollect .= "</tr></table>";
         $basicinfo['{product}'] = $productCollect;
      }
      // check for category list
      if (!empty($coupon->productCategory['category_id'])) {
         $ids = json_decode($coupon->productCategory['category_id'], true);
         $category = Category::whereIn('id', $ids)->get();
         $categoryCollect = "<table style='width:100%'><tr>";
         foreach ($category as  $value) {
            $categoryCollect .=
               "<td>" . $value->name . "</td>" .
               "<td><a href='" . $base_url . "/category-page/" . $value->id . "' target='_blank'>Shop Now</a></td>";
         }
         $categoryCollect .= "</tr></table>";
         $basicinfo['{product_category}'] = $categoryCollect;
      }

      $msgData = MailTemplate::where('status', 'coupon')->first();
      $replMsg = MailTemplate::where('status', 'coupon')->pluck('message')->first();
      foreach ($basicinfo as $key => $info) {
         $replMsg = str_replace($key, $info, $replMsg);
      }
      if (isset($msgData) && isset($replMsg)) {
         $config = ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name];
         Mail::to($user->email)->send(new CouponMail($config));
         return $replMsg;
      }
   }
}
