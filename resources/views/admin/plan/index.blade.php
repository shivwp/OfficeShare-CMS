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
            <a class="addnew-btn" href="{{ route('dashboard.plan.create') }}">
            {{ trans('global.add') }} Packages
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
                           <th>Title</th>
                           <th>Validity</th>
                           <th>Price</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($plan)
                        <?php $i = 1; ?>
                        @foreach($plan as $item)
                        <tr id='{{ $item->id }}'>
                           <td>{{ $item->id }}</td>
                           <td>{{ $item->title }}</td>
                           <td>{{  $item->validity }}</td>
                           <td>{{  $item->price }}</td>
                           <td>
                              @can('plan_edit')
                              <a class="btn btn-xs btn-info" href="{{ route('dashboard.plan.edit',$item->id) }}">
                              <i class="far fa-edit"></i>
                              </a>
                              @endcan
                              @can('plan_delete')
                              <a href="{{ route('dashboard.plan.destroy',$item->id) }}" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></a>
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
   
           url: "{{ url('dashboard/plan') }}/" + id,
   
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