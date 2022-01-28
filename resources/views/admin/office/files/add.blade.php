@extends('layouts.admin')
@section('content')
@section("styles")
<style type="text/css">
  .list-group>li {
    width: 19.4%;
    margin-left: 0px;
    background-color: #F46E43;
  }

  .list-group {
    width: 100%;
    height: 70px;
    margin-top: 20px;
    text-align: center;
  }

  .list-group>li>a {
    font-size: 18px;
    line-height: 70px;
    color: #fff;
  }

  .back {
    background-color: #000 !important;
  }

  select .select2 {
    width: 100% !important;
  }

  .desk {
    background-color: #ebebeb !important;
    padding: 5px;
    margin-top: 12px;
    margin-left: -8px;
    margin-right: -8px;
    box-shadow: 2px 3px 0px #c9c3c3;
  }
</style>
@endsection
<ul class="nav nav-pills list-group mb-3">
  <li class="active list-inline-item b back"><a data-toggle="pill" href="#basicTab">Basic Info</a></li>
  <li class="b"><a data-toggle="pill" href="#locationTab">Location</a></li>
  <li class="b"><a data-toggle="pill" href="#attributeTab">Attribute</a></li>
</ul>
<div class="card">
  <div class="card-body tab-content">
    <div id="basicTab" class="tab-pane fade in show {{ !isset($active_tab)  ? 'active' : '' }}">
      <form method="post" enctype="multipart/form-data" action="{{ route('dashboard.office.store') }}">
        @csrf
        <input type="hidden" name="tabid" value="tab1">
        <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-info-outline mr-10"></i>About office</h6>
        <hr class="light-grey-hr" />
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label mb-10">Office Name <span class=" text-danger">*</span></label>
              <input type="text" id="firstName" class="form-control" name="pname" value="{{ isset($product->pname)?$product->pname:'' }}" required>
              <input type="hidden" name="pid" value="{{ isset($product->id)?$product->id:'' }}" class="pid">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label mb-10">Cost<span class=" text-danger">*</span></label>
              <div class="input-group">
                <input type="number" class="form-control" id="p_price" name="p_price" value="{{ isset($product->p_price)?$product->p_price:'' }}" required>
              </div>
            </div>
          </div>

          <!--/span-->
         {{--<div class="col-md-6">
            <div class="form-group">
              <label>Total Desk</label>
              <select class="form-control tax" name="discount_type">
                <option value="">Select one</option>
                <option value="flat rate">Flat Rate</option>
                <option value="percentage">Percentage</option>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label mb-10">Discount </label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-cut"></i></div>
                <input type="number" class="form-control" id="exampleInputuname_1" name="discount" value="{{ isset($product->discount)?$product->discount:'0' }}">
              </div>
            </div>
          </div>--}}

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label mb-10">Offie Type<span class="text-danger">*</span></label>
              <select class="form-control" name="type" required>
                 <option value="Dedicated">Dedicated Office</option>  
                 <option value="Flexi">Flexi Desk</option>
                 <option value="Shared">Shared Space</option>
              </select>
            </div>
          </div>

          <!--/span-->
         {{-- <div class="col-md-6">
            <div class="form-group">
              <label class="control-label mb-10">Cancellation Hours <span class=" text-danger">*</span></label>
              <select class="form-control" name="return_policy" required>
                <option {{ isset($product)?$product->return_policy:'None' }}>{{ isset($product->return_policy)?$product->return_policy:'None' }}</option>
                @for($i=1; $i <=31 ; $i++) <option value="{{ $i }}">{{ $i." Days" }}</option>
                  @endfor
              </select>
            </div>
          </div>--}}
          <!--/span-->
         <div class="col-md-6">
            <div class="form-group">
              <label>Total Desk</label>
                <input type="number" id="totaldesk" class="form-control" name="total_desk" value="" required>
            </div>
          </div>
          <!--/span-->
          <div class="col-md-6 {{ isset($product->tax) && $product->tax=='excluded'?'':'appTax' }}">
            <div class="form-group ">
              <label class="control-label mb-10">Tax will apply <span class=" text-danger">*</span></label>
              <select class="form-control text-uppercase select2" name="tax_type[]" multiple="multiple" style="width: 100%">
                @if(isset($tax_type) && $tax_type!=null)
                @foreach($tax_type as $itm)
                <option value="{{$itm->id}}">{{ $itm->tax_type }}</option>
                @endforeach
                @else
                <option value="">Select Tax Type</option>
                @endif
              </select>
            </div>
          </div>
          <!--/row-->
          <hr>
          <div class="seprator-block"></div>
          <div class="col-sm-12">
            <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-comment-text mr-10"></i>Short Description</h6>
            <hr class="light-grey-hr" />
            <div class="form-group">
              <textarea class="form-control" rows="4" name="p_s_description">
              {{ isset($product->p_s_description)?$product->p_s_description:'' }}</textarea>
            </div>
          </div>
          <div class="col-md-12">
            <div class="seprator-block"></div>
            <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-comment-text mr-10">

              </i>Description in Details</h6>
            <hr class="light-grey-hr" />
            <textarea name="descript" class="editor1" style="height:400px !important;">
            {{ isset($product->p_description)?$product->p_description:'' }}</textarea>
          </div>

          {{-- <div class="col-md-12">
<div class="seprator-block"></div><br>
<h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-comment-text mr-10"></i>Features</h6>
<hr class="light-grey-hr"/>
<textarea  name="feature" class="editor1" style="height:400px !important;">
  {{ isset($product->feature)?$product->feature:'' }}</textarea>
        </div> --}}

        <hr>

        <div class="col-lg-12 ">
          <div class="seprator-block"></div><br>
          <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-collection-image mr-10">

            </i>Office Thumbnail <span class=" text-danger">*</span></h6>
          <hr class="light-grey-hr" />
          @if(isset($product->thumbnails))
          <div class="row">
            @foreach(json_decode($product->thumbnails) as $thumb)
            <div class="col-sm-2"> <img src="{{ url('/product/thumbnail') }}/{{ $thumb }}" style="height:130px;width:80%;">
            </div>
            @endforeach
          </div>
          <p>Upload new to override all previous thumbnail</p>
          @endif
          <div class="mt-40 fallback ">
            <input type="file" class="form-control" name="thumbnail" multiple id="thumbnail" />
          </div>
        </div>

        <hr class="light-grey-hr" />
        <div class="col-lg-12">
          <br>
          <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-collection-image mr-10">

            </i>Gallery Image <span class=" text-danger">*</span></h6>
          <hr class="light-grey-hr" />
          @if(isset($product->gallery_image))
          <div class="row">
            @foreach(json_decode($product->gallery_image) as $thumb)
            <div class="col-sm-2"> <img src="{{ url('/product/product-gallary') }}/{{ $thumb }}" style="height:130px;width:80%;">
            </div>
            @endforeach
          </div>
          <p>Upload new to override all previous gallery image</p>
          @endif

          <div class="mt-40 fallback "><br>
            <input type="file" class="form-control" name="gallery_image[]" multiple="" />
          </div>
        </div>
        <hr class="light-grey-hr" />

        {{-- <div class="col-md-12">
          <div class="form-group "><br>
            <label class="control-label mb-10">Recommended Office </label>
            <select class="form-control select2" name="matched[]" multiple="" style="width:100%">
              @isset($recomPro)
              @foreach($recomPro as $item)
              <option value="{{ $item->id }}">{{ $item->pname }}</option>
        @endforeach
        @endisset
        </select>
    </div>
  </div>
  <div class="col-sm-12">
    <h5>Manage Recommended Office</h5>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Office</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @isset($product->recommendedProduct)
        @foreach($product->recommendedProduct as $item)
        <tr id="{{ $item->id }}">
          <td>{{ $item->product['pname'] }}</td>
          <td>
            <button class="btn btn-xs btn-danger delRecom" type="button"><i class="fas fa-trash-alt"></i></button>
            <a href="http://eshakti.ewtlive.in/product-details/{{$item->product['id']}}" class="btn btn-xs btn-success" target="_blank"><i class="far fa-eye"></i></a>
          </td>
        </tr>
        @endforeach
        @endisset
      </tbody>
    </table>
  </div>--}}
  <hr class="light-grey-hr" /><br>
  <div class="form-actions col-sm-12 text-right">
    <button class="btn btn-primary btn-sm btn-icon left-icon mr-10 pull-left saveproduct" type="submit" name="basic_info"> <i class="fa fa-check"></i> <span>save & update</span></button>
  </div>
