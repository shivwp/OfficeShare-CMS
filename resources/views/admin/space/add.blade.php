@extends('layouts.admin')
@section('content')

        <h4>
            {{ $title }}
        </h4>

<!-- @if($errors->any())
  <h4>{{$errors->first()}}</h4>
@endif -->

<div class="card">
    <div class="card-body" id="add_space">
        <form action="{{ route('dashboard.space.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
             <input type="hidden" value="{{isset($space->id)?$space->id:''}}" name="id">
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="name">Select Property</label>
                <select name="property_id"  class="form-control" required>
                    @foreach($property as $id => $item)
                    <option value="{{$item->id}}" {{isset($space->property_id) && ($space->property_id == $item->id)? 'selected':''}}>{{ $item->property_title }}</option>
                     @endforeach
                </select>
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Select Space Type</label>
                  <select name="space_type_id"  class="form-control" required>
                    @foreach($space_type as $id => $item)
                    <option value="{{$item->id}}" {{isset($space->property_type_id) && ($space->property_type_id == $item->id)? 'selected':''}}>{{ $item->title }}</option>
                     @endforeach
                </select>
            </div>
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="name">Space Title</label>
                <input type="text" class="form-control" name="title" value="{{isset($space->space_title)?$space->space_title:''}}" required>
            </div>
             <div class="form-group {{$errors->has('key_feature')?'has-error' : '' }}">
               <label for="email">Key Features</label>
                <textarea style="height:300px !important;" class="form-control" name="key_feature" required>{{isset($space->key_feature)?$space->key_feature:''}}</textarea>
            </div>
          
            <div class="form-group {{ $errors->has('desk') ? 'has-error' : '' }}">
                <label for="name">Total Desk</label>
                <input type="number" class="form-control" name="desk" value="{{isset($space->total_desk)?$space->total_desk:''}}" required>
            </div>
            <div class="form-group {{ $errors->has('price_type') ? 'has-error' : '' }}">
              <label for="name">Price Type</label>
              <select name="price_type" class="form-control cost_type"  required>
                  <option  value="">SELECT</option>
                  <option value="single" {{isset($space->cost_type) && ($space->cost_type == "single") ? 'selected' : ''}}>Single Price</option>
                  <option value="range" {{isset($space->cost_type) && ($space->cost_type == "range") ? 'selected' : ''}}>Price Range</option>
              </select>

            </div>
            <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}" id="cost" style="display: none">
                <label for="name">Cost per desk</label>
                <input type="number" class="form-control" name="cost" value="{{isset($space->cost)?$space->cost:''}}">
            </div>
            <div class="form-group {{ $errors->has('range') ? 'has-error' : '' }}" id="range" style="display: none">
                <label for="name">Price Range Per Desk</label>
                <div class="row">
                    <div class="col-md-3 ">
                      <p class="input-range">Monday</p>
                      <p class="input-range">Tuesday</p>
                      <p class="input-range">Wednesday</p>
                      <p class="input-range">Thursday</p>
                      <p class="input-range">Friday</p>
                      <p class="input-range">Saturday</p>
                      <p class="input-range">Sunday</p>
                    </div>
                    <div class="col-md-3">
                      <input type="number" name="Mon" value="{{isset($mon->price)?$mon->price:''}}" id="mon">
                      <input type="number" name="Tue" value="{{isset($tue->price)?$tue->price:''}}" id="tue">
                      <input type="number" name="Wed" value="{{isset($wed->price)?$wed->price:''}}" id="wed">
                      <input type="number" name="Thu" value="{{isset($thu->price)?$thu->price:''}}" id="thu">
                      <input type="number" name="Fri" value="{{isset($fri->price)?$fri->price:''}}" id="fri">
                      <input type="number" name="Sat" value="{{isset($sat->price)?$sat->price:''}}" id="sat">
                      <input type="number" name="Sun" value="{{isset($sun->price)?$sun->price:''}}" id="sun">
                    </div>
                  
                </div>
            </div>
           <div class="form-group {{ $errors->has('availability_type') ? 'has-error' : '' }}">
              <label for="name">Availability type</label>
              <select name="availability_type" class="form-control" required>
                <option value="">--select--</option>
                <option value="1" {{isset($space->availability_type) && ($space->availability_type == 1) ? "selected" : ""}}>Day</option>
                <option value="2" {{isset($space->availability_type) && ($space->availability_type == 2) ? "selected" : ""}}>Night</option>
                <option value="3" {{isset($space->availability_type) && ($space->availability_type == 3) ? "selected" : ""}}>Day & Night</option>
              </select>
           </div>
           <div class="form-group {{ $errors->has('availability_type') ? 'has-error' : '' }}" id="availability-type">
                <label for="name">Select availability space</label>
                <div class="row mb-3">
                    <div class="col-md-4"></div>
                    <div class="col-md-5"></div>
                  <div class="col-md-3">
                    <!-- <button type="button" class="btn submit-btn "  id="somebutton1" ><i class="fas fa-trash-alt"></i></button> -->
                    <button type="button" class="btn submit-btn " style="float:right; font-size: 29px; margin-top: -35px; height: 35px; line-height: 23px; width: 35px; text-align: center;"  id="somebutton" >+</button>
                  </div>
                </div>
                @if(!empty($availabilityDesk))
                  @php
                  $i = 1
                  @endphp
                  @foreach($availabilityDesk as $abc)
                    @if($i ==1)
                    <?php $inew=""; ?>
                    @else
                    <?php $inew = $i; ?>
                    @endif
                   
                    <div class="row rowdelet row-<?php echo $i; ?>">
                      <div class="col-md-1">
                        <p>
                          <label>
                            <input name="availability_space[<?php echo $i; ?>][group1]" type="radio" class="hide-default-radio chk" value="date" onclick='showmoreonclick(".date-range{{$inew}}1",".date-range{{$inew}}2",".date-range{{$inew}}3",this)'  {{isset($abc->type) && ($abc->type == "date") ? 'checked' : ''}} />
                            <span>Date</span>
                          </label>
                        </p>
                      </div>
                      <div class="col-md-1">
                        <label>
                          <input name="availability_space[<?php echo $i; ?>][group1]" onclick='showmoreonclick(".date-range{{$inew}}1",".date-range{{$inew}}2",".date-range{{$inew}}3",this)' type="radio" class="hide-default-radio chk" value="range" {{isset($abc->type) && ($abc->type == "range") ? 'checked' : ''}} />
                          <span>Range</span>
                        </label> 
                      </div>
                      <div class="col-md-6 date-range{{$inew}}1"   style="{{isset($abc->type) && ($abc->type == 'range') ? 'display: none;' : 'display: block;'}}">
                        <label for='name'>Date</label>
                        <input type='date' class='form-control' name='availability_space[{{$i}}][single_date]' value="{{isset($abc->type) && ($abc->type == 'date') ? $abc->to_date : ''}}" >
                      </div>
                      <div class="col-md-6 date-range{{$inew}}2"style="{{isset($abc->type) && ($abc->type == 'range') ? 'display: block;' : 'display: none;'}}">
                          <div class="row">
                              <div class="col-md-6" style="padding: 0;">
                                <label for='name'>from</label>
                                <input type='date' class='form-control' name='availability_space[{{$i}}][from_date]' value="{{isset($abc->type) && ($abc->type == 'range') ? $abc->from_date : ''}}" >
                              </div>
                              <div class="col-md-6">
                                <label for='name'>to</label>
                                <input type='date' class='form-control' name='availability_space[{{$i}}][to_date]' value="{{isset($abc->type) && ($abc->type == 'range') ? $abc->to_date : ''}}" >
                              </div>
                          </div>
                      </div>
                      <div class="col-md-2 date-range{{$inew}}3" >
                          <label for='name'>Desk available</label>
                          <input type='number' class='form-control' name='availability_space[{{$i}}][available_desk]' value="{{isset($abc->available_desk) ? $abc->available_desk : ''}}" >
                      </div>
                      <div class="col-md-2">
                       <!--   <button type="button" class="btn submit-btn "  id="somebutton1" >-</button> -->
                       <button type="button" class="btn submit-btn " onclick="removesec('<?php echo $i; ?>')"  id="somebutton1" ><i class="fas fa-trash-alt"></i></button>
                      </div>
                    </div>
                    @php
                      $i++
                    @endphp
                  @endforeach
                @else
                <div class="row rowdelet row-1">
                  <div class="col-md-1">
                    <p>
                      <label>
                        <input name="availability_space[1][group1]" type="radio" class="hide-default-radio chk" value="date" onclick='showmoreonclick(".date-range1",".date-range2",".date-range3",this)' checked />
                        <span>Date</span>
                      </label>
                    </p>
                  </div>
                  <div class="col-md-1">
                    <label>
                      <input name="availability_space[1][group1]" onclick='showmoreonclick(".date-range1",".date-range2",".date-range3",this)' type="radio" class="hide-default-radio chk" value="range" />
                      <span>Range</span>
                    </label> 
                  </div>
                  <div class="col-md-6 date-range1" style="display: none;">
                    <label for='name'>Date</label>
                    <input type='date' class='form-control' name='availability_space[1][single_date]' value='' >
                  </div>
                  <div class="col-md-6 date-range2" style="display: none;">
                      <div class="row">
                          <div class="col-md-6" style="padding: 0;">
                            <label for='name'>from</label>
                            <input type='date' class='form-control' name='availability_space[1][from_date]' value='' >
                          </div>
                           <div class="col-md-6">
                            <label for='name'>to</label>
                            <input type='date' class='form-control' name='availability_space[1][to_date]' value='' >
                          </div>
                      </div>
                  </div>
                   <div class="col-md-3 date-range3" style="display: none;">
                       <label for='name'>Desk available</label>
                      <input type='number' class='form-control' name='availability_space[1][available_desk]' value='' >
                   </div>
                    <div class="col-md-1">
                       <!--  <button type="button" class="btn submit-btn "  id="somebutton1" >-</button> -->
                        
                    </div>
                </div>
                @endif
           </div>
          
            <div class="form-group {{ $errors->has('thumb') ? 'has-error' : '' }}">
               <label for="name">Featured Image</label>
                @if(isset($space->thumb))
                <img src="{{isset($space->thumb) ? url('media/thumbnail/'.$space->thumb) : ''}}" style="height:100px;width: 100px;" class="remove-old-img">
                @endif
                <input type="file" class="form-control" name="thumb" id="files" class="form-control" {{!empty($space->thumb) ? '':'required'}}/>
                 <div class="files_insert" style="float: left;"></div>
            </div>
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="name">Select Refund Option</label>
                 <select name="refund" id="refund" class="form-control" required>
                   @if(!empty($refund))
                    @foreach($refund as $data)
                      <option value="{{$data->id}}" {{isset($space->booking_payment_refund) && ($space->booking_payment_refund == $data->id) ? 'selected':''}}>{{$data->title}}</option>
                   @endforeach
                  @endif
                </select>
              </div>
             {{--<div class="form-group {{ $errors->has('gallery_image') ? 'has-error' : '' }}">
               <label for="name">Gallery Image</label>
                @if(isset($space->gallary_image))
                @foreach(json_decode($space->gallary_image) as $data)
                <img src="{{isset($space->thumb) ? url('media/gallery/'.$data) : ''}}" style="height:100px;width: 100px;">
                @endforeach
                @endif
                <input type="file" class="form-control" name="gallery_image[]" multiple="" required/>
            </div>--}}
             <div class="form-group {{ $errors->has('min_term') ? 'has-error' : '' }}">
                <label for="name">Min Term (Days)</label>
                <input type="number" class="form-control" name="min_term" value="{{isset($SpaceExtraDetails->min_term)?$SpaceExtraDetails->min_term:''}}" required>
            </div>
             <div class="form-group {{ $errors->has('max_term') ? 'has-error' : '' }}">
                <label for="name">Max Term (Days)</label>
                <input type="number" class="form-control" name="max_term" value="{{isset($SpaceExtraDetails->max_term)?$SpaceExtraDetails->max_term:''}}" required>
            </div>
            <div class="form-group {{ $errors->has('things_not_included') ? 'has-error' : '' }}">
                <label for="name">Things Not Included </label>
                <textarea class="form-control" name="things_not_included" value="" required>{{isset($SpaceExtraDetails->things_not_included)?$SpaceExtraDetails->things_not_included:''}}</textarea>
            </div>
          
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
<style type="text/css">
  input#searchInput {
    margin-top: -7px;
    padding-top: 0px;
    border: none;
    width: 100%;
    padding: 5px 10px;
    box-sizing: border-box;
    border: 1px solid #9e9e9e;
}
#somebutton1{
  margin: 15px 20px;
}
input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
  position: relative;
}
.remove {
 /* display: block;*/
  background: #444;
 /* border: 1px solid black;*/
  color: white;
  /*text-align: center;*/
  cursor: pointer;
   position: absolute;
    right: 3px;
    top: 3px;
}
.remove1 {
    background: #444;
    /* border: 1px solid black; */
    color: white;
    /* text-align: center; */
    cursor: pointer;
    position: absolute;
    right: 3px;
    top: 3px;
}
.pip1 {
  display: inline-block;
  margin: 10px 10px 0 0;
  position: relative;
}

