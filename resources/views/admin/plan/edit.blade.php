@extends('layouts.admin')
@section('content')

        <h4>
            {{ $title }}
        </h4>
    
<div class="card">

    <div class="card-body"id="add_space">
        <form action="{{ route("dashboard.plan.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
             <input type="hidden" value="{{isset($Plan->id)?$Plan->id:''}}" name="id">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="title" value="{{isset($Plan->title)?$Plan->title:''}}">
            </div>
             <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Select Feature</label>
            
             <select class="max-length browser-default " name="attribute[]" multiple="multiple" id="max_length">
                       @foreach($feature as $id => $features)
                        <option value="{{ $id }}" 
            {{ (in_array($id, old('features', [])) || isset($Plan) && $Plan->features->contains($id)) ? 'selected' : '' }} >{{ $features }}</option> @endforeach
                        </select>
            </div>
            <div class="form-group {{$errors->has('email')?'has-error' : '' }}">
               <label for="email">Description</label>
                <textarea style="height:400px !important;" class="editor1 form-control" name="description" id="editor1">{{isset($Plan->description)?$Plan->description:''}}</textarea>
            </div>
               <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="name">Price</label>
                <input type="number" class="form-control" name="price" value="{{isset($Plan->price)?$Plan->price:''}}">
            </div>
             <div class="form-group {{ $errors->has('validity') ? 'has-error' : '' }}">
                <label for="name">Validity</label>
                <input type="text" class="form-control" name="validity" value="{{isset($Plan->validity)?$Plan->validity:''}}">
            </div>
             <div class="form-group {{ $errors->has('validity') ? 'has-error' : '' }}">
                <label for="name">Validity</label>
                  <select name="valid" class="form-control">
                    <option value="15 Day" {{isset($Plan->validity) && ($Plan->validity == '15 Day') ? 'selected' : ''}}>15 Days</option>
                    <option value="1 Month" {{isset($Plan->validity) && ($Plan->validity == '1 Month') ? 'selected' : ''}}>1 Month</option>
                    <option value="3 Month" {{isset($Plan->validity) && ($Plan->validity == '3 Month') ? 'selected' : ''}}>3 Month</option>
                    <option value="6 Month" {{isset($Plan->validity) && ($Plan->validity == '6 Month') ? 'selected' : ''}}>6 Month</option>
                    <option value="12 Month" {{isset($Plan->validity) && ($Plan->validity == '12 Month') ? 'selected' : ''}}>12 Month</option>
                    <option value="1 Year" {{isset($Plan->validity) && ($Plan->validity == '1 Year') ? 'selected' : ''}}>1 Year</option>
                    <option value="2 Year" {{isset($Plan->validity) && ($Plan->validity == '2 Year') ? 'selected' : ''}}>2 Year</option>
                  </select>
            </div>
            
           
          
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>

@endsection