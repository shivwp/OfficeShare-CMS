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

        <form action="{{ route('dashboard.currency-exchange-rate.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Source Currency</label>
                    <select class="form-control" name="source_id">
                        <option value="{{isset($edcurr->sourceCurrency['id'])?$edcurr->sourceCurrency['id']:''}}">
                            {{isset($edcurr->sourceCurrency['name'])?$edcurr->sourceCurrency['name']:''}}
                        </option>
                        <option value="{{ isset($defcurrency->id)?$defcurrency->id:'' }}">{{ isset($defcurrency->name)?$defcurrency->name:'' }}</option>
                        <select>
                            <input type="hidden" name="rid" value="{{ isset($edcurr->id)?$edcurr->id:'' }}">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Converstion Currency</label>
                    <select class="form-control" name="target_id">
                        <option value="{{isset($edcurr->currency['id'])?$edcurr->currency['id']:''}}">
                            {{isset($edcurr->currency['name'])?$edcurr->currency['name']:''}}
                        </option>
                        @if(isset($currency))
                        @foreach ($currency as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        @endif
                        <select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Source Currency Rate </label>
                    <input type="text" name="sourcerate" class="form-control" value="{{ isset($edcurr['source_rate'])?$edcurr['source_rate']:'' }}">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Conversion Currency Rate </label>
                    <input type="text" name="targetrate" class="form-control" value="{{isset($edcurr->target_rate)?$edcurr->target_rate:''}}">
                </div>
                <div class="col-sm-12 form-group text-right">
                    <button class="btn btn-primary btn-sm">Add Rate</button>
                </div>
            </div>
        </form>
        @endcan<br>
        <div class="table-responsive">
            <table class=" table table-striped table-hover datatable datatable-User" id="example">
                <thead>
                    <tr>
                        <th>Source Currency</th>
                        <th>Conversion Currency</th>
                        <th>Currency Rate</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="c">
                    @isset($currency )
                    <?php $i = 0; ?>
                    @foreach ($curRate as $item)
                    <tr id='{{ $item->id }}'>
                        <td>{{ Str::ucfirst($item->sourceCurrency['name']) }}</td>
                        <td>{{ Str::ucfirst($item->currency['name']) }}</td>
                        <td>{{ Str::ucfirst($item->target_rate) }}</td>
                        <td>
                            @if($item->status == 1)
                            <button class="btn btn-success btn-xs btn-rounded change-curr-rate" id="0">Active</button>
                        </td>
                        @else
                        <button class="btn btn-danger btn-xs btn-rounded change-curr-rate" id="1">De-active</button></td>
                        @endif
                        <td>
                            @can('currency_edit')
                            <a class="btn btn-xs btn-info " href="{{ route('dashboard.currency-exchange-rate.edit',$item->id) }}">
                                <i class="far fa-edit"></i>
                            </a>
                            @endcan
                            @can('currency_delete')
                            <a href="javascript:void(0)" class="btn btn-xs btn-danger delcurrate"><i class="fas fa-trash-alt"></i></a>
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
    $(".delcurrate").click(function(event) {
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
                    url: "{{ url('dashboard/currency-exchange-rate') }}/" + id,
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