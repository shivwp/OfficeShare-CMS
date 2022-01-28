@extends('layouts.admin')
@section('content')

        <h4>
            {{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}
        </h4>
  
<div class="card">

    <div class="card-body" id="add_space">
        <form action="{{ route("dashboard.roles.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.role.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($role) ? $role->title : '') }}" required>
                @if($errors->has('title'))
                <p class="help-block">
                    {{ $errors->first('title') }}
                </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.role.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                <label for="permissions">{{ trans('cruds.role.fields.permissions') }}*</label>
           
                               
                        <select class="max-length browser-default " name="permissions[]" id="permissions" multiple="multiple" required>
                         @foreach($permissions as $id => $permissions)
                    <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($role) && $role->permissions->contains($id)) ? 'selected' : '' }}>{{ $permissions }}</option>
                    @endforeach
                        </select>
               
            </div>
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }}">
     
            </div>
        </form>
    </div>
</div>
@endsection