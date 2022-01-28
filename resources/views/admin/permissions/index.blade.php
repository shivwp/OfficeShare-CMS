@extends('layouts.admin')
@section('content')
<div class="row">
   <div class="col-sm-6 pt-2">
      <h4>
         {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
      </h4>
   </div>

     <div class="col-sm-6 text-right ">
      @can('permission_create')
      <a class="addnew-btn" href="{{ route("dashboard.permissions.create") }}">
      {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
      </a>
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
                           <th>
                              #
                           </th>
                           <th>
                              {{ trans('cruds.permission.fields.title') }}
                           </th>
                           
                           <th>
                              Action
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($permissions as $key => $permission)
                        <tr id="{{ $permission->id }}">
                           <td>
                              {{ $permission->id ?? '' }}
                           </td>
                           <td>
                              {{ $permission->title ?? '' }}
                           </td>
                           <td>
                
                               @can('permission_edit')
                              <a class="btn btn-xs btn-info" href="{{ route('dashboard.permissions.edit', $permission->id) }}"><i class="far fa-edit"></i></a>
                              @endcan
                              @can('permission_show')
                            
                                 <a class="btn btn-xs btn-info" title="Spaces"href="{{ route('dashboard.permissions.show', $permission->id) }}"><i class="material-icons">remove_red_eye</i></a>
                              
                              @endcan
                             
                              @can('permission_delete')
                              <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a> 
                              @endcan
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pages">
                      {!! $permissions->links() !!}
                 </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<style type="text/css">
  /*.card .card-content {
    padding: 5px;
    border-radius: 0 0 2px 2px;
    }*/
    .card .card-content {
    padding: 10px;
    border-radius: 0 0 2px 2px;
}
 
</style>
@endsection
@section('scripts')
@parent
<script>
   $(function() {
       let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
       @can('permission_delete')
       let deleteButtonTrans = '{{ trans('
       global.datatables.delete ') }}'
       let deleteButton = {
           text: deleteButtonTrans,
           url: "{{ route('dashboard.permissions.massDestroy') }}",
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
       $('.datatable-Permission:not(.ajaxTable)').DataTable({
           buttons: dtButtons
       })
       $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
           $($.fn.dataTable.tables(true)).DataTable()
               .columns.adjust();
       });
   })
</script>
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
          text: "You won't be able to revert this item!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ url('dashboard/permissions') }}/" + id,
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