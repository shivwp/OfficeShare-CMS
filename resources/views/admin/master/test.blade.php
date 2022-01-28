*@extends('layouts.admin')

@section('content')

<div class="container">
  <div class="row">
      <div class="col-md-3">
        <img src="{{url('media/01.png')}}" width="100%">
    </div>
     <div class="col-md-3">
          <img src="{{url('media/02.png')}}" width="100%">
    </div>
     <div class="col-md-3">
           <img src="{{url('media/03.png')}}" width="100%">
    </div>
     <div class="col-md-3">
           <img src="{{url('media/04.png')}}" width="100%">
    </div>
  </div>

</div>

<div class="container">
    <div class="row"> 

         <div class="col-md-3">
             <img src="{{url('media/01.png')}}" width="100%">
         </div>

          <div class="col-md-9">
         </div>



    </div>


</div>



@push('ajax-script')
<script type="text/javascript">
  $(document).on('change', '#hour_type', function(event) {
    if ($(this).val() == "24 X 7 Opened") {
      $('.hideShow').hide();
    } else if ($(this).val() == "Weekly Opened") {
      $('.hideShow').show();
    }
  });
</script>

@endpush
@endsection