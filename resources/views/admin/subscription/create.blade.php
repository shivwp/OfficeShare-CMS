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

    <div class="card-body">
        <form action="{{ route("dashboard.membership.store") }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Membership Name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ isset($edmem->name)?$edmem->name:'' }}" required>
                <input type="hidden" name="id" class="form-control" value="{{ isset($edmem->id)?$edmem->id:'' }}" required>

            </div>

            <div class="form-group">
                <label>Membership Type</label>
                <select class="form-control" name="memtype">
                    <option {{ isset($edmem->subscribstion_type) && $edmem->subscribstion_type=="monthly"?"selected":'' }}>Monthly</option>
                    <option {{ isset($edmem->subscribstion_type) && $edmem->subscribstion_type=="yearly quarterly"?"selected":'' }}>Yearly Quarterly</option>
                    <option {{ isset($edmem->subscribstion_type) && $edmem->subscribstion_type=="half yearly"?"selected":'' }}>Half Yearly</option>
                </select>
            </div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Membership Price</label>
                <input type="text" id="price" name="price" class="form-control" value="{{ isset($edmem->price)?$edmem->price:'' }}" required>
            </div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Plateform Charge</label>
                <input type="text" id="pch" name="pch" class="form-control" value="{{ isset($edmem->plateform_charges)?$edmem->plateform_charges:'' }}" required>
            </div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Number of Property</label>
                <input type="text" id="pch" name="noofproperty" class="form-control" value="{{ isset($edmem->noofproperty)?$edmem->noofproperty:'' }}" required>
            </div>

            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Description</label>
                <textarea style="height:400px !important;" class="editor1 form-control" name="content">{{isset($edmem->description)?$edmem->description:'' }}</textarea>
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Features(Write all features in next line)</label>
                <textarea style="height:400px !important;" class="editor1 form-control" name="features">{{isset($edmem->features)?$edmem->features:'' }}</textarea>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ isset($edmem->slug)?$edmem->slug:'' }}" required="">
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Meta Title</label>
                <input type="text" id="name" name="meta_title" class="form-control" value="{{isset($edmem->meta_title)?$edmem->meta_title:'' }}">
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Meta Keyword</label>
                <input type="text" id="name" name="meta_key" class="form-control" value="{{isset($edmem->meta_keyword)?$edmem->meta_keyword:'' }}">
            </div>

            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
                <label for="email">Meta Description</label>
                <textarea class=" form-control" name="meta_description">{{isset($edmem->meta_description)?$edmem->meta_description:'' }}</textarea>
            </div>


            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
@endsection