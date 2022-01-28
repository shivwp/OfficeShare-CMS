@extends('layouts.admin')

@section('title', $title)

@section('content')

<!-- /Row -->
<div class="card">
    <div class="card-header ">
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-sm-6 h6"> {{ $title }}</div>
            @can('coupon_create')
            <div class="col-sm-6 text-right">
                <a class="btn btn-dark btn-sm rounded " href="{{ route('dashboard.coupon.create') }}">
                    {{ trans('global.add') }} Coupon

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

                        <th>Coupon Code</th>

                        <th>Coupon Amount</th>
                        <th>Coupon Start Date</th>
                        <th>Coupon Expiry Date</th>
                        <th>Send Mail</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody class="c">

                    @isset($coupon)

                    <?php $i = 0; ?>

                    @foreach ($coupon as $item)

                    <tr id='{{ $item->id }}'>
                        <td>{{ Str::ucfirst($item->code) }}</td>
                        <td>{{ Str::ucfirst($item->coupon_amount) }}</td>

                        <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->expiry_date)->format('d-m-Y') }}</td>
                        <td><a href="{{ url('dashboard/coupon-mail') }}/{{ $item->id }}" class="btn btn-xs btn-primary">Send mail</a></td>
                        <td>

                            @can('color_edit')

                            <a class="btn btn-xs btn-info editColor" href="{{ route('dashboard.coupon.edit',$item->id) }}">

                                <i class="far fa-edit"></i>

                            </a>

                            @endcan

                            @can('color_delete')

                            <a href="javascript:void(0)" class="btn btn-xs btn-danger delCuopon"><i class="fas fa-trash-alt"></i></a>

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
    $(".delCuopon").click(function(event) {
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
                    url: "{{ url('dashboard/coupon') }}/" + id,
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