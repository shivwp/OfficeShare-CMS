@extends('layouts.admin')
@section('content')
<div class="row">
   <div class="col-sm-6">
      <h4>
         {{$title}}: #{{$data->id}}
      </h4>
   </div>
   <div class="col-sm-6 text-right">
      @can('user_create')
      <div style="margin-bottom: 10px;" class="row">
         <div class="col-lg-12">
            <!-- <a class="addnew-btn" href="">
            {{ trans('global.add') }} Booking
            </a> -->
         </div> 
      </div>
      @endcan
   </div>
</div>
<div class="row">
   <div class="col s12" id="s1">
      <div class="card">
         <div class="card-content">
            <div class="row">
               <div class="col s12">
                    <h5><strong>Booking Details</strong></h5>
                    <table id="page-length-option" class="display border">
                        <tbody>
                            <tr id="{{ $data->id }}">
                                <td>Booking ID</td>
                                <td>#{{ $data->id }}</td>
                            </tr>
                            <tr id="{{ $data->id }}">
                                <td>Customer</td>
                                <td>{{$data->user->name}}</td>
                            </tr>
                            <tr>
                                <td><strong>Landload</strong></td>
                                <td>{{$data->landload->name}}</td>
                            </tr>
                            <tr>
                                <td><strong>Booking Status</strong></td>
                                <td>{{$status}}</td>
                            </tr>
                             <tr>
                                <td><strong>Booking Dates</strong></td>
                                <td>
                                    @php
                                        $dates = json_decode($data->booking_details->booking_dated, true);
                                        $i = 1;
                                        foreach($dates as $date) {
                                            if($i==1) {
                                                echo \Carbon\Carbon::parse($date)->format('d-M-Y');
                                            } else {
                                                echo ', '.\Carbon\Carbon::parse($date)->format('d-M-Y');
                                            }
                                            $i++;
                                        }
                                    @endphp
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Day Wise Booked Desk</strong></td>
                                <td>{{$data->booking_details->booked_desk}}</td>
                            </tr>
                           {{-- <tr>
                                <td><strong>Booked Desk Price</strong></td>
                                <td>£ {{$data->price}} / per desk</td>
                                </tr>--}}
                            <tr>
                                <td><strong>Total Booked Desk</strong></td>
                                <td> {{$total_booked_desk}}</td>
                            </tr>
                            <tr>
                                <td><strong>Booking Total Price</strong></td>
                                <td>£ {{$total_booking_price}}</td>
                            </tr>
                           
                            
                            <tr>
                                <td><strong>Booked At</strong></td>
                                <td>{{\Carbon\Carbon::parse($data->created_at)->format('d-M-Y')}}</td>
                            </tr>
                            <tr>
                                <td><strong>Signature</strong></td>
                                <td>@if(!empty($data->signature)) <img src="{{ $data->signature->signature }}" width="80"> @endif</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5><strong>Customer Details</strong></h5>
                            <table  id="page-length-option" class="display border">
                                <tbody>
                                    <tr>
                                        <td><strong>Customer ID</strong></td>
                                        <td>#{{$data->user->id}}</td>
                                    </tr> 
                                    <tr>
                                        <td><strong>Customer Name</strong></td>
                                        <td>{{$data->user->name}}</td>
                                    </tr> 
                                    <tr>
                                        <td><strong>Customer Email</strong></td>
                                        <td>{{$data->user->email}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer Phone</strong></td>
                                        <td>{{$data->user->phone}}</td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><strong>Landload Details</strong></h5>
                            <table  id="page-length-option" class="display border">
                                <tbody>
                                    <tr>
                                        <td><strong>Landload ID</strong></td>
                                        <td>#{{$data->landload->id}}</td>
                                    </tr> 
                                    <tr>
                                        <td><strong>Landload Name</strong></td>
                                        <td>{{$data->landload->name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Landload Email</strong></td>
                                        <td>{{$data->landload->email}}</td>
                                    </tr> 
                                    <tr>
                                        <td><strong>Landload Phone</strong></td>
                                        <td>{{$data->landload->phone}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h5><strong>Property Details</strong></h5>
                    <table id="page-length-option" class="display border">
                        <tbody>
                            <tr id="{{ $data->property->property_id }}">
                                <td>Title</td>
                                <td>{{ $data->property->property_title }}</td>
                            </tr> 
                            <tr id="{{ $data->property->property_id }}">
                                <td>Short Description</td>
                                <td>{{ $data->property->short_description }}</td>
                            </tr>
                            <tr id="{{ $data->property->property_id }}">
                                <td>Type</td>
                                <td>{{ $data->property->property_type }}</td>
                            </tr>
                            <tr id="{{ $data->property->property_id }}">
                                <td>Image</td>
                                <td><img src="{{ $data->property->property_featured_image }}" width="150"></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h5><strong>Space Details</strong></h5>
                    <table  id="page-length-option" class="display border">
                        <tbody>
                            <tr id="{{ $data->id }}">
                                <td>Space ID</td>
                                <td>#{{ $data->space->space_id }}</td>
                            </tr>
                            <tr id="{{ $data->id }}">
                                <td>Title</td>
                                <td>{{ $data->space->space_title }}</td>
                            </tr>
                            <tr id="{{ $data->id }}">
                                <td>Space Price</td>
                                <td>£ {{ $data->space->price }}</td>
                            </tr>
                            <tr id="{{ $data->id }}">
                                <td>Type</td>
                                <td>{{ $data->space->space_type }}</td>
                            </tr>
                            <tr id="{{ $data->id }}">
                                <td>Booked Space</td>
                                <td>{{$data->booking_details->booked_desk}}</td>
                            </tr>
                            <tr id="{{ $data->id }}">
                                <td>Image</td>
                                <td><img src="{{ $data->space->space_featured_image }}" width="150"></td>
                            </tr>
                        </tbody>
                    </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
@parent
<style type="text/css">
    td {
        text-align: left;
        border: 1px solid;
        padding: 8px 10px;
    }
</style>
<script>
  {{-- $(function() {
       let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
       @can('user_delete')
       let deleteButtonTrans = '{{ trans('
       global.datatables.delete ') }}'
       let deleteButton = {
           text: deleteButtonTrans,
           url: "{{ route('dashboard.users.massDestroy') }}",
           className: 'btn-danger',
           action: function(e, dt, node, config) {
               var ids = $.map(dt.rows({
                   selected: true
               }).nodes(), function(entry) {
                   return $(entry).data('entry-id')
               });
   
               if (ids.length === 0) {
                   alert('{{ trans('
                       global.datatables.zero_selected ') }}')
   
                   return
               }
   
               if (confirm('{{ trans('
                       global.areYouSure ') }}')) {
                   $.ajax({
                           headers: {
                               'x-csrf-token': _token
                           },
                           method: 'POST',
                           url: config.url,
                           data: {
                               ids: ids,
                               _method: 'DELETE'
                           }
                       })
                       .done(function() {
                           location.reload()
                       })
               }
           }
       }
       dtButtons.push(deleteButton)
       @endcan
   
       $.extend(true, $.fn.dataTable.defaults, {
           order: [
               [1, 'desc']
           ],
           pageLength: 100,
       });
       $('.datatable-User:not(.ajaxTable)').DataTable({
           buttons: dtButtons
       })
       $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
           $($.fn.dataTable.tables(true)).DataTable()
               .columns.adjust();
       });
   }); --}}
    $(".approvestatus").click(function() {
      $('#approvestatus').submit();
   });
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
          text: "You won't be able to revert this item!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ url('dashboard/users') }}/" + id,
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
   
@endsection