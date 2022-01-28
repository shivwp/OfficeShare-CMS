@extends('layouts.admin')
@section('content')

        <h4>
            {{ $title }}
        </h4>
  
<div class="card">

    <div class="card-body"id="add_space">
        <h4 style="margin-top: 0;">Home Page</h4>
        <form action="{{ route('dashboard.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                {{-- <label for="name">Home Page</label> --}}
                <input type="hidden" id="name" name="name" class="form-control" value="{{ isset($page->title)?$page->name:'' }}" required>
                <input type="hidden" id="name" name="pid" class="form-control" value="{{ isset($page->id)?$page->id:'' }}" required>
            </div>
            <div class="form-group">
                <label>Page Title</label>
                <input type="text" name="title" class="form-control" value="{{isset($page->title)?$page->title:'' }}">
            </div>
            {{-- @dd($page->section) --}}
            <div class="form-group {{$errors->has('content')?'has-error' : '' }}">
                <label for="email">Top Contents*</label>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Left Part Heading*</label>
                        <br>
                        <label for=""><small>Heading Title</small></label>
                        <input type="text" placeholder="Heading Title" name="left_heading_title" value="{{isset($page->meta_value['left_heading_title'])?$page->meta_value['left_heading_title']:'' }}" style="padding:5px;"><br><br>
                        <label for=""><small>Heading Description</small></label>
                        <textarea placeholder="Heading Description" style="height:150px !important;" name="left_heading_desc" class=" form-control">{{isset($page->meta_value['left_heading_desc'])?$page->meta_value['left_heading_desc']:'' }}</textarea><br><br>
                        <label for=""><small>Background Image</small></label>
                        <input type="file" name="left_bg_image">
                        @if(isset($page->meta_value['left_bg_image']))
                        
                            <img src="{{url('images/homePage/'.$page->meta_value['left_bg_image'])}}" width="200">
                        @endif
                        <br><br>
                        <label for=""><small>Video URl</small></label>
                        <textarea placeholder="Video Url" style="height:150px !important;" name="left_video_url" class="form-control">{{isset($page->meta_value['left_video_url'])?$page->meta_value['left_video_url']:'' }}</textarea>
                        <br><br>
                        <label for=""><small>Heading Title 2</small></label>
                        <input type="text" placeholder="Heading Title 2" name="left_heading_title2" value="{{isset($page->meta_value['left_heading_title2'])?$page->meta_value['left_heading_title2']:'' }}" style="padding:5px;"><br><br>
                        <label for=""><small>Heading Short Description</small></label>
                        <textarea placeholder="Heading Short Description " style="height:150px !important;" name="left_heading_short_desc" class=" form-control">{{isset($page->meta_value['left_heading_short_desc'])?$page->meta_value['left_heading_short_desc']:'' }}</textarea><br>
                        <br>
                        <label for=""><small>Heading Description 2</small></label>
                        <textarea placeholder="Heading Description 2" style="height:150px !important;" name="left_heading_desc2" class=" form-control">{{isset($page->meta_value['left_heading_desc2'])?$page->meta_value['left_heading_desc2']:'' }}</textarea>

                    </div>

                    <div class="col-md-6">
                        <label for="">Right Part Heading*</label>
                        <br>
                        <label for=""><small>Filter Title</small></label>
                        <input type="text" placeholder="Filter Title" name="right_filter_title" value="{{isset($page->meta_value['right_filter_title'])?$page->meta_value['right_filter_title']:'' }}" style="padding:5px;">
                        <br>
                        <br>
                        <label for=""><small>Filter Title</small></label>
                        <input type="text" placeholder="Heading Title" name="right_heading_title" value="{{isset($page->meta_value['right_heading_title'])?$page->meta_value['right_heading_title']:'' }}" style="padding:5px;">
                        <br>
                        <br>
                        <textarea placeholder="Heading Description" style="height:150px !important;" name="right_heading_desc" class=" form-control">{{isset($page->meta_value['right_heading_desc'])?$page->meta_value['right_heading_desc']:'' }}</textarea>
                        <br>
                        <br>
                        <label for=""><small>Background Image</small></label>
                        <input type="file" name="right_bg_image">
                        
                        @if(isset($page->meta_value['right_bg_image']))
                            <img src="{{url('images/homePage/'.$page->meta_value['right_bg_image'])}}" width="200">
                        @endif
                        <br>
                        <br>
                        <label for=""><small>App Download Title</small></label>
                        <input type="text" placeholder="Title" name="right_app_title" value="{{isset($page->meta_value['right_app_title'])?$page->meta_value['right_app_title']:'' }}" style="padding:5px;">
                        <br>
                        <br>
                        <textarea placeholder="App Downloads" style="height:150px !important;" name="right_app_downloads" class=" form-control">{{isset($page->meta_value['right_app_downloads'])?$page->meta_value['right_app_downloads']:'' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group {{$errors->has('content')?'has-error' : '' }}">
                <label for="email">Middle Contents*</label>
                <label for=""><small>Number of Blogs</small></label>
                <input type="text" style="padding:5px;" name="number_of_blogs" value="{{isset($page->meta_value['number_of_blogs'])?$page->meta_value['number_of_blogs']:'' }}">
            </div>
            {{-- 
            <div class="form-group {{$errors->has('content')?'has-error' : '' }}">
                <label for="email">Bottom Contents*</label>
                <textarea style="height:300px !important;" class="form-control" id="3" name="content[bottom][]">{{isset($page->meta_value->bottom[0])?$page->meta_value->bottom[0]:'' }}</textarea>
            </div> --}}
            {{-- 
                <div class="form-group">
                    <label>Slug</label>
                    <input type="hidden" name="slug" class="form-control" value="{{ isset($page->slugs)?$page->slugs:'' }}" required="">
                </div> 
            --}}
            <input type="hidden" name="slug" class="form-control" value="{{ isset($page->slugs)?$page->slugs:'' }}" required="">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Meta Title</label>
                <input type="text" id="name" name="meta_title" class="form-control" value="{{isset($page->meta_title)?$page->meta_title:'' }}">
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Meta Keyword</label>
                <textarea class=" form-control" name="meta_keyword">{{isset($page->meta_keyword)?$page->meta_keyword:'' }}</textarea>
            </div>
          
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>


@endsection

@section('scripts')
    

@endsection