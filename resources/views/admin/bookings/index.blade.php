@extends('layouts.admin')
@section('title',$title)
@section('content')
<!-- /Row -->
<div class="row">
   <div class="col-sm-6">
      <h4 class="card-title">
         {{$title}}
      </h4>
   </div>
   <div class="col-sm-6 text-right">
      @can('blog_create')
      <div style="margin-bottom: 10px;" class="row">
         <div class="col-lg-12">
            {{--<a class="addnew-btn" href="{{ route('dashboard.bookings.index') }}">
            {{ trans('global.add') }} Articles
            </a>--}}
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
                  <table  id="page-length-option" class="display">
                     <form method="GET" action="{{route('dashboard.bookings.index')}}" id="  datefilter">
                         <div class="search_bar">
                               <input type="" class="form-controller" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Search by user"></input>
                                <button type="submit" class="serch_btn">Search</button>
                         </div>
                         <div class="filters" >
                              <select class="form-control approvefilter" name="filter">
                                <option value="">-Filter by status-</option>
                                <option value="pending" {{ (request()->get('filter') == 'pending') ? 'selected' : '' }}>Pending</option>
                                <option value="booked" {{ (request()->get('filter') == 'booked') ? 'selected' : '' }}>Booked</option>
                                <option value="cancelled" {{ (request()->get('filter') == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                              </select>
                         </div>
                         <div class="filters" >
                              <select class="form-control approvefilter" name="property_id">
                                <option value="">-Filter by property-</option>
                                @foreach($properties as $val)
                                  <option value="{{$val->id}}" {{ (request()->get('property_id') == $val->id) ? 'selected' : '' }}>{{$val->property_title}}</option>
                                @endforeach
                             
                              </select>
                         </div>
                         <div class="date">
                             <input type="date" name="date" value="" class="form-control datefilter">
                         </div>
                     </form>
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Customer </th>
                           <th>Property </th>
                           <th>Space</th>
                           <th>Booking for</th>
                           <th>Date</th>
                           <th>Amount</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($Bookings)
                        <?php $i = 1; ?>
                        @foreach($Bookings as $item)
                        <tr id='{{ $item->id }}'>
                           <td>{{ $item->id }}</td>
                          <td>{{ $item->user_name }}</td>
                           <td>{{ $item->property_title }}</td>
                           <td>{{ $item->space_title }}</td>
                             <td>
                             @if($item->period_of_day == '1') 
                              <span class="chip green lighten-5"><span class="green-text">Day</span></span>
                             @elseif($item->period_of_day == '2')
                              <span class="chip green lighten-5"><span class="green-text">Night</span></span>
                             @elseif($item->period_of_day == '3')
                              <span class="chip cyan lighten-5"><span class="cyan-text">Day & Night</span></span>
                             @endif
                           </td>
                           <td>{{\Carbon\Carbon::parse($item->created_at)->format('d-M-Y')}}</td>
                           <td>£{{ $item->total_price_sum }}</td>
                           <td>
                            @if($item->booking_status == 'hold')
                            @elseif($item->booking_status == 'pending')
                             <span class="chip cyan lighten-5"><span class="cyan-text">{{ $item->booking_status }}</span></span>
                            @elseif($item->booking_status == 'booked')
                              <span class="chip green lighten-5"><span class="green-text">{{ $item->booking_status }}</span></span>
                            @elseif($item->booking_status == 'enquiry_pending')
                            <span class="chip red lighten-5"><span class="red-text">enquiry pending</span></span>
                             @elseif($item->booking_status == 'enquiry_approved')
                            <span class="chip green lighten-5"><span class="green-text">enquiry approved</span></span>
                            @elseif($item->booking_status == 'enquiry_cancelled')
                            <span class="chip red lighten-5"><span class="red-text">enquiry rejected</span></span>
                            @else
                              <span class="chip red lighten-5"><span class="red-text">{{ $item->booking_status }}</span></span>
                            @endif
                           </td>
                           <td>
                              @can('blog_edit')
                             
                               <a class="btn btn-xs btn-info" href="{{ route('dashboard.single-booking',$item->id) }}" title="invoice">
                             <i class="material-icons">remove_red_eye</i>
                              </a>
                              @endcan
                              @if($item->booking_status == 'inprocess')
                                <form action="{{ route('dashboard.change-booking-status') }}" method="post" id="change-booking-status">
                                  @csrf
                                  <input type="hidden" name="id" value="{{$item->id}}">
                                  <button title="change-status" class="btn btn-danger change-booking-status" name="change_status" value="{{ $item->booking_status=='inprocess'?'approved':'hold'}}"><i class="material-icons">check_circle</i></button>
                                </form>
                              @endif
                              @if($item->booking_status == 'enquiry_pending')
                                <form action="{{ route('dashboard.change-booking-status') }}" method="post" id="change-booking-status">
                                  @csrf
                                  <input type="hidden" name="id" value="{{$item->id}}">
                                  <button title="approve enquiry" class="btn btn-danger change-booking-status" name="enquiry_pending" value="enquiry_pending"><i class="material-icons">offline_pin</i></button>
                                </form>
                              @endif
                              @if($item->booking_status == 'enquiry_pending')
                                <form action="{{ route('dashboard.change-booking-status') }}" method="post" id="change-booking-status">
                                  @csrf
                                  <input type="hidden" name="id" value="{{$item->id}}">
                                  <button title="reject enquiry" class="btn btn-danger change-booking-status" name="enquiry_cancelled" value="enquiry_cancelled"><i class="material-icons">do_not_disturb</i></button>
                                </form>
                              @endif
                              @can('blog_delete')
                              <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a>
                              {{-- <a href="{{ route('dashboard.blog.show',$item->id) }}" class="btn btn-xs btn-primary" ><i class="fas fa-eye"></i></a> --}}
                              @endcan
                           </td>

                        </tr>
                        @endforeach
                        @endisset
                     </tbody>
                  </table>
                    <div class="pages">
                      {!! $Bookings->links() !!}
                    </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
    <style>
button.btn.btn-danger.approvestatus {
    color: #000;
 
}
button.btn.btn-success.show-status {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}

a:hover, a:focus {
    color: #000;
    text-decoration: none;
}
.filters {
    float: left;
}
.date{
   float: left;
    margin-left: 10px;
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
   
           url: "{{ url('dashboard/bookings') }}/" + id,
   
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
<script>
   $(".change-booking-status").click(function() {
      $('#change-booking-status').submit();
    });
</script>
@endpush
@endsection