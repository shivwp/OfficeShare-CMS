@extends('layouts.admin')
@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<div class="row">
   <div class="col-sm-6">
      <h4>{{ $title }}</h4>
   </div>
   <div class="col-sm-6 text-right">
      <a href="{{ route('dashboard.mail-template.create') }}" class="addnew-btn">View Templates</a>
   </div>
</div>
<div class="card">
   <div class="card-body"id="add_space">
      <div class="form-wrap">
         <form method="post" enctype="multipart/form-data" action="{{ route("dashboard.mail-template.store") }}">
         @csrf
         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">From Name<span class=" text-danger">*</span></label>
                  <input type="text" id="firstName" class="form-control" placeholder="Name" name="name" value="{{ isset($msg->name)?$msg->name:'' }}" required="">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">Subject<span class=" text-danger">*</span></label>
                  <input type="text" id="firstName" class="form-control" placeholder="message subject" name="subject" value="{{ isset($msg->subject)?$msg->subject: '' }}" required="">
                  <input type="hidden" name="mid" value="{{ isset($msg->id)?$msg->id: '' }}">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">Message Category<span class=" text-danger">*</span></label>
                  <select name="msg_cat" class="form-control msgCatg" id="msgCatg" required="">
                     <option value="">{{ isset($msg->msg_cat)?$msg->msg_cat:"Message Category" }}</option>
                     <option value="sign up" {{ isset($msg->msg_cat) && $msg->msg_cat=="sign up"?'selected':"" }}>
                     Sign up Mail
                     </option>
                     <option value="order" {{ isset($msg->msg_cat) && $msg->msg_cat=="order"?'selected':"" }}>
                     Order Mail
                     </option>
                     <option value="gift" {{ isset($msg->msg_cat) && $msg->msg_cat=="gift"?'selected':"" }}>
                     Gift Card Mail
                     </option>
                     <option value="coupon" {{ isset($msg->msg_cat) && $msg->msg_cat=="coupon"?'selected':"" }}>
                     Coupon
                     </option>
                     <option value="cart_abandon" {{ isset($msg->msg_cat) && $msg->msg_cat=="cart_abandon"?'selected':"" }}>
                     Cart Abandon
                     </option>
                     <option value="distributor" {{ isset($msg->msg_cat) && $msg->msg_cat=="distributor"?'selected':"" }}>
                     Distributor Request Mail
                     </option>
                     <option value="distributor" {{ isset($msg->msg_cat) && $msg->msg_cat=="forget"?'selected':"" }}>
                     Forget Password
                     </option>
                     <option value="Property_add" {{ isset($msg->msg_cat) && $msg->msg_cat=="Property_add"?'selected':"" }}>
                     Property add
                     </option>
                     <option value="Property_approved" {{ isset($msg->msg_cat) && $msg->msg_cat=="Property_approved"?'selected':"" }}>
                     Property approved
                     </option>
                     <option value="Booking_confirmation" {{ isset($msg->msg_cat) && $msg->msg_cat=="Booking_confirmation"?'selected':"" }}>
                     Booking confirmation
                     </option>
                     <option value="Booking_approved" {{ isset($msg->msg_cat) && $msg->msg_cat=="Booking_approved"?'selected':"" }}>
                     Booking approved
                     </option>
                     <option value="Booking_cancelation" {{ isset($msg->msg_cat) && $msg->msg_cat=="
                     Booking_cancelation"?'selected':"" }}>
                     Booking cancelation
                     </option>
                     <option value="Booking_changes" {{ isset($msg->msg_cat) && $msg->msg_cat=="Booking_changes"?'selected':"" }}>
                     Booking changes
                     </option>
                      <option value="booking_success" {{ isset($msg->msg_cat) && $msg->msg_cat=="booking_success"
                     ?'selected':"" }}>Booking Success</option> 

                     <option value="booking_enquiry" {{ isset($msg->msg_cat) && $msg->msg_cat=="booking_enquiry"
                     ?'selected':"" }}>Booking Enquiry</option> 

                     <option value="booking_journey_started" {{ isset($msg->msg_cat) && $msg->msg_cat=="booking_journey_started"
                     ?'selected':"" }}>Booking Journey Started</option> 

                     <option value="rating" {{ isset($msg->msg_cat) && $msg->msg_cat=="rating"
                     ?'selected':"" }}>Rating</option> 
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">Message On<span class=" text-danger">*</span></label>
                  <select name="status" class="form-control" id="msgype" required="">
                  <option value="{{ isset($msg->status)?$msg->status:"" }}">{{ isset($msg->status)?$msg->status:"" }}</option>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">From Email</label>
                  <input type="text" id="firstName" class="form-control" placeholder="From Email Id" name="fromemail" value="{{ isset($msg->from_email)?$msg->from_email:"" }}" required="">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">Reply From Email<span class=" text-danger">*</span></label>
                  <input type="text" id="firstName" class="form-control" placeholder="Reply From Email Id" name="replyemail" value="{{ isset($msg->reply_email)?$msg->reply_email:"" }}">
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <label class="control-label mb-10 text-primary">Replace Message content with this one to make dynamic<span class=" text-danger">*</span></label>
                  <div class="replace_msg" style="color:black;font-weight:bold;"></div>
               </div>
            </div>
         </div>
         <!--/span-->
      </div>
      <div class="seprator-block"></div>
      <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-comment-text mr-10"></i>Message</h6>
      <hr class="light-grey-hr" />
      <div class="row">
         <div class="col-md-12">
            <div class="panel panel-default card-view">
               <div class="panel-wrapper ">
                  <div class="panel-body">
                     @csrf
                     <!-- <textarea name="message" class="editor1" style="height:400px;">
                        {{ isset($msg->message)?$msg->message:"" }}
                        </textarea> -->
                     <!--  <textarea name="editor1" class="editor1" >  </textarea> -->
                     <textarea name="content" id="editor11" rows="10" cols="80" >
                          {{ isset($msg->message)?$msg->message:"" }}
              </textarea>
                  </div>
               </div>
            </div>
         </div>
      </div>



      <div class="form-actions">
         <hr>
         <br>
         <button class="btn btn-success btn-icon left-icon mr-10 pull-left btn-sm"> <i class="fa fa-check"></i> <span>Save & Update</span></button>
         <div class="clearfix"></div>
      </div>
      </form>
   </div>




 <!--  <table>
      <thead>
        <th><img data-cke-saved-src="http://officeshare-cms.ewtlive.in/images/mail-tempheader1.png" src="http://officeshare-cms.ewtlive.in/images/mail-tempheader1.png" style="/* height: auto; */width:100%;"></th>
      </thead>
      <tbody style="position: relative; left: 12%; text-align: left;bottom: 127px;">
      <tr >
        <td>
           <div class="mail-heading" style="font-family:Helvetica; position: absolute;"><h1 style="font-size: 25px;font-weight: 400;font-family:Helvetica;">Thank you for joining&nbsp;<span class="mail_head" style="color: #fc6565; font-size:25px;font-weight: 400; font-family:Helvetica;">OfficeShare</span>!</h1></div>
        </td>
      </tr>
    </tbody>
  </table>
 -->









</div>
</div>

<style type="text/css">
   @media (min-width: 320px) and (max-width: 767px) {
   .section-1{
   width: 100%!important;
   }
   .section-2{
   width: 100%!important;
   }
   .booking-info{
   display: block!important;
   }
   .parts{
   display: block!important;
   }
   .sub-str{
   display: block!important;
   }
   button.mail-btn{
   height: auto!important;
   width: auto!important;
   }
   .star{
    display: block!important;
   }
   }
</style>
<style type="text/css">
   @media (min-width: 320px) and (max-width: 767px) {
   .btns{
   display: block!important;
   }
   }
</style>
@push('ajax-script')
<!-- <script type="text/javascript">CKEDITOR.replace('post_content', {
   allowedContent:true,
   });
   CKEDITOR.replace( 'editor1', {
    filebrowserBrowseUrl: '/browser/browse.php',
    filebrowserUploadUrl: '/uploader/upload.php'
   });
   </script> -->
<script>
   CKEDITOR.replace('editor11', {
       filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
       filebrowserUploadMethod: 'form', 
       height: '500px',
   }).config.allowedContent = true;
</script>
<script type="text/javascript">
   $(document).ready(function() {
     $(document).on('change','#msgCatg',function() {
   });
   });
</script> --}}
<script type="text/javascript">
   $(document).on('change', '.msgCatg', function() {
     var signup = {
       signup: 'Message on Signup',
       password_reset: 'Message on Reset Password'
     };
     var contact = {
       contact_us: 'Message on Contact Us',
     };
     var distributor = {
       distributor: 'Message on Distributor Request',
     };
     var Property_add ={
       Property_add: 'Message on New Property Add Request',
     }
       var Property_approved ={
       Property_approved: 'Message on Property approved ',
     }
       var Booking_confirmation ={
       Booking_confirmation: 'Message on Booking confirmation',
     }
       var Booking_approved ={
       Booking_approved: 'Message on Booking approved',
     }
       var Booking_cancelation ={
       Booking_cancelation: 'Message on Booking cancelation',
     }
     var Booking_changes ={
       Booking_changes: 'Message on Booking changes',
     }
     var order = {
       placed: 'Message on Order Placed',
       packed: 'Message on Order Packed',
       shipped: 'Message on Order Shipped',
       delivered: 'Message on Order Delivered',
       cancelled: 'Message on Order Cancelled',
       out_for_delivery: 'Message on Order Out for delivery',
       out_for_reach: 'Message on Order Out for reach',
       return: 'Message on Order Order Return Request',
       refunded: 'Message on Order Refunded'
     };
     var gift = {
       gift: 'Message on gift card sending'
     }
     var coupon = {
       coupon: 'Message on coupon code sending'
     }
     var cart_abandon = {
       cart_abandon: 'Message on cart abandon'
     }

     var booking_enquiry = {
       booking_enquiry: 'Message on Booking Enquiry'
     }
     var value = $(this).val();
     if (value != "" && value == "sign up") {
       addTrigger(signup)
     } else if (value != "" && value == "order") {
       addTrigger(order)
     } else if (value != "" && value == "contact") {
       addTrigger(contact)
     } else if (value != "" && value == "distributor") {
       addTrigger(distributor)
     } else if (value != "" && value == "gift") {
       addTrigger(gift)
     } else if (value != "" && value == "cart_abandon") {
       addTrigger(cart_abandon)
     } else if (value != "" && value == "coupon") {
       addTrigger(coupon)
     }else if (value != "" && value == "Property_add") {
       addTrigger(Property_add)
     }else if (value != "" && value == "Property_approved") {
       addTrigger(Property_approved)
     }else if (value != "" && value == "Booking_confirmation") {
       addTrigger(Booking_confirmation)
     }else if (value != "" && value == "Booking_approved") {
       addTrigger(Booking_approved)
     }else if (value != "" && value == "Booking_cancelation") {
       addTrigger(Booking_cancelation)
     }else if (value != "" && value == "Booking_changes") {
       addTrigger(Booking_changes)
     }
     function addTrigger(arg) {
       let opt = "";
       $.each(arg, function(key, val) {
         opt += '<option value="' + key + '">' + val + '</option>'
       });
       $("#msgype").html(opt)
     }
   });
