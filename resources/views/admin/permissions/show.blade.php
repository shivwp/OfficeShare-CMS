@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h4 >
            {{ trans('global.show') }} {{ trans('cruds.permission.title') }}
        </h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="m-4">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.id') }}
                        </th>
                         <th>
                            {{ trans('cruds.permission.fields.title') }}
                        </th>
                        
                    </tr>
                    <tr>
                       
                        <td>
                            {{ $permission->id }}
                        </td>
                        <td>
                            {{ $permission->title }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn submit-btn" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        {{--<nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>--}}
        <div class="tab-content">

        </div>
    </div>
</div>

@endsection
