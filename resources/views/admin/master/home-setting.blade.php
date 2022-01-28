@extends('layouts.admin')

@section('content')
<!-- /Row -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-sm-6 pt-1">
        <h4>
          {{ $title }}
        </h4>
      </div>
      <div class="col-sm-6 text-right ">
        @can('office_create')
        <a class="btn btn-success btn-sm" href="{{ route('dashboard.home-setting.create') }}">
          {{ trans('global.add') }} Module
        </a>
        @endcan
      </div>
    </div>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <div class="card-body">
      <div class="table-responsive">
        @if(session('msg'))
        <p class="alert-success p-1 text-dark">{{ session('msg') }}</p>
        @endif
        <table class="table table-bordered" id="example">
          <thead>
            <tr>
              <th>Sr.</th>
              <th>Section Title</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody class="c">
            @if(!empty($homeSetting))
            <?php $i = 0; ?>
            @foreach($homeSetting as $item)

            @php $j=1 @endphp
            <tr id='{{ $item->id }}'>
              <td>{{ ++$i }}</td>

              <td>{{ Str::ucfirst($item->name) }}</td>

                <td>

              @can('blog_edit')

              <a class="btn btn-xs btn-info" href="{{ route('dashboard.home-setting.edit',$item->id) }}">

                <i class="far fa-edit"></i>

              </a>

              @endcan

              @can('blog_delete')

              <a href="javascript:void(0)" class="btn btn-xs btn-danger delblog"><i class="fas fa-trash-alt"></i></a>

              {{-- <a href="{{ route('dashboard.blog.show',$item->id) }}" class="btn btn-xs btn-primary" ><i class="fas fa-eye"></i></a> --}}

              @endcan

            </td>
            
            </tr>
            @endforeach

           
            @endif
          </tbody>
        </table>
      </div>
    </div>
    <div id="addCat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color:pink">
            <h5 class="modal-title">Add & Update Category</h5>
          </div>
          <form id="addcat" method="post">
            @csrf
            <div class="modal-body">
              <div class="form-group">
                <label for="recipient-name" class="control-label mb-10">Category name</label>
                <input type="text" class="form-control name" id="recipient-name " name="name" value="">
                <input type="hidden" name="id" id="id" value="">
              </div>
              <div class="form-group">
                <label for="message-text" class="control-label mb-10">Parent Category</label>
                <select name="pname" id="pc" class="form-control">
                  <option selected="" id="parent">No Parent</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default reload" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">+ Add & Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>

@push('ajax-script')
<script type="text/javascript">
  $(document).on('change', '#hour_type', function(event) {
    if ($(this).val() == "24 X 7 Opened") {
      $('.hideShow').hide();
    } else if ($(this).val() == "Weekly Opened") {
      $('.hideShow').show();
    }
  });
</script>

@endpush
@endsection