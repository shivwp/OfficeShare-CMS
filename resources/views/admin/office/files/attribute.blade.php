<form class="form-group" id="getattrvalue" method="post" enctype="multipart/form-data">
	@csrf
  	<div class="row">
	<div class="col-sm-12">
	 <h6>Add Attributes</h6>
	 <hr class="light-grey-hr"/><br>		
	</div>
	<div class="col-sm-10">
	 <label>Attributes</label>
	 <select class="form-control select2" name="attribute[]" 
	 required="" multiple=""  style="width:100%">
	 	<option value="">Select one</option>
	 	@if(isset($attributes))
         @foreach($attributes as $item)
          <option value="{{ $item->id }}">{{ $item->display_name }}</option>
         @endforeach
	 	@endif
	 </select>	
	</div>
	<div class="col-sm-2 pt-4">
	 <button class="btn btn-primary btn-md">GET VAUE</button>	
	</div>
	</div>  
</form>

<div class="appendAttr2">
	
</div>