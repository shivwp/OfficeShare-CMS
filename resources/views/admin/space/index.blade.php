@extends('layouts.admin')
@section('title',$title)
@section('content')

<!-- /Row -->

    <div class="row">
      <div class="col-sm-6 pt-1">
        <h4>
          {{ $title }}
        </h4>
      </div>
      <div class="col-sm-6 text-right ">
        @can('office_create')
        <a class="addnew-btn" href="{{ route('dashboard.space.create') }}">
          {{ trans('global.add') }} Space
        </a>
        @endcan
      </div>
    </div>

@if(session()->has('msg'))
    <p class="alert alert-success text-center">{{ session()->get('msg') }}</p>
@endif

<div class="row">
    <div class="col s12" id="s1">
      <div class="card">
        <div class="card-content">
          <div class="row">
            <div class="col s12">
              <table  id="page-length-option" class="display">
                 <form action="{{route('dashboard.space.index')}}" method="get">
                      @csrf
                      <div class="search_bar">  
                           <input type="" class="form-controller" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Sapce search"></input>
                          <button type="submit" class="serch_btn">Search</button>
                      </div>
                      <div class="search_bar ml-2">
                          <select class="form-control approvefilter" name="filter">
                            <option value="0">-Filter by status-</option>
                            <option value="pending" {{ (request()->get('filter') == 'pending') ? 'selected' : '' }}>Pending</option>
                            <option value="publish" {{ (request()->get('filter') == 'publish') ? 'selected' : '' }}>Publish</option>
                            <option value="rejected" {{ (request()->get('filter') == 'rejected') ? 'selected' : '' }}>Rejected</option>
                          </select>
                      </div>
                       <div class="search_bar">  
                       <select class="form-control property-filter" name="property_id">
                          <option value="">-Filter by property-</option>
                          @foreach($properties as $val)
                            <option value="{{$val->id}}" {{ (request()->get('property_id') == $val->id) ? 'selected' : '' }}>{{$val->property_title}}</option>
                          @endforeach
                        </select>
                      </div>
                  </form>
                <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Space Title</th>
              <th>Property</th>
              <th>Type</th>
              <th>Total Desk</th>
              <th>Landload</th> 
              <th>Status</th> 
              <th>Action</th>
            </tr>
          </thead>
          <tbody class="c">
            @if(!empty($space))
            <?php $i = 0; ?>
            @foreach($space as $item)

            @php $j=1 @endphp
            <tr id='{{ $item->id }}'>
              <td>{{ $item->id }}</td>
              
                <td>
                @if(!empty($item->thumb))
                <img src="{{url('/media')}}/thumbnail/{{$item->thumb}}" alt="" class="thumb-img">
               @endif
              </td>
               <td>{{ Str::ucfirst($item->space_title) }}</td>

              <td><a href="{{ route('dashboard.office.edit',$item->property_id) }}">{{ Str::ucfirst($item->property) }}</a></td>
              <td>{{ Str::ucfirst($item->space_type) }}</td>
              <td>{{ Str::ucfirst($item->total_desk) }}</td>
              <td>{{ Str::ucfirst($item->user) }}</td>

               <td>
                @if($item->is_approved == 'rejected')
                 <span class="chip red lighten-5"><span class="red-text">{{$item->is_approved}}</span></span>
                @elseif($item->is_approved == 'pending')
                 <span class="chip cyan lighten-5"><span class="cyan-text">{{$item->is_approved}}</span></span>
                @else
                 <span class="chip green lighten-5">
                      <span class="green-text">{{$item->is_approved}}</span>
                </span>
                @endif
               
              </td>
              <td>
                 <a class="btn btn-xs btn-info" href="{{ route('dashboard.space.edit',$item->id) }}">
                 <i class="far fa-edit"></i>
                </a>
                 @can('permission_create')
                    @if($item->is_approved=='pending')
                    <form action="{{ route('dashboard.change-space-stauts') }}" method="post" id="changepublishstatus">
                        @csrf
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <button class="btn btn-danger changepublishstatus" title="publish" name="approve" value="publish"><i class="material-icons">check_circle</i></button>
                    </form>
                     <form action="{{ route('dashboard.change-space-stauts') }}" method="post" id="changepublishstatus">
                        @csrf
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <button class="btn btn-danger changepublishstatus" title="reject" name="reject" value="rejected"><i class="material-icons">do_not_disturb</i></button>
                    </form>
                    @endif
                     <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a>
                      @if($item->booking_approval== '0')
                       <form action="{{ route('dashboard.change-space-stauts') }}" method="post" id="changepublishstatus">
                        @csrf
                          <input type="hidden" name="id" value="{{$item->id}}">
                          <button class="btn btn-danger changepublishstatus" title="make space enquiry type" name="enquiry" value="rejected"><i class="material-icons">assignment_late</i></button>
                       </form>
                      @endif
                @endcan

              </td>
            </tr>
            @endforeach

            @else
            <td colspan="5">No Office</td>
            @endif
          </tbody>
        </table>
         <div class="pages">
              {!! $space->links() !!}
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
         
    
    <div id="addCat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color:pink">
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
   <style>
      input#search {
        margin-top: 9px;
        margin-left: 10px;
      }
      p.alert.alert-success.text-center {
    background: #fc6565;
    padding-bottom: 8px;
    padding-top: 8px;
    font-weight: 800;
}

    </style>
    @push('ajax-script')
    <script type="text/javascript">
  $(".delblog").click(function(event) {

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

          url: "{{ url('dashboard/space') }}/" + id,

          type: 'DELETE',

          data: {

            id: id,

            _token: '{{ csrf_token() }}'

          },

          success: function(data)

          {
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
    <!-- Edit CAT -->
    <script type="text/javascript">
      $(".delp").click(function(event) {
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
          text: "You won't be able to revert this item!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ url('dashboard/product') }}/" + id,
              type: 'DELETE',
              data: {
                id: id,
                _token: '{{ csrf_token() }}'
              },
              success: function(data) {
                swalWithBootstrapButtons.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success')
                $("#" + id).remove()
              }
            })
          } else if (result.dismiss === Swal.DismissReason.cancel) {
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
        $.get('{{ url(' / admin / categories ') }}/' + id + '/edit', function(data) {
          var d = $.parseJSON(data);
          alert(d.name)
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

    <script type="text/javascript">
      var usertable;
      $(document).ready(function() {
        usertable = $('#posts').DataTable({
          "processing": true,
          "serverSide": true,
          "responsive": true,
          "ajax": {
            "url": "",
            "dataType": "json",
            "type": "POST",
            "data": {
              _token: "{{csrf_token()}}"
            }
          },
          "columns": [{
              "data": "id"
            },
            //{ "data": "userid" },
            {
              "data": "name"
            },
            {
              "data": "image"
            },
            //{ "data": "apiorder" },
            //{ "data": "created_at" },
            //{ "data": "updated_at" },
            {
              "data": "options"
            }
          ]

        });
        $('#btn-filter').click(function() {
          usertable.ajax.reload();
        });
        $('#btn-reset').click(function() {
          $('#form-filter')[0].reset();
          usertable.ajax.reload();
        });
      });
    </script>

    @endpush
    @endsection