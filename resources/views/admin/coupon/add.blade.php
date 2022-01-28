@extends('layouts.admin')
@section('title', $title)
@section('content')
<!-- /Row -->
<form action="{{ route('dashboard.coupon.store') }}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="card">
    <div class="card-header">
      <div style="margin-bottom: 10px;" class="row">
        <div class="col-sm-6 h6"> {{ $title }}</div>
        <div class="col-sm-6 h6 text-center">
          <h6>Coupon Data</h6>
        </div>
        <div class="col-sm-6 bg-light">
          <div class="row p-2 ">
            <div class="col-sm-11 form-group">
              <label>Coupon Code</label>
              <input type="text" name="coupon" class="form-control" id="coupon" required="" value="{{ isset($edcoupon->code)?$edcoupon->code:'' }}">
              <a class="badge badge-success p-1 text-white" id="generateId">
                Generate Coupon Code</a>
            </div>
            <div class="col-sm-11 form-group">
              <label>Description</label>
              <textarea class="form-control" name="description" rows="3" placeholder="Description(Optional)">
              {{ isset($edcoupon->description)?$edcoupon->description:'' }}
              </textarea>
              <input type="hidden" name="id" value=" {{ isset($edcoupon->id)?$edcoupon->id:'' }}">
            </div>
          </div>
        </div>
        <div class="col-sm-6 bg-light">
          <div class="container couponTab">
            <ul class="nav nav-tabs text-white">
              <li class="active"><a data-toggle="tab" href="#general" class="active">
                  <i class="fas fa-dna"></i>&nbsp;&nbsp; General</a></li>
              <li><a data-toggle="tab" href="#usage_rest"><i class="fas fa-ban"></i>&nbsp;&nbsp;Usage Restriction</a></li>
              <li><a data-toggle="tab" href="#usage_limit">
                  <i class="fas fa-window-maximize"></i>&nbsp;&nbsp; Usage Limits</a></li>
            </ul>
            <div class="tab-content">
              <div id="general" class="tab-pane fade in active show">
                <div class="row p-2 pt-4">
                  <div class="col-sm-5 pt-4">
                    <label>Discount Type</label>
                  </div>
                  <div class="col-sm-7">
                    <select class="form-control" name="type">
                      <option value="fp">Fixed price</option>
                      <option value="p">Percentage</option>
                    </select>
                  </div>
                </div>
                <div class="row p-2 pt-4">
                  <div class="col-sm-5 pt-4">
                    <label>Coupon Amount <a href="#" data-toggle="popover" data-placement="bottom" data-content="Value of the coupon."><i class="fas fa-question-circle"></i></a></label>
                  </div>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" name="coupon_amount" value="{{ isset($edcoupon->coupon_amount)?$edcoupon->coupon_amount:'' }}">
                  </div>
                </div>
                <div class="row p-2 ">
                  <div class="col-sm-5 ">
                    <label>Allow Free Shipping </label>
                  </div>
                  <div class="col-sm-7 ">
                    <input type="checkbox" name="free_shipping" value="1" {{isset($edcoupon->allow_free_shipping) && $edcoupon->allow_free_shipping==1?
        'checked="checked"':'' }}>
                  </div>
                </div>
                <div class="row p-2 pt-4">
                  <div class="col-sm-5 pt-4">
                    <label>Start Date <a href="#" data-toggle="popover" data-placement="bottom" data-content="The coupon will start at 00:00:00 of this date."><i class="fas fa-question-circle"></i></a></label>
                  </div>
                  <div class="col-sm-7">
                    <input type="date" class="form-control" name="start_date" value="{{ isset($edcoupon->start_date)?\Carbon\Carbon::parse($edcoupon->start_date)->format('Y-m-d'):'' }}">
                  </div>
                </div>
                <div class="row p-2 pt-4">
                  <div class="col-sm-5 pt-4">
                    <label>Expiry Date <a href="#" data-toggle="popover" data-placement="bottom" data-content="The coupon will expire at 00:00:00 of this date."><i class="fas fa-question-circle"></i></a></label>
                  </div>
                  <div class="col-sm-7">
                    <input type="date" class="form-control" name="expiry_date" value="{{ isset($edcoupon->expiry_date)?\Carbon\Carbon::parse($edcoupon->expiry_date)->format('Y-m-d'):'' }}">
                  </div>
                </div>
              </div>
              {{-- usages restriction --}}
              <div id="usage_rest" class="tab-pane fade">
                <div class="row p-2 pt-4">
                  <div class="col-sm-6 pt-4">
                    <label>Minimum Spend <a href="#" data-toggle="popover" data-placement="bottom" data-content="This field allows you to set the minimum spend (subtotal) allowed to use the coupon."><i class="fas fa-question-circle"></i></a></label>
                  </div>
                  <div class="col-sm-6">
                    <input type="number" name="minimum_spend" class="form-control" value="{{ isset($edcoupon->minimum_spend)?$edcoupon->minimum_spend:'' }}">
                  </div>
                </div>
                <div class="row p-2">
                  <div class="col-sm-6 pt-4">
                    <label>Maximum Spend <a href="#" data-toggle="popover" data-placement="bottom" data-content="This field allows you to set the maximum spend (subtotal) allowed when using the coupon."><i class="fas fa-question-circle"></i></a></label>
                  </div>
                  <div class="col-sm-6">
                    <input type="number" name="maximum_spend" class="form-control" value="{{ isset($edcoupon->maximum_spend)?$edcoupon->maximum_spend:'' }}">
                  </div>
                </div>
                <div class="row p-2">
                  <div class="col-sm-6 pt-4">
                    <label>Inivisual Use Only</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="checkbox" name="indivisual" value="1" {{isset($edcoupon->is_indivisual) && $edcoupon->is_indivisual==1?
        'checked="checked"':'' }}>Check this box if the coupon cannot be used in conjunction with other coupons.
                  </div>
                </div>
                <div class="row p-2">
                  <div class="col-sm-6 pt-4">
                    <label>Exclude Sale Items </label>
                  </div>
                  <div class="col-sm-6">
                    <input type="checkbox" name="exclude_sale_item" value="1" {{isset($edcoupon->exclude_sale_item) && $edcoupon->exclude_sale_item==1?
        'checked="checked"':'' }}>Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.
                  </div>
                </div>
                <hr>
                {{-- <div class="row p-2">
        <div class="col-sm-6 pt-4">
          <label>Minimum cart value </label>
        </div>
          <div class="col-sm-6">
           <input type="number" name="minimum_cart_value" class="form-control"
           value="{{ isset($edcoupon->minimum_cart_value)?$edcoupon->minimum_cart_value:''}}">
              </div>
            </div>
            <div class="row p-2">
              <div class="col-sm-6 pt-4">
                <label>Maximum cart value </label>
              </div>
              <div class="col-sm-6">
                <input type="number" name="maximum_cart_value" class="form-control" value="{{ isset($edcoupon->maximum_cart_value)?$edcoupon->maximum_cart_value:''}}">
              </div>
            </div> --}}
            <hr>
            <div class="row p-2">
              <div class="col-sm-6 ">
                <label>Products <a href="#" data-toggle="popover" data-placement="bottom" data-content="Products that the coupon will be applied to, or that need to be in the cart in order for the 'Fixed cart discount' to be applied."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <select class="select2 form-control" name="products[]" style="width:100%;" multiple="">
                  @php $i=0; @endphp
                  @if(isset($products))
                  @foreach($products as $item)
                  @if(isset($inc_products) && isset($inc_products[$i]) &&
                  $inc_products[$i]['pname']==$item->pname)
                  <option value="{{ $item->id }}" selected="">{{ $item->pname }}</option>
                  @php $i++ @endphp
                  @else
                  <option value="{{ $item->id }}">{{ $item->pname }}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
                <input type="hidden" name="pid" value="{{ isset($pid)?$pid:'' }}">
              </div>
            </div>
            <div class="row p-2">
              <div class="col-sm-6 ">
                <label>Exclude Products <a href="#" data-toggle="popover" data-placement="bottom" data-content="Products that the coupon will not be applied to, or that cannot be in the cart in order for the 'Fixed cart discount' to be applied."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <select class="select2 form-control" name="exclude_products[]" style="width:100%;" multiple="">
                  @php $i=0; @endphp
                  @if(isset($products))
                  @foreach($products as $item)
                  @if(isset($exc_products) && isset($exc_products[$i]) &&
                  $exc_products[$i]['pname']==$item->pname)
                  <option value="{{ $item->id }}" selected="">{{ $item->pname }}</option>
                  @php $i++ @endphp
                  @else
                  <option value="{{ $item->id }}">{{ $item->pname }}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
            <hr>
            <div class="row p-2">
              <div class="col-sm-6">
                <label>Product categories <a href="#" data-toggle="popover" data-placement="bottom" data-content="Product categories that the coupon will be applied to, or that need to be in the cart in order for the 'Fixed cart discount' to be applied."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <select class="select2 form-control" name="product_categories[]" style="width:100%;" multiple="">
                  @php $i=0; @endphp
                  @if(isset($categories))
                  @foreach($categories as $item)
                  @if(isset($inc_prod_cats) && isset($inc_prod_cats[$i]) &&
                  $inc_prod_cats[$i]['name']==$item->name)
                  <option value="{{ $item->id }}" selected="">{{ $item->name }}</option>
                  @php $i++ @endphp
                  @else
                  <option value="{{ $item->id }}">{{ $item->name }}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
                <input type="hidden" name="cid" value="{{ isset($cid)?$cid:'' }}">
              </div>
            </div>
            <div class="row p-2">
              <div class="col-sm-6 ">
                <label>Exclude categories <a href="#" data-toggle="popover" data-placement="bottom" data-content="Product categories that the coupon will not be applied to, or that cannot be in the cart in order for the 'Fixed cart discount' to be applied."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <select class="select2 form-control" name="exclude_categories[]" style="width:100%;" multiple="">
                  @php $i=0; @endphp
                  @if(isset($categories))
                  @foreach($categories as $item)
                  @if(isset($exc_prod_cats) && isset($exc_prod_cats[$i]) &&
                  $exc_prod_cats[$i]['name']==$item->name)
                  <option value="{{ $item->id }}" selected="">{{ $item->name }}</option>
                  @php $i++ @endphp
                  @else
                  <option value="{{ $item->id }}">{{ $item->name }}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
            <hr>
            <div class="row p-2">
              <div class="col-sm-6 ">
                <label>Allowed Specific Email <a href="#" data-toggle="popover" data-placement="bottom" data-content="List of allowed billing emails to check against when an order is placed."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <select class="select2 form-control" name="allowed_email[]" style="width:100%;" multiple="">
                  @php $i=0 @endphp
                  @if(isset($users))
                  @foreach($users as $item)
                  @if(isset($cup_users) && isset($cup_users[$i]) &&
                  $cup_users[$i]==$item->email)
                  <option value="{{ $item->email }}" selected="">{{ $item->email }}</option>
                  @php $i++ @endphp
                  @else
                  <option value="{{ $item->email }}">{{ $item->email }}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
                <input type="hidden" name="uid" value="{{ isset($uid)?$uid:'' }}">
              </div>
            </div>
          </div>
          <div id="usage_limit" class="tab-pane fade">
            <div class="row p-2">
              <div class="col-sm-6 pt-4">
                <label>Usage limit per coupon <a href="#" data-toggle="popover" data-placement="bottom" data-content="How many times this coupon can be used before it is void."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <input type="number" name="usage_limit_per_coupon" class="form-control" value="{{ isset($edcoupon->limit_per_coupon)?$edcoupon->limit_per_coupon:'' }}">
              </div>
            </div>
            <div class="row p-2">
              <div class="col-sm-6 pt-4">
                <label>Usage limit per user <a href="#" data-toggle="popover" data-placement="bottom" data-content="How many times this coupon can be used by an individual user. Uses billing email for guests, and user ID for logged in users."><i class="fas fa-question-circle"></i></a></label>
              </div>
              <div class="col-sm-6">
                <input type="number" name="usage_limit_per_user" class="form-control" value="{{ isset($edcoupon->limit_per_user)?$edcoupon->limit_per_user:'' }}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <hr>
      <button class="btn btn-primary btn-sm">Save & Update</button>
    </div>
  </div>
  </div>
  </div>
</form>
@push('ajax-script')
<!-- Edit CAT -->
<script type="text/javascript">
  $(document).on('click', '#generateId', function(event) {
    function makeid(length) {
      var result = [];
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
        result.push(characters.charAt(Math.floor(Math.random() *
          charactersLength)));
      }
      return result.join('');
    }
    $("#coupon").val(makeid(8));
  });
</script>

<script>
  $(document).ready(function() {
    $('[data-toggle="popover"]').popover();
  });
</script>
@endpush

@endsection