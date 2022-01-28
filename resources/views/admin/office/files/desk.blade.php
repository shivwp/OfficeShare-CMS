	<div class="row">
	<div class="col-sm-12">
	 <h6>Add desk</h6>	
	 <hr class="light-grey-hr"/><br>
	</div>
	<div class="col-sm-10">
		<label>Desk Type</label>
		<select class="form-control select2" name="desk[]" id="deskapply"
		 required="" multiple="" style="width:100%">
			<option value=""></option>
			@if(isset($desks))
             @foreach($desks as $item)
              <option value="{{ $item->types.'_'.$item->id }}">{{ $item->types }}</option>
             @endforeach
			@endif
		</select>
	</div>
	<div class="col-sm-2 pt-4 text-right">
	 <button class="btn btn-primary btn-md applydesk">Apply desk</button>	
	</div>
    </div>
 <div class="adddesktype"></div>   
{{-- <form class="form-group" id="adddesk" method="post"> --}}
{{-- 	 <div class="row desk">
	 <div class="col-sm-8">
	  Computer Desk	
	 </div>
	 <div class="col-sm-4 text-right">
	 <a data-toggle="collapse" href="#demo"><i class="fas fa-align-justify"></i></a>
	 </div>
	 </div>     
    <div class="collapse" id="demo">
	<div class="row  p-2">
	<div class="col-sm-12">
    <br>
	 <h6>Desk Information</h6>	
	 <hr class="light-grey-hr"/><br>
	</div>
	<div class="col-sm-4 pt-1">
	 <label>Desk Cost</label>
	 <input type="number" name="desk_number" class="form-control">	
	</div>
	<div class="col-sm-4">
	 <label>Desk Type</label>
	 <select class="form-control" name="desk_type">
	 <option value="">Select one</option>
	 <option value="flat rate">Flat Rate</option>
	 <option value="percentage">Percentage</option>	
	 </select>
	</div>
	<div class="col-sm-4 pt-1">
	 <label>Desk Discount</label>
	 <input type="number" name="desk_number" class="form-control">	
	</div>
	<div class="col-sm-4">
	 <label>Desk Image</label>
	 <input type="file" name="desk_file[]" class="form-control" multiple="">	
	</div>
	<div class="col-sm-4">
	 <label>Number of desk</label>
	 <input type="number" name="noofdesk" class="form-control">	
	</div>
	<div class="col-sm-4 text-right pt-4">
	<button class="btn btn-sm btn-primary">Create Desk</button>
	</div>
    </div>
    <div class="row p-2">
    	<div class="col-sm-12"><h6>Provide desk number</h6></div>
    	<div class="row p-2">
    	<div class="col-sm-3">
    		<label>Desk 1.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 2.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 3.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 4.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 5.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 6.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 7.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    </div>
    </div> --}}

  {{--    <div class="row desk">
	 <div class="col-sm-8">
	 Writing Desk	
	 </div>
	 <div class="col-sm-4  text-right">
	 <a data-toggle="collapse" href="#demo"><i class="fas fa-align-justify"></i></a>
	 </div>
	 </div>     
    <div class="collapse" id="demo">
	<div class="row  p-2">
	<div class="col-sm-12">
    <br>
	 <h6>Desk Information</h6>	
	 <hr class="light-grey-hr"/><br>
	</div>
	<div class="col-sm-4 pt-1">
	 <label>Desk Cost</label>
	 <input type="number" name="desk_number" class="form-control">	
	</div>
	<div class="col-sm-4">
	 <label>Discount Type</label>
	 <select class="form-control" name="desk_type">
	 <option value="">Select one</option>
	 <option value="flat rate">Flat Rate</option>
	 <option value="percentage">Percentage</option>	
	 </select>
	</div>
	<div class="col-sm-4 pt-1">
	 <label>Desk Discount</label>
	 <input type="number" name="desk_number" class="form-control">	
	</div>
	<div class="col-sm-4">
	 <label>Desk Image</label>
	 <input type="file" name="desk_file[]" class="form-control" multiple="">	
	</div>
	<div class="col-sm-4">
	 <label>Number of desk</label>
	 <input type="number" name="noofdesk" class="form-control">	
	</div>
	<div class="col-sm-4 text-right pt-4">
	<button class="btn btn-sm btn-primary">Create Desk</button>
	</div>
    </div>
    <div class="row p-2">
    	<div class="col-sm-12"><h6>Provide desk number</h6></div>
    	<div class="col-sm-3">
    		<label>Desk 1.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 2.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 3.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 4.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 5.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 6.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    	<div class="col-sm-3">
    		<label>Desk 7.</label>
    		<input type="text" name="" class="form-control">
    	</div>
    </div>
    </div> --}}
{{--      <div class="row">
	 <div class="col-sm-12">
	 <button class="btn btn-primary btn-sm">Save & Update</button>	
	</div>	 
	</div>  --}}
{{-- <table class="table table-bordered">
	<thead>
		<tr>
			<th>Flexi Desk</th>
			<th><a data-toggle="collapse" href="#demo"><i class="fas fa-align-justify"></i></a></th>
		</tr>
	</thead>
	<tbody class="collapse" id="demo">
		<tr>
		<td colspan="2"  >

		</td>	
		</tr>
	</tbody>
</table> --}}
{{-- </form> --}}