@extends('layouts.admin')
@section('content')

        <h4>
            {{ $title }}
        </h4>
   
<div class="card">

    <div class="card-body" id="add_space">
        <form action="{{ route("dashboard.plan-feature.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
              <input type="hidden" value="{{isset($planfeature->id)?$planfeature->id:''}}" name="id">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="title" value="{{isset($planfeature->title)?$planfeature->title:''}}" required>
            </div>
            <div class="form-group {{ $errors->has('features_order') ? 'has-error' : '' }}">
                <label for="features_order">Feature Order</label>
                <input type="number" class="form-control" name="features_order" value="{{isset($planfeature->features_order)?$planfeature->features_order:''}}" required>
            </div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
            </div>
        </form>
    </div>
</div>
@endsection