.remove:hover {
  background: white;
  color: black;
}

p.input-range {
    margin: 10px;
    font-size: 20px;
    padding-bottom: 14px;
}
input#mon {
    padding-top: 20px;
}
input#tue {
    padding-top: 20px;
}
input#wed {
    padding-top: 20px;
}
input#thu {
    padding-top: 20px;
}
input#fri {
    padding-top: 20px;
}
input#sat {
    padding-top: 20px;
}
input#sun {
    padding-top: 20px;
}
.alert.alert-danger {
    background-color: #f55145;
    color: #ffffff;
    margin: 10px;
    padding-top: 9px;
    padding-bottom: 1px;
    text-align: center;
}


@media screen and (max-width: 1440px) {
  button#somebutton {
    margin-left: 92px !important;
  }
}
@media screen and (max-width: 1024px) {
  button#somebutton {
    margin-left: 59px !important;
  }
}


}

</style>
@push('ajax-script')
<script type="text/javascript">

  $(document).on('change','.country', function(event) {

  var id=$(this).val();

  $.get('{{ url("dashboard/get-state") }}/'+id, function(data) {

  var d=$.parseJSON(data);

  var option="<option value=''>Select State</option>";

  $.each(d, function(index, val) {

  option +='<option value="'+val.id+'">'+val.name+'</option>'

  });

  $(".state").html(option)

  });

  });

    $(document).on('change','.state', function(event) {

    var id=$(this).val();

    $.get('{{ url("dashboard/get-city") }}/'+id, function(data) {

    var d=$.parseJSON(data);

    var option="<option value=''>Select City</option>";

    $.each(d, function(index, val) {

    option +='<option value="'+val.id+'">'+val.name+'</option>'

    });

    $(".city").html(option)

    });



    });

