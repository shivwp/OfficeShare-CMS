@extends('layouts.admin')
@section('content')

<div class="card">
        <div class="card-header">
        <h4 >
            {{ trans('global.show') }} {{ trans('cruds.role.title') }}
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
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                      
                         <th>
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                         <th>
                            Permissions
                        </th>
                    </tr>
                    <tr>
                       <td>
                            {{ $role->id }}
                        </td>
                        <td>
                            {{ $role->title }}
                        </td>
                         
                        <td class="ddd">
                            @foreach($role->permissions as $id => $permissions)
                                <span class="label label-info label-many">{{ $permissions->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        
                        
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn submit-btn" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        
        <div class="tab-content">

        </div>
    </div>
</div>
<style type="text/css">
    td {
        padding: 20px !important;
    }
     td.ddd {
    text-align: justify;
    line-height: 30px;
}
</style>
@endsection
