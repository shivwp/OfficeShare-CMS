@extends('layouts.admin')
@section('title', $title)
@section('content')
<!-- /Row -->
<!-- <h4>{{ $title }}</h4> -->
<div class="card">
  <!--  <div class="card-body"id="add_space">
      @can('attr_create')
      <form action="{{ route('dashboard.attribute.store') }}" method="post" class="p-2 " enctype="multipart/form-data">
         @csrf
         @if(session('msg'))
         <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>
         @endif
         <div class="row border-light">
            <div class="col-sm-6 form-group">
               <label>Attribute Name</label>
               <input type="text" name="name" value="{{isset($EditAttr->name)?$EditAttr->name:''}}" class="form-control" required="">
               <input type="hidden" name="id" value="{{isset($EditAttr->id)?$EditAttr->id:''}}" class="form-control" required="">
            </div>
            <div class="col-sm-6 form-group">
               <label>Display Attribute Name</label>
               <input type="text" name="display_name" value="{{isset($EditAttr->display_name)?$EditAttr->display_name:''}}" class="form-control" required="">
            </div>
            {{-- 
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label mb-10">Apply On Product Category <span class=" text-danger">*</span>
                  <input type="checkbox" id="chk" value="1"> Select All
                  </label>
                  <select name="catry[]"  class="form-control select2 chkSel" multiple="">
                     <option>{{ isset($product->category['name'])&& $product->category['name']=="No Parent" ? $product->category['name']:'' }}</option>
                     @isset($categ)
                     @foreach($categ as $item)
                     <option value="{{ $item->id }}">{{ Str::ucfirst($item->name)}}</option>
                     @endforeach
                     @endisset
                  </select>
               </div>
            </div>
            --}}
            {{-- 
            <div class="col-sm-4 form-group ">
               <label>Will apply on product list page? <label>&nbsp;&nbsp;<br>
               <input type="radio" name="single" value="1"  required > Yes &nbsp;&nbsp;
               <input type="radio" name="single" value="0" required > No &nbsp;&nbsp;
            </div>
            --}}
            {{-- 
            <div class="col-sm-6 form-group ">
               <label>Optional Extra Attribute for Style Customization (Remove Pocket etc) <label>&nbsp;&nbsp;<br>
               <input type="radio" name="opt" value="1" required> Yes &nbsp;&nbsp;
               <input type="radio" name="opt" value="0" required> No &nbsp;&nbsp;
            </div>
            --}}
            <div class="col-sm-12 form-group text-right">
               <button class="btn btn-primary btn-sm">Add & Update</button>
            </div>
         </div>
      </form>
      @endcan<br>
   </div> -->
</div>
<h4>Attributes List</h4>
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
                           <th>Attributes</th>
                           {{-- 
                           <th>Categories</th>
                           --}}
                           {{-- 
                           <th>Parent Attributes</th>
                           --}}
                           <th>Display name</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="c">
                        @isset($attribute)
                        <?php $i = 0; ?>
                        @foreach ($attribute as $item)
                        <tr id='{{ $item->id }}'>
                          <td>{{$item->id}}</td>
                           <td>{{ Str::ucfirst($item->name) }}</td>
                           {{-- 
                           <td>
                              @if(isset($item->category))
                              @foreach($item->category as $cat)
                              {{$cat->name['name']}},
                              @endforeach
                              @endif
                           </td>
                           --}}
                           <td>{{ Str::ucfirst($item->display_name) }}</td>
                           <td>
                              @if ($item->status == 1)
                            <span class="chip green lighten-5">
                      <span class="green-text">Active</span>
                </span>
                           </td>
                           @else
                           <button class="btn btn-danger btn-xs edit btn-rounded">De-active</button></td>
                           @endisset
                           <td>
                              @can('attr_edit')
                            
                              @if($item->id== '2')
                              <a class="btn btn-xs btn-info " href="{{ route("dashboard.attribute-value.index") }}">
                              <i class="fas fa-plus-circle"></i>
                              </a>
                              @endif
                              @endcan
                              @can('attr_delete')
                              {{--<a href="javascript:void(0)" class="btn btn-xs btn-danger delattribute"><i class="fas fa-trash-alt"></i></a>--}}
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
   $(document).on('change', '#chk', function(event) {
       let v = $(this).val();
   
       if (v) {
           $("#chkSel option").attr('selected', 'selected');
       } else {
           $("#chkSel option").removeAttr('selected')
       }
   });
</script>
<script type="text/javascript">
   $(".delattribute").click(function(event) {
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
                   url: "{{ url('admin/attribute') }}/" + id,
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