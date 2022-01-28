@extends('layouts.admin')

@section('title', $title)

@section('content')

    <!-- /Row -->

    <div class="card">
      <div class="card-header ">
          <div style="margin-bottom: 10px;" class="row">
            <div class="col-sm-6 h6">   {{ $title }}</div>
                @can('giftcard_create')
                   <div class="col-sm-6 text-right">
                    <a class="btn btn-dark btn-sm rounded " href="{{ route('admin.giftcard.create') }}" >

                            {{ trans('global.add') }} Gift Card

                        </a>

                    </div>

                </div>

            @endcan
        </div>
    </div>

        <div class="card-header">

          <div class="card-body">

            <div class="table-responsive">

                <table class=" table table-striped table-hover datatable datatable-User" id="example">

                    <thead>

                        <tr>

                            <th>Gift Card Title</th>
                            <th>Gift Card</th>
                            <th>Gift Amount</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody class="c">

                        @isset($gifts)

                            <?php $i = 0; ?>

                            @foreach ($gifts as $item)

                                <tr id='{{ $item->id }}'>
                                    <td style="width:15%">{{ Str::ucfirst($item->title) }}</td>
                                    <td>
                                      <img src="{{ url('') }}/{{ $item->image }}" style="height:100px;width:200px;">  
                                    </td>
                                <td>{{ $item->amount }}</td>
                                 <td>
                                     @if($item->status==1)
                                     <a href="#" class="btn btn-success btn-sm">Active</a>
                                     @else
                                     <a href="#" class="btn btn-danger btn-sm">Inactive</a>
                                     @endif
                                 </td>
                                <td>

                                @can('giftcard_edit')

                                    <a class="btn btn-xs btn-info " href="{{ route('admin.giftcard.edit',$item->id) }}">

                                        <i class="far fa-edit"></i>

                                    </a>

                                @endcan

                                @can('giftcard_delete')

                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger delgiftcard"><i

                                            class="fas fa-trash-alt"></i></a>

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

    $(".giftcard").click(function(event) {
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
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
    reverseButtons: true
    }).then((result) => {
    if (result.isConfirmed) {
    $.ajax({
    url: "{{ url('admin/giftcard') }}/"+id,
    type: 'DELETE',
    data:{ 
    id:id,
    _token:'{{ csrf_token() }}'
    },
    success:function(data)
    { swalWithBootstrapButtons.fire(
    'Deleted!',
    'Your file has been deleted.',
    'success'
    )
    $("#"+id).remove() 
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