@extends('layouts.admin')
@section('title', $title)
@section('content')
    <!-- /Row -->
    <div class="card">
        <div class="card-header">
          <div class="card-body">
            @can('tax_create')
            <form action="{{ route('admin.tax.store') }}" method="post" class="p-2 ">
                @csrf
                <h5>{{ $title }}</h5>
                @if(session('msg'))
                <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
               @endif
            <div class="row border-light">
              
               <div class="col-sm-5 form-group">
                 <label>Tax Type</label>
                   <input type="text" name="tax_type" value="{{ isset($taxs->tax_type)?$taxs->tax_type:'' }}" class="form-control" required="">
               </div>
                <div class="col-sm-5 form-group">
                 <label>Tax Value in(%)</label>
                   <input type="number" name="tax" value="{{ isset($taxs->tax)?$taxs->tax:'' }}" class="form-control" required="">
                   <input type="hidden" name="id" value="{{ isset($taxs->id)?$taxs->id:'' }}" class="form-control" required="">
               </div>
                <div class="col-sm-2 form-group">
                   <button class="btn btn-primary btn-sm">Add & Update</button>
               </div>
              </div>
             </form>
             @endcan<br>
            <div class="table-responsive">
                <table class=" table table-striped table-hover datatable datatable-User" id="example">
                    <thead>
                        <tr>
                            <th>Tax Type</th>
                            <th>Tax Value in(%)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="c">
                        @isset($tax)
                            <?php $i = 0; ?>
                            @foreach ($tax as $item)
                                <tr id='{{ $item->id }}'>
                                    <td>{{ Str::ucfirst($item->tax_type) }}</td>
                                    <td>{{ Str::ucfirst($item->tax) }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <a class="btn btn-success btn-xs edit btn-rounded" href="{{url('admin/change-tax-status')}}/{{ $item->id }}">Active</a>
                                    </td>
                                @else
                                    <a class="btn btn-danger btn-xs edit btn-rounded" href="{{url('admin/change-tax-status')}}/{{ $item->id }}">De-active</a></td>
                            @endisset
                            <td>
                                @can('tax_edit')
                                    <a class="btn btn-xs btn-info " href="{{ route('admin.tax.edit',$item->id) }}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                @endcan
                                @can('tax_delete')
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger deltax"><i
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
            $(".deltax").click(function(event) {
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
                            url: "{{ url('admin/tax') }}/"+id,
                            type: 'DELETE',
                            data:{ 
                                id:id,
                                _token:'{{csrf_token() }}'
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
