@extends('layouts.admin')
@section('title', $title)
@section('content')
<!-- /Row -->
<h4>Articles Category</h4>

<div class="card">
   <div class="card-header">
      <div class="card-body">
         @can('currency_create')
         <form action="{{ route('dashboard.blog-category.store') }}" method="post" class="p-2 ">
            @csrf
            @if(session('msg'))
            <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
            @endif
            <div class="row border-light">
               <div class="col-sm-12 form-group">
                  <label>Category Name</label>
                  <input type="text" name="name" value="{{ isset($edblog->name)?$edblog->name:'' }}" class="form-control" required="">
                  <input type="hidden" name="id" value="{{ isset($edblog->id)?$edblog->id:'' }}" class="form-control" required="">
               </div>
               <div class="col-sm-12 form-group">
                  <label>Slug</label>
                  <input type="text" name="slug" value="{{ isset($edblog->slug)?$edblog->slug:'' }}" class="form-control" required="">
               </div>
               <div class="col-sm-2 form-group text-center pt-4">
                  <button class="btn btn-primary btn-sm">Add & Update</button>
               </div>
            </div>
         </form>
         @endcan<br>
      </div>
   </div>
</div>
@if(!empty($blogc))
<div class="row">
   <div class="col s12" id="s1">
      <div class="card">
         <div class="card-content">
            <div class="row">
               <div class="col s12">
             
                  <table  id="page-length-option" class="display">
                     <form action="{{route('dashboard.blog-category.index')}}" method="get">
                    <div class="search_bar">
                          @csrf
                           <input type="" class="form-controller" id="search" name="search" placeholder="Article category search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}"></input>
                            <button type="submit" class="serch_btn">Search</button>
                     </div>
                      <div class="filters" >
                          <select class="form-control approvefilter" name="filter">
                            <option value="">-Filter by status-</option>
                            <option value="1" {{ (request()->get('filter') == '1') ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (request()->get('filter') == '0') ? 'selected' : '' }}>Inactive</option>
                          </select>
                      </div>
                      </form>
                     <thead>
                        <tr>
                          <th>#</th>
                           <th>Title</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @if(!empty($blogc))
	                        <?php $i = 0; ?>
	                        @foreach ($blogc as $item)
		                        <tr id='{{ $item->id }}'>
		                          <td>{{ $item->id }}</td>
		                           <td>{{ $item->name }}</td>
		                           <td>

		                              @if($item->status == 0)
		                               <span class="chip red lighten-5" ><span class="red-text">Inactive</span></span>
		                              @else
		                              
		                                <span class="chip green lighten-5">
		                              <span class="green-text">Active</span></span>
		                              @endif  
		                           <td>
		                              @can('blogcategory_edit')
		                              <a class="btn btn-xs btn-info " href="{{ route('dashboard.blog-category.edit',$item->id) }}">
		                              <i class="far fa-edit"></i>
		                              </a>
		                              @endcan
		                              @can('blogcategory_delete')
		                              <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a>
		                              @endcan
		                           </td>
	                        </tr>
	                        @endforeach
                        @endif
                     </tbody>
                  </table>
              
                    @if(!empty($blogc))
                   <div class="pages">
              			{!! $blogc->links() !!}
            	   </div>
            	   @endif
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif
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

    </style>

@push('ajax-script')
<!-- Edit CAT -->
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
                   url: "{{ url('dashboard/blog-category') }}/" + id,
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