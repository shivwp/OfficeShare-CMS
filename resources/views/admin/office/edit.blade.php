@extends('layouts.admin')
@section('content')
<h4>
   {{ $title }}
</h4>
<div class="card">
   <div class="card-body" id="add_space">
      <form action="{{ route('dashboard.office.update',$property->id) }}"  enctype="multipart/form-data" method="POST">
         @csrf
         @method('PUT')
         <input type="hidden" value="{{isset($property->id)?$property->id:''}}" name="id">
         <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="name">Title</label>
            <input type="text" class="form-control" name="title" value="{{isset($property->property_title)?$property->property_title:''}}" required>
         </div>


          @if(Auth::user()->roles[0]->title == "Admin")
           <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
              <label for="name">Select Landload</label>
               <select name="landload" id="lamdload" class="form-control" required>
                <option value="">--Select--</option>
                  @foreach($landload as $land => $land_user)
                  <option value="{{ $land_user->id }}" {{ ( $land_user->id == $property->user_id) ? 'selected' : '' }}>{{ $land_user->name }}</option>
                  @endforeach
              </select>
           </div>
           @endif
         {{--
         <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="name">Property Type</label>
            <select name="property_type[]" id="permissions" class="form-control select2" multiple="multiple" required>
               @foreach($space_type as $id => $type)
               <option value="{{ $id }}" >{{ $type }}</option>
               @endforeach
            </select>
         </div>
         --}}
        
         <div class="form-group {{$errors->has('description')?'has-error' : '' }}">
            <label for="email">Describe Your Space</label>
            <textarea style="height:200px !important;" class=" form-control" id="editor11" name="short_description" required>{{isset($property->short_description)?$property->short_description:''}}</textarea>
         </div>
     
         {{--
         <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
            <label for="name">Cost</label>
            <input type="number" class="form-control" name="cost" value="{{isset($property->title)?$property->title:''}}">
         </div>
         --}}
        <div class="form-group {{ $errors->has('thumbnail') ? 'has-error' : '' }}">
            <label for="name">Image</label>
            <img src="{{url('media/thumbnail/'.$property->thumbnail)}}" style="height:100px;width:100px; float: left; margin-right: 10px;" class="remove-old-img">
            <div class="files_insert" style="float: left;"></div>
            <input type="file" id="files" name="thumbnail" class="form-control" />

       </div>
         <div class="form-group {{ $errors->has('gallery_image') ? 'has-error' : '' }}">
            <label for="name">Gallery Image</label>
           
            @if(!empty(json_decode($property->gallary_image)))
              @foreach(json_decode($property->gallary_image) as $data)
                <span class="pip" data-title="{{$data}}">
                <img src="{{url('media/gallery/'.$data)}}" style="height:100px;width:100px;">
                <i class="fas fa-window-close remove" onclick="removeImage('{{$data}}')"></i>
                </span>
                {{-- <img src="{{url('media/gallery/'.$data)}}" style="height:100px;width:100px;"> --}}
              @endforeach
            @endif
            <input type ="hidden" name="gallery_img" id="gallery_img" value="{{$property->gallary_image}}">
            <input type="file" class="form-control" name="gallery_image[]" multiple="" id="gallery_image"/>
            
         </div>
           <div class="form-group {{ $errors->has('disability_access') ? 'has-error' : '' }}">
                <label for="name">Disability Access</label>
               <select class="form-control" name="disability_access" required=>
                 <option value="1" {{ ( isset($propertExtraDetails->disability_access) && $propertExtraDetails->disability_access == "1") ? 'selected' : '' }}>Yes</option>
                 <option value="0" {{ ( isset($propertExtraDetails->disability_access) && $propertExtraDetails->disability_access == "0") ? 'selected' : '' }}>No</option>
               </select>
            </div>
           
            <div class="form-group {{ $errors->has('how_to_find_us') ? 'has-error' : '' }}">
              <label for="name">About Sharer</label>
               <textarea class="form-control" name="how_to_find_us" value="" required>{{isset($propertExtraDetails->how_to_find_us)?$propertExtraDetails->how_to_find_us:''}}</textarea>
            </div>
             <div class="form-group {{ $errors->has('insurance') ? 'has-error' : '' }}">
              <label for="name">Insurance</label>
              <textarea class="form-control" name="insurance" value="" required>{{isset($propertExtraDetails->insurance)?$propertExtraDetails->insurance:''}}</textarea>
            </div>
            <div class="form-group {{ $errors->has('covid_19_secure') ? 'has-error' : '' }}">
              <label for="name">Covid 19 Secure</label>
                <textarea class="form-control" name="covid_19_secure" value="" required>{{isset($propertExtraDetails->covid_19_secure)?$propertExtraDetails->covid_19_secure:''}}</textarea>
            </div>
           
         <div class="row form-group">
            <div class="col-sm-12 mt-3">
              <div class="col-sm-12 mt-3">
                        <div id="map" style="width: 100%;height: 300px">
                       </div>
            </div>
               <div class="row form-group col-sm-12">
                          <input id="searchInput" class="form-control controls" type="text" placeholder="Type an address to find">
                       </div>

            <div class="col-sm-12 mt-4">
               <div class="row">
                  <div class="col-sm-4">
                     <label>Country</label>
                     <input type="text" name="country" id="country" value="{{isset($location->country)?$location->country:''}}" class="form-control">
                  </div>
                  <div class="col-sm-4">
                     <label>State</label>
                     <input type="text" name="state" id="state" value="{{isset($location->state)?$location->state:''}}" class="form-control">
                  </div>
                  <div class="col-sm-4">
                     <label>City</label>
                     <input type="text" name="city" id="city" value="{{isset($location->city)?$location->city:''}}" class="form-control">
                  </div>
                  <div class="col-sm-4">
                     <label>Postcode</label>
                     <input type="text" name="postcode" id="postcode"  value="{{isset($location->postcode)?$location->postcode:''}}" class="form-control" required>
                  </div>
                  <div class="col-sm-4">
                     <label>Langitude</label>
                     <input type="text" name="lang" id="lng" value="{{isset($location->longitude)?$location->longitude:''}}" class="form-control" required>
                  </div>
                  <div class="col-sm-4">
                     <label>Latitude</label>
                     <input type="text" name="lat" id="lat" value="{{isset($location->latitude)?$location->latitude:''}}" class="form-control" required>
                  </div>
                  <div class="col-sm-6">
                     <label>Address</label>
                     <textarea row="3"class="form-control" id="location" name="address" required>{{isset($location->address)?$location->address:''}}</textarea>
                  </div>
                  <div class="col-sm-6 mb-2">
                     <label>Select Amenities</label>
                     <div class="input-field">
                        <select class="max-length browser-default " name="attribute[]" multiple="multiple" id="max_length">
                        @if(isset($attributes))
                        @foreach($attributes as $item)
                        <option value="{{ $item->id }}" {{ in_array($item->id,$atr_id_data) ? "selected" : "" }}>{{ $item->value }}</option> @endforeach
                        @endif
                        </select>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div>
            <input class="btn submit-btn" type="submit" value="{{ trans('global.save') }} & Update">
         </div>
      </form>
   </div>
