@extends('layouts.admin')
@section('content')


        <h4 >
            {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        </h4>
 
<div class="row">
    <div class="col s12" id="s1">
      <div class="card">
        <div class="card-content">
          <div class="row">
            <div class="col s12">
              <table  id="page-length-option" class="display">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                         <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        
                        <th>
                            Roles
                        </th>
                    </tr>
                    <tr>
                       <td>
                            {{ $user->id }}
                        </td>
                        <td>
                            {{ $user->name }}
                        </td>
                         <td>
                            {{ $user->email }}
                        </td>
                        
                         <td>
                            @foreach($user->roles as $id => $roles)
                             
                                <span class="chip green lighten-5">
                      <span class="green-text">{{ $roles->title }}</span>
                </span>
                            @endforeach
                        </td>
                    </tr>
                   
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn submit-btn" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
         </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
