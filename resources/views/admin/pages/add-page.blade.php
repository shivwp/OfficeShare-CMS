@extends('layouts.admin')
@section('content')

        <h4>
            {{ $title }}
        </h4>
  
<div class="card">

    <div class="card-body"id="add_space">
        <form action="{{ route('dashboard.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
           
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Page name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($page->title)?$page->name:'' }}" required>
                <input type="hidden" id="name" name="pid" class="form-control" value="{{ isset($page->id)?$page->id:'' }}" required>
            </div>
            <div class="form-group">
                <label>Page Title</label>
                <input type="text" name="title" class="form-control" value="{{isset($page->title)?$page->title:'' }}">
            </div>
            <div class="form-group {{$errors->has('content')?'has-error' : '' }}">
                <label for="email">Page Contents*</label>
                <textarea style="height:400px !important;" class="editor1 form-control" id="editor11" name="content">{{isset($page->content)?$page->content:'' }}</textarea>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ isset($page->slugs)?$page->slugs:'' }}" required="">
            </div>
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