</div>
</form>
</div>
<div id="locationTab" class="tab-pane fade show {{ isset($active_tab) && ($active_tab == 'tab1')  ? 'active' : '' }}">
  @include("admin.office.files.location")
</div>
<div id="attributeTab" class="tab-pane fade {{ isset($active_tab) && ($active_tab == 'tab2')  ? 'active show' : '' }}">
  @include("admin.office.files.attribute")
</div>
</div>
@push('ajax-script')
{{-- @include('admin.product.style-custom-script') --}}
<script type="text/javascript">
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
  $(".delRecom").click(function(event) {
      var id=$(this).parents('tr').attr('id');
const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})
swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this item!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
     $.ajax({
      url:"{{ url('dashboard/remove-recommended-product') }}/"+id,
      type:'GET',
      success:function(data)
      { swalWithBootstrapButtons.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success' )
      $("#"+id).remove() 
      }
    })
   }else if(result.dismiss === Swal.DismissReason.cancel ) {
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
     )
   }
  }) 
 });
$(document).on('submit', '#getattrvalue', function(event) {
event.preventDefault();
$.ajax({
url:'{{route("dashboard.fetch-attribute-value")}}',
type:'POST',
data:new FormData(this),
contentType:false,
processData:false,
success:function(data){
  console.log(data)
let d="";
let opt='';
let i=0;
let v="";
 d +="<form method='post' action='{{ route('dashboard.office.store') }}'>"
 d +='@csrf'
 d +="<div class='row'>"
 d +=' <div class="col-sm-12"><br>'+ 
    '<h6>Add Attribute Value</h6>'+
    '<hr class="light-grey-hr"/><br>'+ 
  '</div>'
$.each(data, function(index, val) {
  v=val.name;
  d +="<div class='col-sm-6 form-group'>";
  d +="<input type='hidden' name='attrId"+index+"' value='"+val.id+"'>"
  d +="<label>"+val.name+"</label>";
  d +="<select class='form-control attv3' name='attribute"+index+"[]' multiple='' style='width:100%'>"
  $.each(val.attribute, function(index2, val2) {
     opt +="<option value='"+val2.id+"'>"+val2.name+"</option>";
  });
  d +=opt;
  d +="</select>";
  d +='<p> Use as a feature ';   
  d +='<input type="checkbox" name="ppage'+index+'" value="1">';   
  d +=' Use in variation ';   
  d +='<input type="checkbox" name="vari'+index+'" value="1"></p>';  
  d +="</div>";
  opt="";
  i +=1;
});
  d +="<div class='col-sm-12 form-group'>";
  d +="<button class='btn btn-primary btn-sm'>Save Attribute</button>"
  d +="<input type='hidden' name='tot' value='"+i+"' />" 
  d +="</div>";
  d +="</form></div>"      
//  d +="<div class='col-sm-12 form-group'>";
//  d +="<input type='hidden' name='tot' value='"+i+"' />"     
//  d +="<button class='btn btn-success btn-sm'>Save Attribute</button>";
//  d +="</div>";
 $(".appendAttr2").html(d)
 $(".attv3").select2();
}
});
});
</script>
<script type="text/javascript">
  // get dimension unit
  $(document).on('change', '#dim_unit', function(event) {
   $('#dim_replace').text('Dimension('+$('#dim_unit option:selected').text()+')')
  });  
    // get weight unit
  $(document).on('change', '#weight_unit', function(event) {
   $('#weight_replace').text('Weight('+$('#weight_unit option:selected').text()+')')
  });  
  // product variation settings
    function checkPrice(v,i){
      if(i==1){
      $(".vari"+v).show();
      console.log(i)
      }else{
      $(".vari"+v).hide();
       console.log(i)
      }
    }
  // add product variations
  function addVariation(e,val){
      e.preventDefault();
    $.ajax({
     url:'{{ url('dashboard/set-product-variation') }}',
     type:'POST',
     data:new FormData($("#addVariant"+val)[0]),
     contentType:false,
     processData:false,
     success:function(data){
      swal.fire('Saved',"product variation added");
      $("#addVariant"+val)[0].reset();
      if(data=="1"){
        $("#defaultState"+val).text("Default variation")
      }
      $("#removeCombo"+val).remove();
      $("#addVariant"+val).remove();
     }
   })
  }  
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#basic').click();
  });
        $(document).on('change', '#pc', function(event) {
            var pcat=$('#pc option:selected').text();
            $.get('{{ url('dashboard/get-category') }}/'+pcat, function(data) {
                let opt="";
                let sel="";
                let opt2="";
                let tot=0;
                 //for sub category
                $.each(data.d, function(index, val){
                opt +="<option value='"+val.id+"'>"+val.name+"</option>"
                }); 
                $.each(data.attr, function(index, val){
                  opt2 +="<option value='"+val.id+"'>"+val.name+"</option>"
                });
                $("#getAttr").html(opt2)
              //   //for attribute value 
              // $.each(data.attr, function(index, val){
              //   tot +=1;  
              //   sel +="<div class='col-sm-4'>";
              //   sel +="<label>"+val.name+"</label>";
              //   sel +="<input type='hidden' value='"+val.id+"' name='attrId"+index+"'>";
              //   sel +="<select class='form-control attval2' name='attribute"+index+"[]' multiple>";
              //   $.each(val.value, function(index2, val2) {
              //      opt2 +="<option value='"+val2.id+"'>"+val2.atrr_value+"</option>"
              //   });
              //   sel +=opt2;  
              //   sel +='<p>';   
              //   sel +='<input type="checkbox" name="ppage'+index+'" value="1">'; 
              //   sel +='Use on product page ' ; 
              //   sel +='<input type="checkbox" name="vari'+index+'" value="1">'; 
              //   sel +=' Use in variation</p>';   
              //   sel +="</select></div>";    
              //   opt2="";
              //   });
              //   sel +="<input type='hidden' value='"+tot+"' name='tot'>"; 
              //   $(".attri").html(sel);  
                $("#subc").html(opt)
                $(".attval2").select2();
            });
        });
  $('.appTax').hide()
  $(document).on('change', '.tax', function(event) {
  $(this).val()=="excluded"?$('.appTax').show():$('.appTax').hide()
 });