</div>
<style type="text/css">
  input#searchInput {
    margin-top: -7px;
    padding-top: 0px;
    border: none;
    width: 100%;
    padding: 5px 10px;
    box-sizing: border-box;
    border: 1px solid #9e9e9e;
}

input[type="file"] {
  display: block;
}
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
.remove {
 /* display: block;*/
  background: #444;
 /* border: 1px solid black;*/
  color: white;
  /*text-align: center;*/
  cursor: pointer;
   position: absolute;
    right: 3px;
    top: 3px;
}
.remove1 {
    background: #444;
    /* border: 1px solid black; */
    color: white;
    /* text-align: center; */
    cursor: pointer;
    position: absolute;
    right: 3px;
    top: 3px;
}
.pip1 {
  display: inline-block;
  margin: 10px 10px 0 0;
  position: relative;
}

.remove:hover {
  background: white;
  color: black;
}
</style>
@push('ajax-script')
<script type="text/javascript">
   // Select With Icon 
   $(".select2-icons").select2({
       dropdownAutoWidth: true,
       width: '100%',
       minimumResultsForSearch: Infinity,
       templateResult: iconFormat,
       templateSelection: iconFormat,
       escapeMarkup: function (es) { return es; }
   });
   
   // Format icon 
   function iconFormat(icon) {
       var originalOption = icon.element;
       if (!icon.id) { return icon.text; }
       var $icon = "" + $(icon.element).data('icon') + "" + icon.text;
   
       return $icon;
   }
   
       
   // Limiting the number of selections
   $(".max-length").select2({
       dropdownAutoWidth: true,
       width: '100%',
      
   });
   
     $(document).on('change','.country', function(event) {
   
     var id=$(this).val();
   
     $.get('{{ url("dashboard/get-state") }}/'+id, function(data) {
   
     var d=$.parseJSON(data);
   
     var option="<option value=''>Select State</option>";
   
     $.each(d, function(index, val) {
   
     option +='<option value="'+val.id+'">'+val.name+'</option>'
   
     });
   
     $(".state").html(option)
   
     });
   
     });
   
       $(document).on('change','.state', function(event) {
   
         var id=$(this).val();
     
         $.get('{{ url("dashboard/get-city") }}/'+id, function(data) {
     
           var d=$.parseJSON(data);
       
           var option="<option value=''>Select City</option>";
       
           $.each(d, function(index, val) {
       
            option +='<option value="'+val.id+'">'+val.name+'</option>'
       
           });
       
           $(".city").html(option);
     
         });
   
   
   
       });
   
</script>
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6bmqd_cBj6dI23_-QdXi9wrHZBeKcauc&callback=initMap&callback=initAutocomplete&libraries=places&v=weekly">
  </script>
