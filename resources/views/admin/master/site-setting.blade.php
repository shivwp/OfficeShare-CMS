@extends('layouts.admin')

@section('content')
<h4>{{$title}}</h4>
<div class="card">
  <div class="card-body" id="add_space">
    <form action="{{ route("dashboard.setting.store") }}" method="post" enctype="multipart/form-data">
      @csrf
      <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-info-outline "></i>About Business</h6>
      <hr class="light-grey-hr" />
      <div class="row">
             <div class="col-md-4">
          <div class="form-group">
            <img src="{{ isset($setting[11]->value)?url($setting[11]->value):'https://image.shutterstock.com/image-vector/shield-letter-s-logosafesecureprotection-logomodern-260nw-633031571.jpg'}}" style="height:100px;width:200px;" alt="logo">
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group">
            <label class="control-label ">Business logo </label>
            <input type="hidden" name="name_11" value="Business logo">
            <input type="file" class="form-control" id="exampleInputuname_1" name="value_11">
            <input type="hidden" name="id_11" value="{{ isset($setting[11]->id)?$setting[11]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="hidden" name="name_0" value="Business Name">
            <label class="control-label ">Business Name <span class=" text-danger">*</span></label>
            <input type="text" name="value_0" class="form-control" value="{{isset($setting[0]->value)?$setting[0]->value:''}}">
            <input type="hidden" name="id_0" value="{{ isset($setting[0]->id)?$setting[0]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Country <span class=" text-danger">*</span></label>
            <input type="hidden" name="name_1" value="Country">
            <select name="value_1" class="form-control countries">
              <option value="{{isset($setting[1]->value)?$setting[1]->value:''}}">
                {{isset($setting[1]->value)?$setting[1]->value:"Select Country"}}
              </option>
              @isset($country)
              @foreach($country as $ct)
              <option value="{{ $ct->name }}">{{ $ct->name }}</option>
              @endforeach
              @endisset
            </select>
            <input type="hidden" name="id_1" value="{{ isset($setting[1]->id)?$setting[1]->id:""}}">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">State <span class=" text-danger">*</span></label>
            <!--<select name="state" class="form-control state">
  <option value="{{isset($st)?$st->id:''}}">{{isset($st)?$st->name:""}}</option>  
  </select>-->
            <input type="hidden" name="name_2" value="State">
            <input type="text" name="value_2" class="form-control " value="{{isset($setting[2]->value)?$setting[2]->value:""}}">
            <input type="hidden" name="id_2" value="{{ isset($setting[2]->id)?$setting[2]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="hidden" name="name_3" value="City">
            <label class="control-label ">City <span class=" text-danger">*</span></label>
            <!--<select name="city" class="form-control city">
  <option value="{{isset($city)?$city->id:''}}">{{isset($city)?$city->name:""}}</option>  
  </select>-->
            <input type="text" name="value_3" class="form-control " value="{{isset($setting[3]->value)?$setting[3]->value:""}}">
            <input type="hidden" name="id_3" value="{{ isset($setting[3]->id)?$setting[3]->id:""}}">
          </div>
        </div>

      
        <div class="col-md-6">
          <div class="form-group">
            <input type="hidden" name="name_5" value="Postcode">
            <label class="control-label ">Postcode <span class=" text-danger">*</span></label>
            <input type="number" class="form-control" placeholder="postcode" name="value_5" value="{{ isset($setting[5]->value)?$setting[5]->value:""}}">
            <input type="hidden" name="id_5" value="{{ isset($setting[5]->id)?$setting[5]->id:""}}">
          </div>
        </div>
        <!--/span-->
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Helpline Number </label>
            <input type="hidden" name="name_6" value="Helpline Number">
            <input type="number" class="form-control" id="exampleInputuname_1" placeholder="Helpline Number" name="value_6" value="{{ isset($setting[6]->value)?$setting[6]->value:""}}">
            <input type="hidden" name="id_6" value="{{ isset($setting[6]->id)?$setting[6]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">Email </label>
            <input type="hidden" name="name_7" value="Email">
            <input type="email" class="form-control" id="exampleInputuname_1" placeholder="Email" name="value_7" value="{{ isset($setting[7]->value)?$setting[7]->value:""}}">
            <input type="hidden" name="id_7" value="{{ isset($setting[7]->id)?$setting[7]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">PAN Number </label>
            <input type="hidden" name="name_8" value="PAN Number">
            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="PAN NUMBER" name="value_8" value="{{ isset($setting[8]->value)?$setting[8]->value:""}}">
            <input type="hidden" name="id_8" value="{{ isset($setting[8]->id)?$setting[8]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">CIN Number </label>
            <input type="hidden" name="name_9" value="CIN Number">
            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="CIN NUMBER" name="value_9" value="{{ isset($setting[9]->value)?$setting[9]->value:""}}">
            <input type="hidden" name="id_9" value="{{ isset($setting[9]->id)?$setting[9]->id:""}}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label ">GSTIN Number </label>
            <input type="hidden" name="name_10" value="GSTIN Number">
            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="GSTIN" name="value_10" value="{{ isset($setting[10]->value)?$setting[10]->value:""}}">
            <input type="hidden" name="id_10" value="{{ isset($setting[10]->id)?$setting[10]->id:""}}">
          </div>
        </div>
          <div class="col-md-12">
          <div class="form-group">
            <input type="hidden" name="name_4" value="Address">
            <label class="control-label ">Address <span class=" text-danger">*</span></label>
            <textarea name="value_4" class="form-control">{{ isset($setting[4]->value)?$setting[4]->value:""}}</textarea>
            <input type="hidden" name="id_4" value="{{ isset($setting[4]->id)?$setting[4]->id:""}}">
          </div>
        </div>
      
     
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label ">Site Url </label>
            <input type="hidden" name="name_12" value="Site Url">
            <input type="url" class="form-control" id="exampleInputuname_1" name="value_12" placeholder="www.example.com" value='{{ isset($setting[12]->value)?$setting[12]->value:""}}'>
            <input type="hidden" name="id_12" value="{{ isset($setting[12]->id)?$setting[12]->id:""}}">
          </div>
        </div>
      </div>
      <div class="seprator-block"></div>
      <div class="row ">
        <div class="col-sm-12">
          <label>Official Hour Type</label>
          <input type="hidden" name="name_13" value="Official Hour">
          <input type="hidden" name="id_13" value="{{ isset($setting[13]->id)?$setting[13]->id:""}}">
          <textarea class="form-control" name="value_13">{{ isset($setting[13]->value)?$setting[13]->value:""}}</textarea>
        </div>
      </div>

      <hr class="light-grey-hr" />
      <div class="row">
        <div class="col-sm-12">
          <h6>Mail Setting</h6>
        </div>
        {{-- <div class="col-sm-6">
   @if(isset($setting->mailtype) && $setting->mailtype=="sendmail")

    <input type="radio" name="mail" value="sendmail" class="form-check mail" checked=""> Send Mail

    @else

    <input type="radio" name="mail" value="sendmail" class="form-check mail" > SMTP

    @endisset

  </div> --}}

        {{-- <div class="col-sm-6">
   
    @if(isset($setting->mailtype) && $setting->mailtype=="smtp")

    <input type="radio" name="mail" value="smtp" class="form-check mail" checked=""> SMTP

    @else

         <input type="radio" name="mail" value="smtp" class="form-check mail" > Sendmail

        @endif 

  </div> --}}

      </div>
      @php $mail=isset($setting[14]->value)?json_decode($setting[14]->value,true):'' @endphp
    
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label ">SMTP HOST</label>
            <input type="hidden" name="id_14" value="{{ isset($setting[14]->id)?$setting[14]->id:""}}">
            <input type="hidden" name="name_14" value="Mail Info">
            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="smtp.gmail.com" name="host" value="{{ isset($mail['host'])?$mail['host']:""}}">
          </div>
        </div>



        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label ">PORT </label>
            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="port" name="port" value="{{ isset($mail['port'])?$mail['port']:""}}">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label ">Mail Encryption </label>
            <select name="encrypt" class="form-control">
              <option value=""></option>
              <option {{ isset($mail['encrypt'])?"selected":""}}>ssl</option>

              <option {{ isset($mail['encrypt'])?"selected":""}}>tls</option>

            </select>

          </div>

        </div>
        <div class="col-sm-6">

          <div class="form-group">

            <label class="control-label ">Sender Name </label>

            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="name" name="sname" value="{{ isset($mail['name'])?$mail['name']:""}}">

          </div>

        </div>

        <div class="col-sm-6">

          <div class="form-group">

            <label class="control-label ">Email id </label>

            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="email" name="semail" value="{{ isset($mail['email'])?$mail['email']:""}}">

          </div>

        </div>

        <div class="col-sm-6">

          <div class="form-group">

            <label class="control-label ">Password </label>

            <input type="password" class="form-control" id="exampleInputuname_1" placeholder="password" name="password" autocomplete="false">

          </div>

        </div>
                <div class="col-sm-12">
          <div class="form-group">
                   <input type="hidden" name="id_15" value="{{ isset($setting[15]->id)?$setting[15]->id:''}}">
               <input type="hidden" name="name_15" value="terms">
            <label class="control-label ">Terms & Conditions</label>
                          <textarea style="height:300px !important;" class="form-control editor1" name="value_15" value="{{ isset($setting[15]->value)?$setting[15]->value:''}}" id="editor1">{{ isset($setting[15]->value)?$setting[15]->value:""}}</textarea>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
               <input type="hidden" name="id_16" value="{{ isset($setting[16]->id)?$setting[16]->id:''}}">
            <input type="hidden" name="name_16" value="css">
           <label class="control-label ">Add Css Code</label>
                          <textarea style="height:300px !important;" class="form-control" name="value_16" value="{{ isset($setting[16]->value)?$setting[16]->value:''}}">{{ isset($setting[16]->value)?$setting[16]->value:""}}</textarea>
          </div>
        </div>

      </div>

       
    </div>

 


  <div class="form-actions" id="add_space">

    <button class="btn submit-btn  ">  Save & update</button>
  </div>

  </form>

</div>

</div>

@push('ajax-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.6.2/tinymce.min.js" integrity="sha512-sOO7yng64iQzv/uLE8sCEhca7yet+D6vPGDEdXCqit1elBUAJD1jYIYqz0ov9HMd/k30e4UVFAovmSG92E995A==" crossorigin="anonymous"></script>

  <script>
    CKEDITOR.replace('editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form', 
        height: '500px',
    }).config.allowedContent = true;
</script>
 <script>
    CKEDITOR.replace('editor2', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form', 
        height: '500px',
    }).config.allowedContent = true;
</script>
@endpush
@endsection