</script>



<script type="text/javascript">





function removeStyle(id){
const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})
swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this item!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed){
         $.get('{{ url("dashboard/remove-variant") }}/'+id, function(data){
          swal.fire('Removed',"style removed!");
          $("#"+id).remove();  
        });
   }else if(result.dismiss === Swal.DismissReason.cancel ) {
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
     )
   }
  }); 

  }
  // remove product attribute
  function removeProductAttribute(e,id) {
 // alert("hgj")
    const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})
swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this item!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed){
         $.get('{{ url("dashboard/remove-product-attribute") }}/'+id, function(data){
          swal.fire('Removed',"Attribute removed!");
          $("#"+id).remove();  
        });
   }else if(result.dismiss === Swal.DismissReason.cancel){
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
     )
   }
  }); 
  }
  // get attribute value 
  function getAttributeValue(id){
    $.get('{{ url("dashboard/get-product-attribute-value") }}/'+id, function(data) {
      let opt="";
      let d=$.parseJSON(data);
      $.each(d, function(index, val){
        opt +="<option value='"+val.id+"'>"+val.atrr_value+"</option>"
      });
      $("#attributeValue_"+id).html(opt)
    });
  }

  // update attribute value
  function updateAttributeValue(e,id) {
   e.preventDefault();
   $.ajax({
     url: '{{ url("dashboard/update-product-attribute-value") }}',
     type: 'POST',
     data: new FormData($("#editAttribute_"+id)[0]),
     contentType:false,
     processData:false,
     success:function(data){
      swal.fire('Saved',"data updated ");
     }
   })  
  }
  // remove product attribute value
  function deleteAttrVal(id) {
    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed){
           $.get('{{ url("dashboard/remove-product-attribute-value") }}/'+id, function(data){
            swal.fire('Removed',"Attribute removed!");
            $("#"+id).remove();  
          });
     }else if(result.dismiss === Swal.DismissReason.cancel){
      swalWithBootstrapButtons.fire(
        'Cancelled',
        'Your imaginary file is safe :)',
        'error'
       )
     }
    }); 
  }
  function updateVariation(e,v) {
     e.preventDefault();
       $.ajax({
        url: '{{ url('dashboard/update-product-variant') }}',
        type: 'POST',
        data:new FormData($("#updateVariant"+v)[0]) ,
        contentType:false,
        processData:false,
        success:function(data){
          swal.fire('Saved',"variation data updated!"); 
          $("#updateVariant"+v)[0].reset();
        }
    });
  }
  function addVariant(e,v) {
       e.preventDefault();
       $.ajax({
        url: '{{ url('dashboard/add-product-variant') }}',
        type: 'POST',
        data:new FormData($("#addVariant"+v)[0]) ,
        contentType:false,
        processData:false,
        success:function(data){
          swal.fire('Saved',"variation data saved!"); 
          $("#addVariant"+v)[0].reset();
        }
    });
  }
  function reGenerate(id) {
   const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert already made variation combination!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, make new',
    cancelButtonText: 'No, cancel!',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed){
           $.get('{{ url("dashboard/variation-regenerate") }}/'+id, function(data){
            location.reload();
          });
     }else if(result.dismiss === Swal.DismissReason.cancel){
      swalWithBootstrapButtons.fire(
        'Cancelled',
        'Your imaginary file is safe :)',
        'error'
       )
     }
    }); 
  }
  $(document).on('click', '.b', function(event){
  let id=$(this).attr('id');
  $(".b").removeClass('back')
  $(this).addClass('back active')
});

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

