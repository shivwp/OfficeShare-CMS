@extends('layouts.admin')
@section('title',$title)
@section('content')
<!-- /Row -->
<div class="card">
  <div class="card-header ">
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
                  <a class="addnew-btn" href="{{ route('dashboard.blog.create') }}">
                  {{ trans('global.add') }} Articles
                  </a>
               </div>
            </div>
            @endcan
         </div>
      </div>
   </div>
<div class="row width-style">
   <div class="col s12" id="s1">
      <div class="card">
         <div class="card-content">
            <div class="row">
               <div class="col s12">
                  <table  id="page-length-option" class="display">
                     <form method="GET" action="{{route('dashboard.blog.index')}}" id="datefilter">
                       <div class="search_bar">
                             <input type="" class="form-controller" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Article search"></input>
                              <button type="submit" class="serch_btn">Search</button>
                       </div>
                        <div class="filters">
                            <select class="form-control approvefilter" name="filter">
                              <option value="">-Filter by status-</option>
                              <option value="0" {{ (request()->get('filter') == '0') ? 'selected' : '' }}>Pending</option>
                              <option value="1" {{ (request()->get('filter') == '1') ? 'selected' : '' }}>Publish</option>
                            </select>
                        </div>
                         <div class="filters" >
                            <select class="form-control approvefilter" name="filter_cat">
                              <option value="">-Filter by category-</option>
                              @foreach($blog_Cat as $cat)
                                <option value="{{$cat->id}}" {{ (request()->get('filter_cat') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                           <th>Media</th>
                           <th>Title</th>
                           <th>Created By</th>
                           <th>Date</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($blog)
                        <?php $i = 1; ?>
                        @foreach($blog as $item)
                        <tr id='{{ $item->id }}'>
                           <td>{{ $item->id }}</td>
                            <td>
                              @if($item->media_type=="image")
                              <img src="{{url('')}}/{{$item->media}}" alt="img" style="height:100px;width:130px;">
                              @else
                              <video>
                                 <source src="{{url('')}}/{{$item->media}}">
                              </video>
                              @endif
                           </td>
                           <td style="width: 37%;">{{ $item->title }}</td>
                          
                           <td>{{$item->user['name']}}</td>
                           <td>{{\Carbon\Carbon::parse($item->created_at)->format('d-M-Y')}}</td>
                           <td>
                               @if ($item->status==1)
                                <span class="chip green lighten-5">
                                    <span class="green-text">Published</span>
                              </span>
                               
                                @else
                              <span class="chip red lighten-5"><span class="red-text">Pending</span></span>
                              @endif
                           </td>
                           <td>
                              @can('blog_edit')
                              <a class="btn btn-xs btn-info" href="{{ route('dashboard.blog.edit',$item->id) }}">
                              <i class="far fa-edit"></i>
                              </a>
                              @endcan
                              @can('blog_edit')
                             {{-- <a class="btn btn-xs btn-info" title="change-status" href="{{ route('dashboard.blog-status-change',$item->id) }}">
                                <i class="far fa-calendar-check"></i>
                              </a>--}}
                              @endcan
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
              {!! $blog->links() !!}
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
.width-style{
  width: 100%
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
   
           url: "{{ url('dashboard/blog') }}/" + id,
   
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