<script>
/* script */
function initialize() {
       var latlng = new google.maps.LatLng(51.5073509,-0.1277583);
    var map = new google.maps.Map(document.getElementById('map'), {
      center: latlng,
      zoom: 13
    });
    var marker = new google.maps.Marker({
      map: map,
      position: latlng,
      draggable: true,
      anchorPoint: new google.maps.Point(0, -29)
   });
    var input = document.getElementById('searchInput');
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();   
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
       
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);          
        var results = place;
        var city = '';
        var state = '';
        var country='';
        var postcode = '';
        console.log(results);
        for (var k = 0; k < results.address_components.length; k++) {
            for (var j = 0; j < results.address_components[k].types.length; j++) {
                if (results.address_components[k].types[j] == "postal_code") {
                    var postcode =  results.address_components[k].long_name;
                }
                if (results.address_components[k].types[j] === "administrative_area_level_1") {
                    var state = results.address_components[k].long_name;
                }
                if (results.address_components[k].types[j] === "locality") {
                    var city = results.address_components[k].long_name;
                }
                if (results.address_components[k].types[j] === "country") {

                    var country = results.address_components[k].long_name;
                }
                
            }
        }

        document.getElementById('location').value = place.formatted_address;
        document.getElementById('lng').value = place.geometry.location.lng();
        document.getElementById('lat').value = place.geometry.location.lat();
        document.getElementById('city').value = city;
        document.getElementById('state').value = state;
        document.getElementById('country').value = country;
        document.getElementById('postcode').value = postcode;
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);
       
    });
    // this function will work on marker move event into map 
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {        
            var results = results[0];
            var city = '';
            var state = '';
            var country='';
            var postcode = '';
            for (var k = 0; k < results.address_components.length; k++) {
                for (var j = 0; j < results.address_components[k].types.length; j++) {
                    if (results.address_components[k].types[j] == "postal_code") {
                        var postcode =  results.address_components[k].long_name;
                    }
                    if (results.address_components[k].types[j] === "locality") {
                        var state = results.address_components[k].long_name;
                    }
                    if (results.address_components[k].types[j] === "sublocality_level_1") {
                        var city = results.address_components[k].long_name;
                    }
                    if (results.address_components[k].types[j] === "country") {

                        var country = results.address_components[k].long_name;
                    }
                    
                }
            }

            document.getElementById('location').value = results.formatted_address;
            document.getElementById('lng').value = results.geometry.location.lng();
            document.getElementById('lat').value = results.geometry.location.lat();
            document.getElementById('city').value = city;
            document.getElementById('state').value = state;
            document.getElementById('country').value = country;
            document.getElementById('postcode').value = postcode;
            infowindow.setContent(results[0].formatted_address);
            infowindow.open(map, marker);
          }
        }
        });
    });
}
function bindDataToForm(address,lat,lng){
   document.getElementById('location').value = address;
   document.getElementById('lat').value = lat;
   document.getElementById('lng').value = lng;
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script type="text/javascript">
  $(document).ready(function() {

    <?php 

      if(!empty(json_decode($property->gallary_image))) {
        $images = "[";
        foreach(json_decode($property->gallary_image) as $data) {
          $images .= '"'.url('media/gallery/'.$data).'",';
        }
        $images .= "]";
    ?>
        var images = <?php echo $images; ?>
    <?php  
      }
    ?>
     
    if (window.File && window.FileList && window.FileReader) {
      $("#files").on("change", function(e) {
         $('.remove-old-img').remove(); 
        var files = e.target.files,
          filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();
          fileReader.onload = (function(e) {
            var file = e.target;
            $('.files_insert').html("<span class=\"pip1\">" +
              "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
              "<i class=\"fas fa-window-close remove1\"></i>"+
              "</span>")
            // $().insertBefore("#files");
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
  });



  $(document).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
      $("#gallery_image").on("change", function(e) {
        //$('.pip').remove();
        var files = e.target.files;
        console.log(files); 
        filesLength = files.length;
        $(".onchangeImg").remove();
        for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();
          fileReader.onload = (function(e) {
            var file = e.target;
            $("<span class=\"pip onchangeImg\">" +
              "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
              "<i class=\"fas fa-window-close remove\"></i>"+
              "</span>").insertBefore("#gallery_image");
            $(".remove").click(function(){
              $(this).parent(".onchangeImg").remove();
            });
          });
          fileReader.readAsDataURL(f);
        }

        // if(filesLength == 0 || filesLength == "") {
        //   $('.pip').remove();
        // }

        // console.log($("#files1").val());
        // $("#files1").val('');
        // $("#files1").attr('value', '');

      });
    } else {
      alert("Your browser doesn't support to File API")
    }
  });
</script>
<script>
   Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};


function removeImage(data){

    var inputvalue = $('#gallery_img').val();

    //console.log();
    var ary = JSON.parse(inputvalue);

    ary.remove(data);
    $('.pip[data-title="'+data+'"]').remove();
    $('#gallery_img').val(JSON.stringify(ary));
}
</script>

@endpush
@endsection