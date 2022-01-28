@extends('layouts.admin')
@section('title', $title)
@section('content')
<!-- /Row -->

<h4>
          {{ $title }}
        </h4>
<div class="card">
   <div class="card-header">
      <div class="card-body">
         @can('currency_create')
         <form action="{{ route('dashboard.newsletter.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
            @csrf
            @if(session('msg'))
            <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
            @endif
            <div class="row border-light">
               <div class="col-sm-8 form-group">
                  <label>Mailchimp Api</label>
                  <input type="text" name="api" value="{{ isset($edchimp->api)?$edchimp->api:'' }}" class="form-control" required="">
                  <input type="hidden" name="id" value="{{ isset($edchimp->id)?$edchimp->id:'' }}" class="form-control" required="">
               </div>
               <div class="col-sm-4 form-group text-center pt-4">
                  <button class="btn btn-primary btn-sm">Add & Update</button>
               </div>
            </div>
         </form>
         @endcan<br>
      </div>
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
                           <th>Api</th>
                           <th>Audience Id</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($newsletter )
                        <?php $i = 0; ?>
                        @foreach ($newsletter as $item)
                        <tr id='{{ $item->id }}'>
                           <td>{{ $item->api }}</td>
                           <td>{{ $item->audience_id }}</td>
                           <td>

                            @if($item->status == 1)
                   <span class="chip green lighten-5">
                      <span class="green-text">Active</span>
                </span>
                @else
              
                 <span class="chip red lighten-5"><span class="red-text">Inactive</span></span>
                @endif
                             <!--  @if($item->status == 1)
                              <a class="btn btn-success btn-xs btn-rounded" href="{{ url('dashboard/change-chimp-status/').$item->id.'/0' }}">Active</a>
                           </td>
                           @else
                           <a class="btn btn-danger btn-xs btn-rounded" href="{{ url('dashboard/change-chimp-status/').$item->id.'/1' }}">Inactive</a></td>
                           @endif -->
                           <td>
                              @can('currency_edit')
                              <a class="btn btn-xs btn-info " href="{{ route('dashboard.newsletter.edit',$item->id) }}">
                              <i class="far fa-edit"></i>
                              </a>
                              @endcan
                              @can('currency_delete')
                              <a href="javascript:void(0)" class="btn btn-xs btn-danger delchimp"><i class="fas fa-trash-alt"></i></a>
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
      </div>
   </div>
</div>
@push('ajax-script')
<!-- Edit CAT -->
<script type="text/javascript">
   $(".delchimp").click(function(event) {
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
                   url: "{{ url('dashboard/newsletter') }}/" + id,
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