@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header ">
        <h4>
            {{ $title }}
        </h4>
    </div>
</div>
<div class="card">

    <div class="card-body" id="add_space">
        <form action="{{ route("dashboard.home-setting.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
             <input type="hidden" value="{{isset($homeSetting->id)?$homeSetting->id:''}}" name="id">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="title" value="{{isset($homeSetting->  name)?$homeSetting->name:''}}">
            </div>
          
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Full Description</label>
                <textarea style="height:400px !important;" class="editor1 form-control" name="content">{{isset($homeSetting->contents)?$homeSetting->contents:''}}</textarea>
            </div>
        
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
@endsection