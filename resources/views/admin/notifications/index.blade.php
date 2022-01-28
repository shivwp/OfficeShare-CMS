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
            <a class="addnew-btn" href="{{ route('dashboard.notifications.create') }}">
              Send Notifications
            </a>
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
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Image</th>
                           <th>Notification Title</th>
                           <th>Notification Description</th>
                           <th>Created At</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($notification)
                        <?php $i = 1; ?>
                        @foreach($notification as $item)
                        <tr id='{{ $item->id }}'>
                           <td>{{  $item->id  }}</td>
                            <td>
                              @if(!empty($item->image) && ($item->image != null))

                                <img src="{{ $item->image }}" alt="img" style="width:125px;">

                              @else
                                 <img src="{{ asset('images/notifications.png') }}" alt="img" style="width:125px;">
                              @endif

                              
                           </td>
                           <td>{{ $item->title }}</td>
                           <td>{{ $item->body }}</td>
                          
                           <td>{{date('d M Y h:i a', strtotime($item->created_at))}}</td>
                        </tr>
                        @endforeach
                        @endisset
                     </tbody>
                  </table>
                  <div class="pages">
                    {!! $notification->links() !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@push('ajax-script')
<script type="text/javascript">
   $(".delblog").click(function(event) {
   
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
   
           url: "{{ url('dashboard/notifications') }}/" + id,
   
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
@endpush
@endsection