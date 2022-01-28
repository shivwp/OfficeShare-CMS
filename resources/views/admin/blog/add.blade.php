@extends('layouts.admin')
@section('content')

        <h4>
            {{ $title }}
        </h4>
  
<div class="card">

    <div class="card-body"id="add_space">
        <form action="{{ route("dashboard.blog.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('cat') ? 'has-error' : '' }}">
                <label for="name">Category</label>
                <select class="form-control" name="cat" required="">
                    @if(isset($blogcat))
                    @foreach($blogcat as $item)
                    <option value="{{ $item->id }}" {{ isset($edblog->blog_category) && $edblog->blog_category==$item->id?"selected":'' }}>{{ $item->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group {{ $errors->has('viewtype') ? 'has-error' : '' }}">
                <label for="viewtype">View Type</label>
                <select class="form-control" name="viewtype" required="">
                    <option value="web" {{isset($edblog) && $edblog->blog_view_type=="web"?"checked":''}}>Web</option>
                    <option value="mobile" {{isset($edblog) && $edblog->blog_view_type=="mobile"?"checked":''}}>Mobile</option> 
                </select>
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="title" value="{{isset($edblog->title)?$edblog->title:''}}">
            </div>
             <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                <label for="name">Video Url</label>
                <input type="text" class="form-control" name="video_url" value="{{isset($edblog->video_url)?$edblog->video_url:''}}">
            </div>
         
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Upload Featured Image</label>
                @if(isset($edblog->media))
                <img src="{{url(''.$edblog->media)}}" style="height:100px;width:100px;">
                @endif
                <input type="file" name="media" class="form-control">
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Upload App Image</label>
                  @if(isset($edblog->app_image))
                <img src="{{url(''.$edblog->app_image)}}" style="height:100px;width:100px;">
                @endif
                <input type="file" name="app_image" class="form-control">
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Short Description</label>
                <textarea style="height:200px !important;" class=" form-control" name="sd">{{isset($edblog->short_description)?$edblog->short_description:''}}</textarea>
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Full Description</label>
                <textarea style="height:400px !important;" class="editor1 form-control" id="editor11" name="fd">{{isset($edblog->description)?$edblog->description:''}}</textarea>
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="name">Status</label>
                <select class="form-control" name="st" required>
                   <option value="">Select</option>
                   <option value="0" {{ isset($edblog->status) && ($edblog->status == 0 ) ?"selected":'' }}>Pending</option>
                   <option value="1" {{ isset($edblog->status) && ($edblog->status == 1 ) ?"selected":'' }}>Publish</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Meta Title</label>
                <input type="text" id="name" name="meta_title" class="form-control" value="{{isset($edblog->meta_title)?$edblog->meta_title:''}}">
                <input type="hidden" value="{{isset($edblog->id)?$edblog->id:''}}" name="id">
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Meta Keyword</label>
                <textarea class=" form-control" name="meta_keyword">{{isset($edblog->meta_keyword)?$edblog->meta_keyword:''}}</textarea>
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Meta Description</label>
                <textarea class=" form-control" name="meta_description">{{isset($edblog->meta_description)?$edblog->meta_description:''}}</textarea>
            </div>
          
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
@endsection