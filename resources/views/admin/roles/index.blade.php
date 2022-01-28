@extends('layouts.admin')
@section('content')

      <div class="row">
         <div class="col-sm-6 pt-2">
            <h4>
               {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
            </h4>
         </div>
         <div class="col-sm-6 text-right">
            @can('role_create')
            <a class="addnew-btn" href="{{ route("dashboard.roles.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
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
                              {{ trans('cruds.role.fields.title') }}
                           </th>
                           <th>
                              {{ trans('cruds.role.fields.permissions') }}
                           </th>
                           <th>
                              Action
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($roles as $key => $role)
                        <tr id="{{ $role->id }}">
                           <td>
                              {{ $role->id ?? '' }}
                           </td>
                           <td>
                              {{ $role->title ?? '' }}
                           </td>
                           <td class="ddd">
                              @foreach($role->permissions as $key => $item)
                              <span class="badge badge-info">{{ $item->title }}</span>
                              @endforeach
                           </td>
                           <td>
                              @can('role_edit')
                              <a class="btn btn-xs btn-info" href="{{ route('dashboard.roles.edit', $role->id) }}">
                 <i class="far fa-edit"></i>
                </a>
                 @endcan
                  @can('role_delete')
                 <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog" href="{{ route('dashboard.roles.destroy', $role->id) }}"><i class="fas fa-trash-alt"></i></a>
                  @endcan
                 @can('role_show')
                 <a  class="btn btn-xs btn-info" href="{{ route('dashboard.roles.show', $role->id) }}" title="Spaces"><i class="material-icons">remove_red_eye</i></a>
                    @endcan
                  </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<style type="text/css">
  td.ddd {
    max-width: 100%;
    width: 75%;
    text-align: justify;
    line-height: 30px;
}
</style>
@endsection
@section('scripts')
@parent
<script>
   $(function() {
       let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
       @can('role_delete')
       let deleteButtonTrans = '{{ trans('
       global.datatables.delete ') }}'
       let deleteButton = {
           text: deleteButtonTrans,
           url: "{{ route('dashboard.roles.massDestroy') }}",
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
       $('.datatable-Role:not(.ajaxTable)').DataTable({
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
              url: "{{ url('dashboard/roles') }}/" + id,
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