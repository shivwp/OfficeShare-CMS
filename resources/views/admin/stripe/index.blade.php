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
        @can('stripe_create')
        <form action="{{ route('dashboard.stripe-setup.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
            @csrf

            @if(session('msg'))
            <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
            @endif
            <div class="row border-light">
                <div class="col-sm-6 form-group">
                    <label>Payment Option</label>
                    <select required="" class="form-control" name="name" id="payment">
                        <option value="{{ isset($edstripe->payment_gateway)?$edstripe->payment_gateway:'' }}">
                            {{ isset($edstripe->payment_gateway)?$edstripe->payment_gateway:'Select One' }}
                        </option>
                        <option value="COD">COD</option>
                        <option value="Stripe">Stripe</option>
                        <option value="Paypal">Paypal</option>
                        <!-- <option value="Paypal-Debit">Paypal/Debit-Credit Card</option> -->
                    </select>
                </div>
                <div class="col-sm-6 form-group pk pt-1">
                    <label>Client key</label>
                    <input type="text" name="pub_key" value="{{ isset($edstripe->publishing_key)?$edstripe->publishing_key:'' }}" class="form-control">
                    <input type="hidden" name="id" value="{{ isset($edstripe->id)?$edstripe->id:'' }}" class="form-control">
                </div>
                <div class="col-sm-8 form-group sk">
                    <label>Secret key</label>
                    <input type="text" name="sec_key" value="{{ isset($edstripe->secret_key)?$edstripe->secret_key:'' }}" class="form-control">
                </div>
                <div class="col-sm-4 form-group pt-4 text-right">
                    <button class="btn btn-primary btn-sm">Add & Update</button>
                </div>
            </div>
        </form>
        @endcan<br>
        <div class="table-responsive">
            <table class="table table-striped table-hover datatable datatable-User" id="example" style="width:100%;">
                <thead>
                    <tr>
                        <th>Payment Options</th>
                        <th>Client key</th>
                        <th>Secret key</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="c">
                    @isset($stripe)
                    <?php $i = 0; ?>
                    @foreach ($stripe as $item)
                    <tr id='{{ $item->id }}'>
                        <td>{{ $item->payment_gateway }}</td>
                        <td>{{ $item->publishing_key }}</td>
                        <td>{{ $item->secret_key }}</td>
                        <td>
                            @if ($item->status == 1)
                            <a href="{{ url('dashboard/change-payment-status') }}/{{ $item->id }}/0" class="btn btn-success btn-xs edit btn-rounded">Active</a>
                        </td>
                        @else
                        <a href="{{ url('dashboard/change-payment-status') }}/{{ $item->id }}/1" class="btn btn-danger btn-xs edit btn-rounded">Inactive</a></td>
                        @endisset
                        <td>
                            @can('stripe_edit')
                            <a class="btn btn-xs btn-info " href="{{ route('dashboard.stripe-setup.edit',$item->id) }}">
                                <i class="far fa-edit"></i>
                            </a>
                            @endcan
                            @can('stripe_delete')
                            <a href="javascript:void(0)" class="btn btn-xs btn-danger delkey"><i class="fas fa-trash-alt"></i></a>
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
<!-- Edit CAT -->
<script type="text/javascript">
    $(".delkey").click(function(event) {
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
                    url: "{{ url('dashboard/stripe-setup') }}/" + id,
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


<!-- for data search -->
<script>
    $(document).on('change', '#payment', function(event) {
        if ($(this).val() == "COD") {
            $(".sk").hide();
            $(".pk").hide();
        } else {
            $(".sk").show();
            $(".pk").show();
        }
    });
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