
@extends('layouts.admin')
@section('title', $title)
@section('content')
    <!-- /Row -->
<form action="{{ route('admin.giftcard.store') }}" method="post" enctype="multipart/form-data">
@csrf
<div class="card">
<div class="card-header">
<div style="margin-bottom: 10px;" class="row">
<div class="col-sm-6 h6">   {{ $title }}</div>
<div class="col-sm-12 bg-light">
     <div class="col-sm-11 form-group">
      <label>Gift Title</label>
      <input type="text" name="title" class="form-control" id="title" required=""
      value="{{ isset($edgiftcard->title)?$edgiftcard->title:'' }}">
      <input type="hidden" name="id" value=" {{ isset($edgiftcard->id)?$edgiftcard->id:'' }}">
    </div>
    <div class="col-sm-11 form-group">
      <label>Gift card image  @if(isset($edgiftcard->image))<img src="{{ url('') }}/{{ $edgiftcard->image }}" style="height:80px;width:150px;">@endif </label>
     <input type="file" name="image" class="form-control">
    </div>
    <div class="col-sm-11 form-group">
      <label>Gift card Fixed Amount <span style="font-size:12px;">(give multiple amount by seperating with comma)</span></label>
      <input type="text" name="amount" class="form-control" value="{{ isset($edgiftcard->amount)?$edgiftcard->amount:'' }}" placeholder="Ex. 200,300">
    </div>
    <div class="col-sm-11 form-group">
      <label>Description</label>
      <textarea class="form-control" name="description" rows="3" placeholder="Description(Optional)">
        {{ isset($edgiftcard->description)?$edgiftcard->description:'' }}
      </textarea> 
    </div>
    <div class="col-sm-11 form-group">
      <label>Valid Days</label>
      <select class="form-control select2" name="valid_days" required="">
        <option value="">Select One</option>
        @for($i=1;$i<=365;$i++)
         @if(isset($edgiftcard->valid_days) && $edgiftcard->valid_days==$i)
          <option selected="">{{ $i }}</option>
          @else
          <option>{{ $i }}</option>
         @endif
        @endfor
      </select>
    </div> 
</div>
<div class="col-sm-12">
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
     var result           = [];
     var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
     var charactersLength = characters.length;
     for ( var i = 0; i < length; i++ ) {
      result.push(characters.charAt(Math.floor(Math.random() * 
       charactersLength)));
    }
    return result.join('');
    }
     $("#coupon").val(makeid(8));
    });

</script>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>
@endpush

@endsection