</script>
<script type="text/javascript">
  $(document).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
      $("#files").on("change", function(e) {
         $('.remove-old-img').remove(); 
        var files = e.target.files,
          filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();
          fileReader.onload = (function(e) {
            var file = e.target;
              $('.files_insert').html("<span class=\"pip1\">" +
              "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
              "<i class=\"fas fa-window-close remove1\"></i>"+
              "</span>").insertBefore("#files")
            $(".remove1").click(function(){
              $(this).parent(".pip1").remove();
              $('#files').val(""); 

            });
            
          });
          fileReader.readAsDataURL(f);
        }
      });
    } else {
      alert("Your browser doesn't support to File API")
    }
  });
</script>
<script type="text/javascript">
$(document).ready(function(){
  $(".cost_type").change(function(){
    var check = $(this).val();
      if(check == 'single'){
        $("#cost").show();
        $("#range").hide();
         $("#cost").append("<input type ='hidden' name='single_cost' value='asdfdsa' >");
      }
      else{
          if(check == 'range'){
            $("#cost").hide();
            $("#range").show();
            $("#range").append("<input type ='hidden' name='cost_range' value='asdfdsa'>");
          }
          else{
             $("#cost").hide();
              $("#range").hide();
          }
      }
   
  });

   var check1 = $(".cost_type").val();
    if(check1 == 'single'){
      $("#cost").show();
      $("#range").hide();
      $("#cost").append("<input type ='hidden' name='single_cost' value='asdfdsa'>");
    }
    else{
        if(check1 == 'range'){
          $("#cost").hide();
          $("#range").show();
          $("#range").append("<input type ='hidden' name='cost_range' value='asdfdsa'>");
        }
        else{
           $("#cost").hide();
            $("#range").hide();
        }
    }
  
});
</script>
<script type="text/javascript">
   function showmoreonclick(class1,class2,class3,thisv){
  

      // var selected = $('.hide-default-radio').val();
     //var selected = $('input[type="radio"]:checked');
     var selected = thisv.value;
      if(selected == 'date'){
        $(class2).hide();
        $(class1).show();
        $(class3).show();
      }
      else if(selected == 'range'){
          $(class1).hide();
          $(class2).show();
          $(class3).show();
      }
  
  }
  function removesec(classs){
	 $("#availability-type .row-"+classs).remove(); 
  }
  $(document).ready(function(){

    <?php if(empty($availabilityDesk)): ?>

        $(".date-range2").hide();
        $(".date-range1").show();
        $(".date-range3").show();
    <?php endif; ?>

    var selectedVal = "";


  
    //var i = 2;
    $("#somebutton").click(function () {
      var i = $('#availability-type .rowdelet').length;
      i++;
      var class1 = ".date-range"+i+"1";
      var class2 = ".date-range"+i+"2";
      var class3 = ".date-range"+i+"3";
      var html = '<div class="row rowdelet row-'+i+'"><div class="col-md-1"><p>'+
                      '<label>'+
                        '<input name="availability_space['+i+'][group1]" type="radio" class="hide-default-radio chk" value="date" onclick="showmoreonclick(\''+class1+'\', \''+class2+'\', \''+class3+'\', this)"  />'+
                        '<span>Date</span>'+
                      '</label>'+
                    '</p>'+
                  '</div>'+
                  '<div class="col-md-1">'+
                    '<label>'+
                      '<input name="availability_space['+i+'][group1]" type="radio" class="hide-default-radio chk" value="range" onclick="showmoreonclick(\''+class1+'\', \''+class2+'\', \''+class3+'\', this)" />'+
                      '<span>Range</span>'+
                    '</label>'+ 
                  '</div>'+
                  '<div class="col-md-6 date-range'+i+'1" style="display: none;">'+
                    '<label for="name">Date</label>'+
                    '<input type="date" class="form-control" name="availability_space['+i+'][single_date]" value="" >'+
                  '</div>'+
                  '<div class="col-md-6 date-range'+i+'2" style="display: none;">'+
                      '<div class="row">'+
                          '<div class="col-md-6" style="padding: 0;">'+
                            '<label for="name">from</label>'+
                            '<input type="date" class="form-control" name="availability_space['+i+'][from_date]" value="" >'+
                          '</div>'+
                           '<div class="col-md-6">'+
                            '<label for="name">to</label>'+
                            '<input type="date" class="form-control" name="availability_space['+i+'][to_date]" value="" >'+
                          '</div>'+
                      '</div>'+
                  '</div>'+
                   '<div class="col-md-3 date-range'+i+'3" style="display: none;">'+
                       '<label for="name">No of desk available</label>'+
                      '<input type="number" class="form-control" name="availability_space['+i+'][available_desk]" value="" >'+
                   '</div>'+
                   '<div class="col-md-1">'+
                   '<button type="button" class="btn submit-btn " onclick="removesec('+i+')"  id="somebutton1"  ><i class="fas fa-trash-alt"></i></button>'+
                   '</div>'+
                '</div>';
      $("#availability-type").append(html);
     // i++;
      });
      $("#somebutton1").click(function () {
        var deltval = $('#availability-type .rowdelet').length;
       // alert(deltval);
       if(deltval >1){
        $("#availability-type .row-"+deltval).remove();
       }
      });
   
   



  });
</script>
 
@endpush
@endsection


 