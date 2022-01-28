@extends('layouts.admin')

@section('title',$title)

@section('content')



<!-- /Row -->

<div class="card">

  <div class="card-header ">



    <div class="row">

      <div class="col-sm-6">

        <h4 class="card-title">

          Product Categories

        </h4>

      </div>

      <div class="col-sm-6 text-right">

        @can('category_create')

        <div style="margin-bottom: 10px;" class="row">

          <div class="col-lg-12">

            <a class="btn btn-success" href="javascript:void(0)" data-target="#addCat" data-toggle="modal">

              {{ trans('global.add') }} Category

            </a>

          </div>

        </div>

        @endcan

      </div>

    </div>

  </div>



  <div class="card-body">

    <div class="table-responsive">

      <table class=" table table-striped table-hover datatable datatable-User" id="example">

        <thead>

          <tr>

            <th>#</th>

            <th>Categories</th>

            <th>Parent Categories</th>

            <th>Status</th>

            <th>Action</th>

          </tr>

        </thead>

        <tbody class="c">

          @isset($cat)

          <?php $i = 0; ?>

          @foreach($cat as $item)

          <tr id='{{ $item->id }}'>

            <td>{{ $item->id }}</td>

            <td>{{ Str::ucfirst($item->name) }}</td>

            <td>{{ Str::ucfirst($item->cid ) }}</td>

            <td>

              @if($item->status==1)

              <a class="btn btn-success btn-xs  btn-rounded" href="{{url('dashboard/change-category-status')}}/{{$item->id}}">Active</a>
            </td>

            @else

            <a class="btn btn-danger btn-xs  btn-rounded" href="{{url('dashboard/change-category-status')}}/{{$item->id}}">De-active</a></td>

            @endif

            <td>



              @can('category_edit')

              <a class="btn btn-xs btn-info edit" href="javascript:void(0)">

                <i class="far fa-edit"></i>

              </a>

              @endcan

              @can('category_delete')

              <a href="javascript:void(0)" class="btn btn-xs btn-danger delc"><i class="fas fa-trash-alt"></i></a>

              @endcan

            </td>

          </tr>

          @endforeach

          @endisset

        </tbody>

      </table>

    </div>

  </div>

</div>



<div id="addCat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header" style="background-color:#32D3A1">



        <h5 class="modal-title">Add & Update Category</h5>

      </div>

      <form id="addcat" method="post">

        @csrf

        <div class="modal-body">



          <div class="form-group">

            <label for="recipient-name" class="control-label mb-10">Category name</label>

            <input type="text" class="form-control name" id="recipient-name " name="name" value="">

            <input type="hidden" name="id" id="id" value="">

          </div>

          <div class="form-group">

            <label for="message-text" class="control-label mb-10">Parent Category</label>

            <select name="pname" id="pc" class="form-control">

              <option selected="" id="parent">No Parent</option>

              @if(!$categ->isEmpty())

              @foreach($categ as $item)

              <option>{{ Str::ucfirst($item->name) }}</option>

              @endforeach

              @endif

            </select>

          </div>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default reload" data-dismiss="modal">Close</button>

          <button type="submit" class="btn btn-danger">+ Add & Update</button>

        </div>

      </form>

    </div>

  </div>

</div>

@push('ajax-script')

<script type="text/javascript">
  $("#addcat").submit(function(event) {

    event.preventDefault();

    $.ajax({

      url: '{{ route("dashboard.categories.store") }}',

      type: 'POST',

      data: new FormData(this),

      contentType: false,

      processData: false,

      success: function(data)

      {

        if (data != 'You have already added this category')

        {

          var d = $.parseJSON(data);

          var sts = '';

          sts = '<button class="btn btn-success btn-xs st btn-rounded " id="1">Active</button>';

          var action = '<button class="btn btn-info btn-xs edit btn-rounded"><i class="far fa-edit"></i></button><button class="btn btn-danger btn-xs del btn-rounded">  <i class="fas fa-trash-alt"></i></button>';

          var row =

            '<tr id=' + d.id + '><td></td><td>' + d.name + '</td><td>' + d.cid + '</td><td>' + sts + '</td><td>' + action + '</td></tr>';

          $(".c").prepend(row);

          $("#pc").append('<option>' + d.name + '</option>');

          $("#addcat")[0].reset();

          swal("Added");

        } else {

          swal("Sorry!! ", data);

        }

      }

    })

  });
</script>

<!-- Edit CAT -->

<script type="text/javascript">
  $(".delc").click(function(event) {

    var id = $(this).parents('tr').attr('id');

    const swalWithBootstrapButtons = Swal.mixin({

      customClass: {

        confirmButton: 'btn btn-success',

        cancelButton: 'btn btn-danger'

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



        $.get('destroy-category/' + id, function(data) {



          swalWithBootstrapButtons.fire(

            'Deleted!',

            'Your file has been deleted.',

            'success'

          )



          $("#" + id).remove()



        });







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
  $(".edit").click(function(event) {

    var id = $(this).parents('tr').attr('id');

    $.get('{{ url(' / dashboard / categories ') }}/' + id + '/edit', function(data) {

      var d = $.parseJSON(data);

      //alert(d.name)

      $('.name').attr('value', d.name);

      $('#id').attr('value', d.id);

      $('#parent').text(d.cid)

      $("#addCat").modal('show')



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

@endpush

@endsection