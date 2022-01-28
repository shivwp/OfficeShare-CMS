@extends('layouts.admin')
@section('title', $title)
@section('content')

<!-- /Row -->
<div class="card">
    <div class="card-header">
        <h4>{{ $title }}</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @can('desktype_create')
        <form action="{{ route('dashboard.desk-type.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
            @csrf
            @if(session('msg'))
            <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
            @endif
            <div class="row border-light">
                <div class="col-sm-6 form-group pt-1">
                    <label>Desk Type</label>
                    <input type="text" name="name" value="{{isset($eddesk->types)?$eddesk->types:''}}" class="form-control" required="">
                    <input type="hidden" name="id" value="{{isset($eddesk->id)?$eddesk->id:''}}" class="form-control" required="">
                </div>

                <div class="col-sm-4 form-group text-right pt-4">
                    <button class="btn btn-primary btn-sm">Add & Update</button>
                </div>
            </div>
        </form>
        @endcan<br>
        @isset($desk)
        <div class="table-responsive">
            <table class=" table table-striped table-hover datatable datatable-User" id="example">
                <thead>
                    <tr>
                        <th>Desk Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="c">

                    <?php $i = 0; ?>
                    @foreach ($desk as $item)
                    <tr id='{{ $item->id }}'>
                        <td>{{ Str::ucfirst($item->types) }}</td>
                        <td>
                            @if ($item->status == 1)
                            <a href="{{ url('dashboard/change-desk-status') }}/{{ $item->id }}/0" class="btn btn-success btn-xs edit btn-rounded">Active</a>
                        </td>
                        @else
                        <a href="{{ url('dashboard/change-desk-status') }}/{{ $item->id }}/1" class="btn btn-danger btn-xs edit btn-rounded">De-active</a></td>
                        @endisset
                        <td>
                            @can('desktype_edit')
                            <a class="btn btn-xs btn-info " href="{{ route('dashboard.desk-type.edit',$item->id) }}">
                                <i class="far fa-edit"></i>
                            </a>
                            @endcan
                            @can('desktype_delete')
                            <a href="javascript:void(0)" class="btn btn-xs btn-danger deldesk"><i class="fas fa-trash-alt"></i></a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                {{-- <tfoot>
                     <td> {{ $attributeVal->links() }}</td>
                </tfoot> --}}
            </table>
            @endisset
        </div>
    </div>
</div>

@push('ajax-script')
<!-- Edit CAT -->
<script type="text/javascript">
    $(".deldesk").click(function(event) {
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
                    url: "{{ url('dashboard/desk-type') }}/" + id,
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

@endpush
@endsection