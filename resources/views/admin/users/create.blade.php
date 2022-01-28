@extends('layouts.admin')
@section('content')

<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered {
    padding: 8px;
}
.select2-container .select2-selection--single {
    height: 45px !important;
}
span.select2-search.select2-search--dropdown {
    display: none;
}
span.select2-selection__arrow {
    display: none !important;
}
.alert.alert-danger {
    background-color: #f55145;
    color: #ffffff;
    margin: 10px;
    padding-top: 9px;
    padding-bottom: 1px;
    text-align: center;
}
</style>    

<div class="card">
    <div class="card-body" id="add_space">
        <form action="{{ route("dashboard.users.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="name">First Name*</label>
                        <input type="hidden" name="first_name" value="abc">
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="name">Last Name*</label>
                            <input type="text" id="name" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="telephone">Phone*</label>
                        <input type="number" id="" name="mobile" class="form-control" value="{{ old('mobile') }}"  onblur="myFunction()" required>
                      
                    </div>
                    <div class="col-sm-6">
                         <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                       
                    </div>
                </div>
            </div>
            <div class="form-group">
                 <div class="row">
                    <div class="col-sm-6">
                        <label for="password">{{ trans('cruds.user.fields.password') }} *</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        
                    </div>
                    <div class="col-sm-6">
                        <label for="dob">DOB *</label>
                        <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob') }}" required>
                    </div>
            </div>
            <div class="form-group">
                <label for="zip">Postcode *</label>
                <input type="text" id="zip" name="zip" class="form-control" value="{{ old('zip') }}" required>
            </div>
            <div class="form-group pb-0">
                <label for="roles">{{ trans('cruds.user.fields.roles') }}*
                <select class="max-length browser-default user_type" name="roles[]" id="roles" required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                <p class="help-block">
                    {{ $errors->first('roles') }}
                </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>
            <div class="form-group mb-2 plan">
                <label for="plan">Packages</label>
                <select class="form-control max-length browser-default" name="plan">
                   <option value="">SELECT</option>
                    @foreach($plan as $key => $val)
                        <option value="{{$val->id}}">{{$val->title}}</option>

                    @endforeach
                </select>
            </div>
            <div>
                 <label for="Image">Profile Image</label>
                  <input type="file" class="form-control" name="image" id="files" required/>
            </div>
            <div>
                <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
<style type="text/css">
    .imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
  position: relative;
}
/*.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
}*/

.remove {
    /* display: block; */
    background: #444;
    /* border: 1px solid black; */
    color: white;
    /* text-align: center; */
    cursor: pointer;
    position: absolute;
    right: 3px;
    top: 3px;
}
.remove1 {
 /* display: block;*/
  color: #fc6565;
  /*text-align: center;*/
  cursor: pointer;
   position: absolute;
}
</style>
@push('ajax-script')
<script type="text/javascript">
    var input = document.querySelector("#telephone");
      intlTelInput(input, {
      utilsScript: "js/utils.js"
    });    
</script>
<script type="text/javascript">
    function myFunction() {
        var title = $(".iti__selected-flag").attr("title");
        var strArray = title.split(":");
        $('#code').val(strArray[1].trim());
    }
</script>
<script type="text/javascript">
  $(document).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
      $("#files").on("change", function(e) {
        var files = e.target.files,
          filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();
          fileReader.onload = (function(e) {
            var file = e.target;
            $("<span class=\"pip1\">" +
              "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
              "<i class=\"fas fa-window-close remove1\"></i>"+
              "</span>").insertAfter("#files");
            $(".remove1").click(function(){
              $(this).parent(".pip1").remove();
              $('#files').val(""); 

            });
            
          });
          fileReader.readAsDataURL(f);
        }
      });
    } else {
      alert("Your browser doesn't support to File API")
    }


    $(function(){
        $('.plan').hide();
      // turn the element to select2 select style
      $('#roles').select2({
        // placeholder: "Select a state"
      }).on('change', function(e) {
        var data = $("#roles option:selected").text();
        // alert(data);
        if(data == 'Landlord') {
            $('.plan').show();
        } else {
            $('.plan').hide();
        }
      });

    });

    
  });
</script>
@endpush
@endsection