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

    <div class="card-body"id="add_space">
        <form action="{{ route("dashboard.notifications.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{isset($notification->id)?$notification->id:''}}">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Title</label>
                <input type="text" required class="form-control" name="title" value="{{isset($notification->title)?$notification->title:''}}">
            </div>
              <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Body</label>
               <textarea class="form-control" required name="body" value="value="{{isset($notification->body)?$notification->body:''}}">{{isset($notification->body)?$notification->body:''}}</textarea>
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Upload Image</label>
                <input type="file" name="media" class="form-control" >
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
@endsection