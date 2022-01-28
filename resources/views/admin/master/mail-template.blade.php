@extends('layouts.admin')
@section('content')
<div class="row">
   <div class="col-sm-6">
      <h4>{{ $title }}</h4>
   </div>
   <div class="col-sm-6 text-right">
      <a href="{{ route('dashboard.mail-template.create') }}" class="addnew-btn">Add Mail Template</a>
   </div>
</div>
<div class="row">
   <div class="col s12" id="s1">
      <div class="card">
         <div class="card-content">
            <div class="row">
               <div class="col s12">
                  <table  id="page-length-option" class="display">
                      <form action="{{route('dashboard.mail-template.index')}}" method="get">
                          @csrf
                          <div class="search_bar">  
                               <input type="" class="form-controller" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Message for"></input>
                              <button type="submit" class="serch_btn">Search</button>
                          </div>
                          <div class="search_bar">
                              <select class="form-control approvefilter" name="filter">
                                <option value="">-Filter by status-</option>
                                <option value="1" {{ (request()->get('filter') == '1') ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (request()->get('filter') == '0') ? 'selected' : '' }}>Deactive</option>
                              </select>
                          </div>
                       </form>
                     <thead>
                        <tr class="text-sm-left">
                          <th>#</th>
                           <th>Message For</th>
                           <th>Name</th>
                           <th>Subject</th>
                           <th>From</th>
                           <th>Status</th>
                           {{--<th>Reply From</th>--}}
                           <th>Message Categories</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c" id="attrvdata">
                        @isset($msg)
                        <?php $i = 0; ?>
                        @foreach($msg as $item)
                        
                        
                        <tr id='{{ $i }}'>
                          <td>{{ $item->id }}</td>
                        <td>{{ Str::ucfirst(str_replace('_',' ',$item->status)) }}</td>
                        <td>{{ Str::ucfirst($item->name) }}</td>
                        <td>{{ Str::ucfirst($item->subject) }}</td>
                        <td> {{ $item->from_email }}</td>
                        <td>
                          @if($item->active_status == 1) 
                          <span class="chip green lighten-5><span class="green-text">Active</span></span>
                          @else
                            <span class="chip red lighten-5">
                            <span class="red-text">Deactivated</span>
                            </span>
                          @endif
                        </td>
                        {{--<td> {{ $item->reply_email }}</td>--}}
                        <td> {{str_replace('_',' ',$item->msg_cat)  }}</td>
                        <td>
                           <div class="dropdown">
                              <a class="btn btn-xs btn-info" href="{{ route('dashboard.mail-template.edit',$item->id) }}">
                              <i class="far fa-edit"></i>
                              </a>
                              <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog" id="delMsg"><i class="fas fa-trash-alt"></i></a>
                           </div>
                        </td>
                        </tr>
                        <?php $i++ ?>
                        @endforeach
                        @endisset
                     </tbody>
                  </table>
                   <div class="pages">
              {!! $msg->links() !!}
            </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
    <style>
      input#search {
        margin-top: 10px;
        margin-left: 10px;
      }
      p.alert.alert-success.text-center {
    background: #fc6565;
    padding-bottom: 8px;
    padding-top: 8px;
    font-weight: 800;
}

    </style>
@push('ajax-script')
<script type="text/javascript">
   $(document).ready(function() {
   
     $(document).on('click', "#delMsg", function(event) {
   
       let id = $(this).parents('tr').attr('id');
   
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
   
             url: "{{ url('dashboard/mail-template') }}/" + id,
   
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
   
   });
</script>
<style type="text/css">
   th{
   text-align: center;
   }
</style>
@endpush
@endsection