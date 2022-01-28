@extends('layouts.admin')
@section('title', $title)
@section('content')

<!-- /Row -->

    <h4>{{ $title }}</h4>


<div class="card">
  <div class="card-body" id="add_space">
    @can('attr_value_create')
    <form action="{{ route('dashboard.attribute-value.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
      @csrf
      @if(session('msg'))
      <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
      @endif
      <div class="row border-light">
        <div class="col-sm-6 form-group">
          <label>Attributes</label>
          <select class="form-control attr_id" name="attr_id" required>
           <!--  <option value="{{isset($EditAttr->attributeName['id'])?$EditAttr->attributeName['id']:''}}">
              {{isset($EditAttr->attributeName['name'])?$EditAttr->attributeName['name']:''}}
            </option> -->
            @if(isset($attribute))
            @foreach($attribute as $value)
            {{--<option>Select one</option>--}}
            <option value="{{$value->id}}">{{$value->name}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="col-sm-6 form-group ">
          <label>Attribute Value</label>
          <input type="text" name="name" value="{{isset($EditAttr->value)?$EditAttr->value:''}}" class="form-control" required>
          <input type="hidden" name="id" value="{{isset($EditAttr->id)?$EditAttr->id:''}}" class="form-control" required>
        </div>

        <div class="col-sm-6 form-group pt-1">
          <label>Attribute value icon</label>
          <input type="file" name="icon" class="form-control" required>
        </div>
        <div class="col-sm-6 form-group pt-1">
          <!-- <label>Attribute value active icon (Optional)</label>
          <input type="file" name="icon2" class="form-control"> -->
        </div>
        <!--<div class="col-md-6">-->
        <!-- <div class="form-group">-->
        <!-- <label class="control-label mb-10">Apply On Product Category <span class=" text-danger">*</span>-->
        <!-- <input type="checkbox" id="chk" > Select All-->
        <!-- </label>-->
        <!-- <select name="catry[]"  class="form-control select2 chkSel" multiple="">-->
        <!--     <option>{{ isset($product->category['name'])&& $product->category['name']=="No Parent" ? $product->category['name']:'' }}</option>-->
        <!--     @isset($categ)-->
        <!--     @foreach($categ as $item)-->
        <!--     <option value="{{ $item->id }}" >{{ Str::ucfirst($item->name)}}</option>-->
        <!--     @endforeach-->
        <!--     @endisset-->
        <!-- </select>-->
        <!-- </div>-->
        <!-- </div>-->
        <!--<div class="col-sm-6 form-group">-->
        <!--  <label>Attribute Value2 (To)Optional</label>-->
        <!--    <input type="text" name="name2" value="{{isset($EditAttr->attr_value2)?$EditAttr->attr_value2:''}}" class="form-control" >-->
        <!--</div>-->
        <div class="col-sm-4 form-group  pt-2">
          <button class="btn btn-primary btn-sm">Add & Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
    @endcan<br>
    @isset($attributeVal)
  <div class="row">
   <div class="col s12" id="s1">
      <div class="card">
         <div class="card-content">
            <div class="row">
               <div class="col s12">
                  <table  id="page-length-option" class="display">
        <thead>
          <tr>
            <th>ID</th>
            <th>Attributes</th>
            <th>Attributes Value</th>
            <th>Icon</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        {{-- <tbody class="c"> --}}

        <?php $i = 0; ?>
                            @foreach ($attributeVal as $item)
                                <tr id='{{ $item->id }}'>
        <td>{{ $item->id }}</td>
        <td>{{ Str::ucfirst($item->attributeName['name']) }}</td>
        <td>{{ Str::ucfirst($item->value) }}</td>
        <td>
          @if(isset( $item->icon))
          <img src="{{ url('') }}/{{ $item->icon }}" style="width:50px;height:50px;" alt="icon">
          @endif
        </td>
       
        <td>
          @if ($item->status == 1)
          <span class="chip green lighten-5"><span class="green-text">Active</span></span>
        </td>
        @else
        <span class="chip red lighten-5"><span class="red-text">{{$item->is_approved}}</span></span>
        @endisset
        <td>
          @can('attr_value_edit')
          <a class="btn btn-xs btn-info " href="{{ route('dashboard.attribute-value.edit',$item->id) }}">
            <i class="far fa-edit"></i>
          </a>
          @endcan
          @can('attr_value_delete')
          <a href="javascript:void(0)" class="btn btn-xs btn-danger delattrVal"><i class="fas fa-trash-alt"></i></a>
          @endcan
        </td>
        </tr>
        @endforeach 

        {{-- </tbody> --}}
        {{-- <tfoot>
                     <td> {{ $attributeVal->links() }}</td>
        </tfoot> --}}
      </table>
      @endisset
 </div>
            </div>
         </div>
      </div>
   </div>
</div>

@push('ajax-script')
<!-- Edit CAT -->
<script type="text/javascript">
  $(document).on('change', '#chk', function(event) {
    event.preventDefault();
    $(".chkSel option").attr('selected', '');
  });
</script>
<script type="text/javascript">
  $(".delattrVal").click(function(event) {
    var id = $(this).parents('tr').attr('id');
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
         confirmButton: 'conf_btn',
            cancelButton: 'cancel_btn'
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('dashboard/attribute-value') }}/" + id,
          type: 'DELETE',
          data: {
            id: id,
            _token: '{{csrf_token() }}'
          },
          success: function(data) {
            swalWithBootstrapButtons.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )

            $("#" + id).remove()
          }
        })
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Your imaginary file is safe :)',
          'error'
        )
      }
    })
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on('change', '.attr_id', function(event) {
      let v = $(".attr_id option:selected").text()
      $.get('{{url("dashboard/get-sub-attribute")}}/' + v, function(data) {
        let opt = "<option value=''></option>";
        $.each(data, function(index, val) {
          opt += "<option value='" + val.id + "'>" + val.name + "</option>";
        });
        $("#sub_attr_id").html(opt)
      });
    });
  });
</script>

<!-- for data search -->
<script>
  $(document).ready(function() {
    $("#InputCat").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".c tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
<script type="text/javascript">
  var attrtable;
  $(document).ready(function() {
    attrtable = $('#attribute').DataTable({
      "processing": true,
      "serverSide": true,
      "responsive": true,

      "ajax": {
        "url": "{{ url('dashboard/get-attribute-value-data') }}",
        "dataType": "json",
        "type": "POST",

        "data": {
          "order": [
            [3, "desc"]
          ],
          _token: "{{csrf_token()}}"
        }
      },
      "columns": [{
          "data": "id"
        },
        {
          "data": "attribute"
        },
        {
          "data": "value"
        },
        {
          "data": "icon"
        },
        {
          "data": "active_icon"
        },
        {
          "data": "status"
        },
        {
          "data": "options"
        }
      ]

    });
    $('#btn-filter').click(function() {
      attrtable.ajax.reload();
    });
    $('#btn-reset').click(function() {
      $('#form-filter')[0].reset();
      attrtable.ajax.reload();
    });
  });
</script>
@endpush
@endsection