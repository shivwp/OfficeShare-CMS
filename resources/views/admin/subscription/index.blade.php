@extends('layouts.admin')

@section('title',$title)
@section('content')
<!-- /Row -->
<div class="card">
  <div class="card-header ">
    <div class="row">
      <div class="col-sm-6">
        <h4 class="card-title">
          {{ $title }}
        </h4>
      </div>
      <div class="col-sm-6 text-right">
        @can('page_create')
        <div style="margin-bottom: 10px;" class="row">
          <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('dashboard.membership.create') }}">
              {{ trans('global.add') }} Membership
            </a>
          </div>
        </div>
        @endcan
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class=" table table-striped table-hover datatable datatable-User " id="example">
        <thead>
          <tr>
            <th>#Sr.</th>
            <th>Membership Name</th>
            <th>Price</th>
            <th>Plateform Charge</th>
            <th>Membership Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="c">

          @isset($mem)
          <?php $i = 1; ?>
          @foreach($mem as $item)
          <tr id='{{ $item->id }}'>
            <td>{{ $i++ }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->price}}</td>
            <td>{{ $item->plateform_charges }}</td>
            <td>{{ $item->subscribstion_type }}</td>
            <td>
              @if($item->status)
              <a href="{{ url('dashboard/change-subscription-status/'.$item->id.'/0') }}" class="badge badge-success p-1">Active</a>
              @else
              <a href="{{ url('dashboard/change-subscription-status/'.$item->id.'/1') }}" class="badge badge-danger p-1">Inactive</a>
              @endif
            </td>
            <td>
              @can('membership_edit')
              <a class="btn btn-xs btn-info" href="{{ route('dashboard.membership.edit',$item->id) }}">
                <i class="far fa-edit"></i>
              </a>
              @endcan
              @can('membership_delete')
              <a href="javascript:void(0)" class="btn btn-xs btn-danger delsubs"><i class="fas fa-trash-alt"></i></a>

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

@push('ajax-script')
<script type="text/javascript">
  $(".delsubs").click(function(event) {
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
        $.ajax({
          url: "{{ url('dashboard/membership') }}/" + id,
          type: 'DELETE',
          data: {
            id: id,
            _token: '{{ csrf_token() }}'
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
@endpush

@endsection