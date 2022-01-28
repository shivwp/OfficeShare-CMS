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
        <form action="{{ route("dashboard.plan.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
             <input type="hidden" value="{{isset($Plan->id)?$Plan->id:''}}" name="id">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="title" value="{{isset($Plan->title)?$Plan->title:''}}" required>
            </div>
             <div class="input-field">
                 <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">Select Feature</label>
                     <select name="features[]" id="permissions" class="form-control select2 max-length browser-default" multiple="multiple" required>
                        @foreach($feature as $id => $features)
                        <option value="{{ $id }}" >{{ $features }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group {{$errors->has('description')?'has-error' : '' }}">
               <label for="email">Description</label>
                <textarea style="height:400px !important;" class="editor1 form-control" name="description" id="editor1" required="">{{isset($Plan->description)?$Plan->description:''}}</textarea>
                @if($errors->has('description'))
                <p class="help-block">
                    {{ $errors->first('description') }}
                </p>
                @endif
            </div>
               <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="name">Price</label>
                <input type="number" class="form-control" name="price" value="{{isset($Plan->price)?$Plan->price:''}}" required>
            </div>
             <div class="form-group {{ $errors->has('validity') ? 'has-error' : '' }}">
                <label for="name">Validity</label>
                  <select name="valid" class="form-control" required>
                    <option value="">Select</option>
                    <option value="15 Day">15 Days</option>
                    <option value="1 Month">1 Month</option>
                    <option value="3 Month">3 Month</option>
                    <option value="6 Month">6 Month</option>
                    <option value="12 Month">12 Month</option>
                    <option value="1 Year">1 Year</option>
                    <option value="2 Year">2 Year</option>
                  </select>
            </div>
          
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
<style>
    .alert.alert-danger {
    background-color: #f55145;
    color: #ffffff;
    margin: 10px;
    padding: 1px;
    text-align: center;
}

</style>

@endsection