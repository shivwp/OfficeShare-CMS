<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ trans('panel.site_title') }}</title>
  <link rel="stylesheet" type="text/css" href="http://docs.ckeditor.com/#!/guide/dev_howtos_file_upload">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />

  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap');
  </style>
  <style type="text/css">
    .select2-selection--multiple {
      width: 100% !important;
    }

    .nav-link.active {
      border: 2px solid #fc6565 !important;
   
      box-shadow: 6px 4px 9px -2px rgba(0,0,0,0.75);
      background-color: #fc6565 !important;
      color: white !important;

    }

    button.dt-button,
    div.dt-button,
    a.dt-button {
      background-image: linear-gradient(to bottom, #FF5722 0%, #FF5722 100%) !important;
      color: #fff !important;
    }

    .couponTab>ul>li {
      width: 33.3%;
      background: #32D3A1;
      text-align: center;
      height: 35px;
      line-height: 35px;
      border-right: 2px #fff solid;
      font-size: 15px;
    }

    .couponTab>ul>li>a {
      color: #fff !important;
    }
  </style>
  @stack('style-content')
  @yield('styles')
  <script id="mcjs">
    ! function(c, h, i, m, p) {
      m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)
    }(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/5ebcad3d798d0558a224752d6/f654dced7aac355171b307d72.js");
  </script>

<style type="text/css">
.overlay {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  right: 0;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0, 0.9);
  overflow-x: hidden;
  transition: 0.5s;
}

.overlay-content {
  position: relative;
  top: 25%;
  width: 100%;
  text-align: center;
  margin-top: 30px;
}

.overlay a {
  padding: 8px;
  text-decoration: none;
  font-size: 36px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
  color: #f1f1f1;
}

 .closebtn {
  position: absolute;
  font-size: 60px;
  
}

@media screen and (max-height: 450px) {
  .overlay a {font-size: 20px}
  .overlay .closebtn {
  font-size: 40px;
  top: 15px;
  right: 35px;
  }
}
</style>
<script type="text/javascript">
  function openNav() {
  document.getElementById("myNav").style.width = "260px";
  jQuery (".nav-burger").css("display", "none");
  jQuery (".closebtn").css("display", "block");
  jQuery (".sidebar, .off-canvas-sidebar nav .navbar-collapse").css("display", "block");

}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
  jQuery (".closebtn").css("display", "none");
  jQuery (".nav-burger").css("display", "block");
  jQuery (".sidebar, .off-canvas-sidebar nav .navbar-collapse").css("display", "none");
}


  jQuery( window ).resize(function() {
    if(jQuery(this).width() > 992){
    
        jQuery (".wrapper .sidebar-wrapper").hide();

      

    }else if(jQuery(this).width() < 992){

        jQuery (".wrapper .sidebar-wrapper").show();
    }

  });
</script>
</head>

<body style="height: auto;">
  <div class="wrapper">
 
    <div class="main-panel">


<div  class="overlay">
  
  <div class="overlay-content">
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Clients</a>
    <a href="#">Contact</a>
  </div>
</div>
<div class="btn_grp">
<span class="nav-burger"  onclick="openNav()">&#9776; </span>
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()" style="display: none;">&times;</a>
 </div>


<div class="row border-do">
    <div class="col-md-3">
        
    </div>
     <div class="col-md-3 margin-do">
        <form action="{{ route('update-pass') }}" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{$userId}}">

         <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Enter password</label>
                <input type="text" class="form-control" name="pass" value="{{isset($edblog->title)?$edblog->title:''}}">
        </div>
         <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Confirm Password</label>
            <input type="text" class="form-control" name="pass_check" value="{{isset($edblog->title)?$edblog->title:''}}">
        </div>
        <button type="submit" class="btn btn-danger">Change & Update</button>
        </form>
        
    </div>
     <div class="col-md-3">
        
    </div>

       
</div>
<style type="text/css">
    .border-do{

    }
    .form-group input {
     background-color: #fafafa; 
    padding: 8px 10px;
}
.margin-do{

    margin-top: 200px;
}
</style>
   
         