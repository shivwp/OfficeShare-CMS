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
        @can('currency_create')
        <form action="{{ route('dashboard.currency-exchange.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
            @csrf
            @if(session('msg'))
            <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
            @endif
            <div class="row border-light">
                <div class="col-sm-4 form-group">
                    <label>Country Name</label>
                    <input type="text" name="country" value="{{ isset($edcurr->country_name)?$edcurr->country_name:'' }}" class="form-control" required="">
                </div>
                <div class="col-sm-4 form-group">
                    <label>Country Code</label>
                    <input type="text" name="country_code" value="{{ isset($edcurr->country_code)?$edcurr->country_code:'' }}" class="form-control" required="">
                </div>
                <div class="col-sm-4 form-group">
                    <label>Currency Name</label>
                    <input type="text" name="name" value="{{ isset($edcurr->name)?$edcurr->name:'' }}" class="form-control" required="">
                    <input type="hidden" name="id" value="{{ isset($edcurr->id)?$edcurr->id:'' }}" class="form-control" required="">
                </div>
                <div class="col-sm-3 form-group">
                    <label>Currency Code</label>
                    <input type="text" name="code" value="{{ isset($edcurr->code)?$edcurr->code:'' }}" class="form-control" required="">
                </div>
                <div class="col-sm-3 form-group">
                    <label>Currency Sign</label>
                    <input type="text" name="cursign" value="{{ isset($edcurr->sign)?$edcurr->sign:'' }}" class="form-control" required="">
                </div>
                <div class="col-sm-3 form-group">
                    <label>Make Default Currency</label><br>
                    <input type="checkbox" name="stat" value="1">
                </div>
                <div class="col-sm-3 form-group text-right pt-4">
                    <button class="btn btn-primary btn-sm">Add & Update</button>
                </div>
            </div>
        </form>
        {{-- <form action="{{ route('dashboard.currency-exchange-rate') }}" method="post" class="p-2 " enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-6 form-group">
                <label>Source Currency</label>
                <select class="form-control" name="source_id">
                    <option></option>
                    <option value="{{ $defcurrency->id }}">{{ $defcurrency->name }}</option>
                    <select>
            </div>
            <div class="col-sm-6 form-group">
                <label>Converstion Currency</label>
                <select class="form-control" name="target_id">
                    <option></option>
                    @if(isset($currency))
                    @foreach ($currency as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    @endif
                    <select>
            </div>
            <div class="col-sm-6 form-group">
                <label>Source Currency Rate </label>
                <input type="text" name="sourcerate" class="form-control">
            </div>
            <div class="col-sm-6 form-group">
                <label>Conversion Currency Rate </label>
                <input type="text" name="targetrate" class="form-control">
            </div>
            <div class="col-sm-6 form-group">
                <button class="btn btn-primary btn-sm">Add Rate</button>
            </div>
        </div>
        </form> --}}
        @endcan<br>
        <div class="table-responsive">
            <table class=" table table-striped table-hover datatable datatable-User" id="example">
                <thead>
                    <tr>
                        <th>Currency Name</th>
                        <th>Currency Code</th>
                        <th>Currency Rate</th>
                        <th>Make Default</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="c">
                    @isset($currency )
                    <?php $i = 0; ?>
                    @foreach ($currency as $item)
                    <tr id='{{ $item->id }}'>
                        <td>{{ Str::ucfirst($item->name) }}</td>
                        <td>{{ Str::ucfirst($item->code) }}</td>
                        <td>{{ Str::ucfirst($item->rate) }}</td>
                        <td>
                            @if($item->status == 1)
                            <button class="btn btn-success btn-xs btn-rounded change-curr" id="0">Active</button>
                        </td>
                        @else
                        <button class="btn btn-danger btn-xs btn-rounded change-curr" id="1">De-active</button></td>
                        @endif
                        <td>
                            @can('currency_edit')
                            <a class="btn btn-xs btn-info " href="{{ route('dashboard.currency-exchange.edit',$item->id) }}">
                                <i class="far fa-edit"></i>
                            </a>
                            @endcan
                            @can('currency_delete')
                            <a href="javascript:void(0)" class="btn btn-xs btn-danger delcur"><i class="fas fa-trash-alt"></i></a>
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
    $(".delcur").click(function(event) {
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
                    url: "{{ url('dashboard/currency-exchange') }}/" + id,
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
        $(document).on('click', '.change-curr', function(event) {
            let id = $(this).parents("tr").attr('id');
            let st = $(this).attr('id');
            $.get('{{ url("admin/make-default-currency") }}/' + id + "/" + st, function(data) {

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
@endpush
@endsection