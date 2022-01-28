@extends('layouts.admin')
@section('content')
<div class="row">
   <div class="col-sm-6">
      <h4>
         {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
      </h4>
   </div>
   <div class="col-sm-6 text-right">
      @can('user_create')
      <div style="margin-bottom: 10px;" class="row">
         <div class="col-lg-12">
            <a class="addnew-btn" href="{{ route("dashboard.users.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
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
                    <form action="{{route('dashboard.users.index')}}" method="get">
                      @csrf
                       <div class="search_bar">  
                          <button type="submit" class="serch_btn">Search</button>
                      </div>
                      <div class="search_bar mr-2">
                          <select class="form-control approvefilter" name="filter">
                            <option value="0">-Filter by Role-</option>
                             @foreach($role as $key => $item)
                             <option value="{{$item->id}}">{{$item->title}}</option>
                             @endforeach
                          </select>
                      </div>
                  </form>
                     <thead>
                        <tr>
                           <th>
                              #
                           </th>
                           <th>
                              {{ trans('cruds.user.fields.name') }}
                           </th>
                           <th>
                              {{ trans('cruds.user.fields.email') }}
                           </th>
                           <th>
                             Packages
                           </th>
                           <th>
                              {{ trans('cruds.user.fields.roles') }}
                           </th>
                           <th>
                              Action
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($users as $key => $user)
                        <tr id="{{ $user->id }}">
                           <td>
                              {{ $user->id ?? '' }}
                           </td>
                           <td>
                              {{ $user->name ?? '' }}
                           </td>
                           <td>
                              {{ $user->email ?? '' }}
                           </td>
                           <td>
                                @if($user->plan_title)
                                  <span>{{ $user->plan_title }}</span>
                                  <br>
                                  <span>{{ $user->validity }}</span>
                                  <br>
                                @else 
                                <span> - </span>
                                @endif
                             {{--<span>{{ $user->price}}</span>--}} 
                           </td>
                           <td>
                              @foreach($user->roles as $key => $item)
                            
                              <span class="chip green lighten-5">
                                  <span class="green-text">{{ $item->title }}</span>
                              </span>
                              @endforeach
                           </td>
                           <td>
                          @can('user_edit')
                          <a class="btn btn-xs btn-info" href="{{ route('dashboard.users.edit', $user->id) }}">
                            <i class="far fa-edit"></i>
                          </a>
                          @endcan
                        <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a>
                           @can('user_show')
                          <a class="btn btn-xs btn-info" title="Spaces" href="{{ route('dashboard.users.show', $user->id) }}">
                            <i class="material-icons">remove_red_eye</i>
                          </a>
                           @endcan
                              <!-- @can('user_delete')
                              <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                 <input type="hidden" name="_method" value="DELETE">
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                 <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                 <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog">
                            <i class="fas fa-trash-alt"></i>
                          </a>
                              </form>
                              @endcan -->
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                   <div class="pages">
              {!! $users->links() !!}
            </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
@parent
<script>
  {{-- $(function() {
       let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
       @can('user_delete')
       let deleteButtonTrans = '{{ trans('
       global.datatables.delete ') }}'
       let deleteButton = {
           text: deleteButtonTrans,
           url: "{{ route('dashboard.users.massDestroy') }}",
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
       $('.datatable-User:not(.ajaxTable)').DataTable({
           buttons: dtButtons
       })
       $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
           $($.fn.dataTable.tables(true)).DataTable()
               .columns.adjust();
       });
   }); --}}
    $(".approvestatus").click(function() {
      $('#approvestatus').submit();
   });
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
              url: "{{ url('dashboard/users') }}/" + id,
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