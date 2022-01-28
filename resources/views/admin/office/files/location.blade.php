 <form id="addlocation" class="form-group" method="post" action="{{ route('dashboard.office.store') }}">
 @csrf
 <div class="row">
  <div class="col-sm-12">
  	<h6 class="b">Add Address</h6>
  	<hr class="light-grey-hr"/><br>
  </div>	
  <div class="col-sm-6">
    <label>Country</label>
    <select class="form-control country select2" name="country" required="" style="width:100%">
      <option value="">Select one</option>
     @if(isset($countries))
          @foreach($countries as $item)
           <option value="{{ $item->id }}">{{ $item->name }}</option> 
          @endforeach 
     @endif 
    </select>
  </div>
  <div class="col-sm-6">
      <label>State</label>
      <select class="form-control state select2" name="state" style="width:100%"></select>
  </div>
  <div class="col-sm-6">
    <label>City</label>
    <select class="form-control city select2" name="city" style="width:100%"></select>
  </div>
  <div class="col-sm-6">
     <label>Phone</label>
    <input type="number" name="phone" class="form-control">
  </div>
    <div class="col-sm-4">
      <label>Postcode</label>
    <input type="number" name="postcode" class="form-control">
  </div>
  <div class="col-sm-4">
    <label>Langitude</label>
    <input type="text" name="lang" class="form-control">
  </div>
    <div class="col-sm-4">
    <label>Latitude</label>
    <input type="text" name="lat" class="form-control">
  </div>
     <div class="col-sm-12">
      <label>Address</label>
      <textarea class="form-control" name="address"></textarea>
     </div>
     <div class="col-sm-12">
     	<button class="btn btn-primary btn-sm">Save & update</button>
     </div>
</div>
</form>