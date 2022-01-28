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
      @can('plan_create')
      <div style="margin-bottom: 10px;" class="row">
         <div class="col-lg-12">
            <a class="addnew-btn" href="{{ route('dashboard.plan-feature.create') }}">
            {{ trans('global.add') }} Package Feature
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
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($feature)
                        <?php $i = 1; ?>
                        @foreach($feature as $item)
                        <tr id='{{ $item->id }}'>
                           <td>{{ $item->id }}</td>
                           <td>{{ $item->title }}</td>
                           <td>
                              @can('plan_edit')
                              <a class="btn btn-xs btn-info" href="{{ route('dashboard.plan-feature.edit',$item->id) }}">
                              <i class="far fa-edit"></i>
                              </a>
                              @endcan
                              @can('plan_delete')
                              <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a>
                              {{-- <a href="{{ route('dashboard.plan-feature.show',$item->id) }}" class="btn btn-xs btn-primary" ><i class="fas fa-eye"></i></a> --}}
                              @endcan
                           </td>
                        </tr>
                        @endforeach
                        @endisset
                     </tbody>
                  </table>
                  <div class="pages">
              {!! $feature->links() !!}
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
   
           url: "{{ url('dashboard/plan-feature') }}/" + id,
   
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