</script>
// For trigger
<script type="text/javascript">
   $(document).on('change', '.msgCatg', function() {
   
     var signup = ['{name}',
       '{email}',
       '{password}',
       '{site_url}',
       '{business_name}',
       '{logo}'
     ];
   
     var contact = ['{name}',
       '{contact_subject}',
       '{order_number}',
       '{site_url}',
       '{helpline_number}',
       '{business_name}',
       '{logo}'
     ];
   
     var distributor = ['{distributor_name}',
       '{distributor_country}',
       '{distributor_company}',
       '{distributor_company_type}',
       '{site_url}',
       '{helpline_number}',
       '{business_name}',
       '{logo}'
     ];
   
     var order = ['{user_name}',
       '{user_address}',
       '{user_phone}',
       '{user_email}',
       '{product_name}',
       '{order_number}',
       '{price}',
       '{discount}',
       '{total_saving}',
       '{quantity}',
       '{total_price}',
       '{gross_amount}',
       '{current_date}',
       '{grand_total}',
       '{billing_address}',
       '{shipping_address}',
       '{shipping_charge}',
       '{tax_type}',
       '{tax_amount}',
       '{site_url}',
       '{helpline_number}',
       '{business_name}',
       '{logo}'
     ];
     let gift = [
       '{sender_name}',
       '{recipient_name}',
       '{gift_code}',
       '{gift_expiry_date}',
       '{gift_card_image}',
       '{gift_title}',
       '{gift_sender_message}',
       '{gift_card_description}',
       '{gift_amount}',
       '{gift_quantity}',
       '{site_url}',
       '{business_logo}',
       '{business_name}'
     ];
     let coupon = [
       '{product}',
       '{user_name}',
       '{coupon_code}',
       '{coupon_description}',
       '{coupon_expiry_date}',
       '{product_category}',
       '{coupon_amount}',
       '{limit_per_user}',
       '{limit_per_coupon}',
       '{site_url}',
       '{business_logo}',
       '{business_name}'
     ];
     let cart_abandon = ['{user_name}',
       '{user_address}',
       '{user_phone}',
       '{user_email}',
       '{product_name}',
       '{price}',
       '{discount}',
       '{quantity}',
       '{total_price}',
       '{gross_amount}',
       '{grand_total}',
       '{current_date}',
       '{site_url}',
       '{helpline_number}',
       '{business_name}',
       '{business_logo}'
     ];
     var Property_add =[
       '{distributor_name}',
       '{distributor_country}',
       '{distributor_company}',
       '{distributor_company_type}',
       '{site_url}',
       '{helpline_number}',
       '{business_name}',
       '{logo}'
       ];
     var value = $(this).val();
   
     if (value != "" && value == "sign up") {
   
       addTrigger(signup)
   
     } else if (value != "" && value == "order") {
   
       addTrigger(order)
   
     } else if (value != "" && value == "contact") {
   
       addTrigger(contact)
   
     } else if (value != "" && value == "distributor") {
   
       addTrigger(distributor)
   
     } else if (value != "" && value == "gift") {
   
       addTrigger(gift)
   
     } else if (value != "" && value == "cart_abandon") {
   
       addTrigger(cart_abandon)
   
     } else if (value != "" && value == "coupon") {
   
       addTrigger(coupon)
   
     } else if (value != "" && value == "Property_add") {
   
       addTrigger(Property_add)
   
     }
   
   
   
     function addTrigger(arg) {
   
       let opt = "";
   
       $.each(arg, function(key, val) {
   
         opt += '<span class="text-dark">' + val + '</span>,&nbsp;&nbsp;'
   
       });
   
       $(".replace_msg").html(opt)
   
     }
   
   });
</script>
@endpush
@endsection