$(document).on('click', '.applydesk', function(event){
    let id=$('#deskapply').val();
    console.log(id)
    let len=id.length;
    let add='<form class="form-group" id="adddesk" method="post" action="{{ route('dashboard.office.store') }}">';
    add+='@csrf';
    for(var i =1; i <= len; i++){
    let caption=id[(i-1)].split('_');
   add+='<div class="row desk">'+
   '<div class="col-sm-8">'+caption[0]+ 
   '</div>'+
   '<div class="col-sm-4 text-right">'+
   '<a data-toggle="collapse" href="#colp_'+i+'"><i class="fas fa-align-justify"></i></a>'+
   '</div>'+
   '</div>' +    
    '<div class="collapse show" id="colp_'+i+'">'+
  '<div class="row  p-2">'+
  '<div class="col-sm-12">'+
    '<br>'+
   '<h6>Desk Information</h6>'+  
   '<hr class="light-grey-hr"/><br>'+
  '</div>'+
  '<div class="col-sm-4 pt-1">'+
   '<label>Desk Cost</label>'+
   '<input type="hidden" name="ddesk" value="'+id+'" class="form-control">'+
   '<input type="number" name="desk_cost_'+i+'" class="form-control">'+
   '<input type="hidden" name="desk_id_'+i+'" class="form-control" value="'+caption[1]+'">'+    
  '</div>'+
  '<div class="col-sm-4">'+
   '<label>Discount Type</label>'+
   '<select class="form-control" name="discount_type_'+i+'">'+
   '<option value="">Select one</option>'+
   '<option value="flat rate">Flat Rate</option>'+
   '<option value="percentage">Percentage</option>'+ 
   '</select>'+
  '</div>'+
  '<div class="col-sm-4 pt-1">'+
   '<label>Desk Discount</label>'+
   '<input type="number" name="desk_discount_'+i+'" class="form-control">'+  
  '</div>'+
  '<div class="col-sm-4">'+
   '<label>Desk Image</label>'+
   '<input type="file" name="desk_file_'+i+'[]" class="form-control" multiple="">' + 
  '</div>'+
  '<div class="col-sm-4">'+
   '<label>Number of desk</label>'+
   '<input type="number" name="noofdesk_'+i+'" class="form-control" id="noofdesk_'+i+'">'+ 
   '<input type="hidden" name="count_'+i+'" class="form-control" id="counter_'+i+'">'+
  '</div>'+
  '<div class="col-sm-4 text-right pt-4">'+
  '<button class="btn btn-sm btn-primary" type="button" onclick="createDesk('+i+')">Create Desk</button>'+
  '</div>'+
    '</div>'+
    '<div class="row p-2">'+
      '<div class="col-sm-12"><h6>Provide desk number</h6></div>'+
    '</div>'+
    '<div id="addoption_'+i+'" class="row p-2"></div>'
    '</div></div>';
    }
    add +='<input type="hidden" name="parent_counter" value="'+len+'">';
    add +='<div class="row">'+
   '<div class="col-sm-12">'+
   '<button class="btn btn-primary btn-sm">Save & Update</button>'+  
  '</div>'+   
  '</div>'+
  '</form>';
    $('.adddesktype').html(add);
   });       
  
function createDesk(arg){
   let count=$("#noofdesk_"+arg).val(); 
   let opt=''; 
   for(var i =1; i <=count; i++) {
     opt +='<div class="col-sm-3">'+
        '<label>Desk '+i+'.</label>'+
        '<input type="text" name="'+arg+'_desk_number_'+i+'" class="form-control">'+
      '</div>'
   }
   $('#counter_'+arg).val(count);
   $('#addoption_'+arg).html(opt)
}    
</script>

@endpush
@endsection
