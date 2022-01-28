@extends('layouts.admin')

@section('title', $title)

@section('content')

    <!-- /Row -->

    <div class="card">

        <div class="card-header">

          <div class="card-body">

            @can('zone_create')

            <form action="{{ route('admin.selling-zone.store') }}" method="post" class="p-2 ">

                @csrf

                <h5>{{ $title }}</h5>

                @if(session('msg'))

                <p class="p-1 alert-success text-dark text-center">{{ session('msg') }}</p>

               @endif

            <div class="row border-light">

               <div class="col-sm-4 form-group">

                 <label>Country</label>

                   <select class="form-control country" name="country" required="">

                       <option value="{{ isset($edzone->getCountry['id'])?$edzone->getCountry['id']:'' }}">{{ isset($edzone->getCountry['name'])?$edzone->getCountry['name']:'Select Country' }}</option>

                       @isset($country)

                         @foreach($country as $ct)

                           <option value="{{ $ct->id }}">{{ $ct->name }}</option>

                         @endforeach

                       @endisset

                   </select>

               </div>

                <div class="col-sm-4 form-group">

                 <label>State</label>

                     <select class="form-control state" name="state" required="">

                        <option value="{{ isset($edzone->getState['id'])?$edzone->getState['id']:'' }}">{{ isset($edzone->getState['name'])?$edzone->getState['name']:'' }}</option>

                     </select>

               </div>

                <div class="col-sm-4 form-group">

                 <label>City</label>

                     <select class="form-control city" name="city" required="">

                         <option value="{{ isset($edzone->getCity['id'])?$edzone->getCity['id']:'' }}">{{ isset($edzone->getCity['name'])?$edzone->getCity['name']:'' }}</option>

                     </select>

               </div>

               <div class="col-sm-4 form-group">

                 <label>Postal Code</label>

                     <input type="text" name="pincode" required="" class="form-control" value="{{ isset($edzone->postal_code)?$edzone->postal_code:'' }}">

               </div>

               <div class="col-sm-4 form-group">

                   <label>Shipping Option</label>

                  <p><input type="radio" name="ship"   value="free"> Free &nbsp;&nbsp;

                  <input type="radio" name="ship"  value="paid"> Charge</p>

              </div>

                <input type="hidden" name="id"  value="{{ isset($edzone->id)?$edzone->id:'' }}">

      

                <div class="col-sm-4 form-group text-center">

                   <button class="btn btn-primary btn-sm">Add & Update</button>

               </div>

              </div>

             </form>

             @endcan<br>

            <div class="table-responsive">

                <table class=" table table-striped table-hover datatable datatable-User" id="example">

                    <thead>

                        <tr>

                            <th>Selling Countries</th>

                            <th>Selling States</th>

                            <th>Selling Cities</th>

                            <th>Selling postal code</th>

                            <!--<th>Shipping Charge</th>-->

                            <th>Set Shipping Method</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody class="c">

                        @isset($zone)

                            <?php $i = 0; ?>

                            @foreach ($zone as $item)

                                <tr id='{{ $item->id }}'>

                                    <td>{{ Str::ucfirst($item->getCountry['name']) }}</td>

                                    <td>{{ Str::ucfirst($item->getState['name']) }}</td>

                                    <td>{{ Str::ucfirst($item->getCity['name']) }}</td>

                                    <td>{{ Str::ucfirst($item->postal_code) }}</td>

                                     <!--<td>{{ Str::ucfirst($item->shipping_charge) }}</td>-->

                                

                                     <td>@if($item->shipping_charge!="free")

                                           <a class="btn btn-warning btn-xs edit btn-rounded text-white showShipOpt"  id="{{ $item->id }}">Add Shipping Method</a>

                                           <a href="javascript:void(0)" class="view_ship" id="{{$item->id}}"><i class="fa fa-eye"></i></a>

                                          @else

                                           <a class="btn-xs btn-success edit btn-rounded text-white "  id="{{ $item->id }}">Free Shipping</a>

                                          @endif

                                          

                                          </td>

                                    <td>

                                        @if($item->status == 1)

                                            <a class="btn btn-success btn-xs edit btn-rounded" href="{{url('admin/change-selling-status')}}/{{$item->id}}">Active</a>

                                    </td>

                                @else

                                    <a class="btn btn-danger btn-xs edit btn-rounded" href="{{url('admin/change-selling-status')}}/{{$item->id}}">De-active</a></td>

                            @endisset

                            <td>

                                @can('zone_edit')

                                    <a class="btn btn-xs btn-info " href="{{ route('admin.selling-zone.edit',$item->id) }}">

                                        <i class="far fa-edit"></i>

                                    </a>

                                @endcan

                                @can('zone_delete')

                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger delzone"><i

                                            class="fas fa-trash-alt"></i></a>

                                @endcan

                            </td>

                            </tr>

                            @endforeach

                        @endisset

                    </tbody>

                </table>

            </div>

        </div>

    </div>

<div id="shipping_method" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header" style="background-color:#32D3A1">



<h5 class="modal-title">Add & Update Shipping Method</h5>

</div>

<form id="addShip" method="post">

  @csrf

  <input type="hidden" name="sid" value="" id="sid">

<div class="modal-body">

    <div>

    <div class="col-sm-12 form-group">

            <label>Shipping Option</label>

            <select name="ship_opt" class="form-control ship_control" required>

                <option value="">Shipping Option</option>

                <option value='free'>Free</option>

               <option value='charge'>Shipping Charge</option>  

            </select>

         </div>

    </div>     

 <div class="free_shipping">

<div class="form-group">

<label for="recipient-name" class="control-label mb-10">Free Shipping Order Above(Amount)</label>

<input type="number" class="form-control" id="order_above" name="order_above"  value="" >

</div>

</div>         

<div class="paid_shipping">

<div class="form-group">

<label for="recipient-name" class="control-label mb-10"> Shipping Option Name </label>

<input type="text" class="form-control " id="name" name="name"  value="" >

</div>

<div class="form-group">

<label for="recipient-name" class="control-label mb-10"> Shipping  Amount </label>

<input type="number" class="form-control " id="ship_amount" name="ship_amount"  value="" >

</div>

<div class="form-group">

<label for="recipient-name" class="control-label mb-10">Make Default Shipping Charge </label>

<input type="checkbox" class="form-check name" id="ship_deaf" name="ship_deaf"  value="1" >

</div>

</div> 

<input type="hidden" name="id" id="id" value="">

</div>

<div class="modal-footer">

<button type="button" class="btn btn-default reload" data-dismiss="modal">Close</button>

<button type="submit" class="btn btn-danger">+ Add & Update</button>

</div>

</form>

</div>

</div>

</div>

<div id="view_shipping" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<div class="modal-header" style="background-color:#32D3A1">

<h5 class="modal-title">Manage Shipping Method</h5>

</div>

<div class="modal-body">

  <table class="table table-hover">

    <thead>

      <tr>

        <th>Label</th>

        <th>Cost</th>

        <th>Shipping Option</th>

        <th>Action</th>

      </tr>

    </thead>

    <tbody class="add_ship">

      

    </tbody>

  </table>

</div>

<div class="modal-footer">

 <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>

</div>

</div>

</div>

</div>

    @push('ajax-script')



        <!-- Edit CAT -->

        <script type="text/javascript">

            $(".delzone").click(function(event) {

                var id = $(this).parents('tr').attr('id');

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {

                        confirmButton: 'btn btn-success',

                        cancelButton: 'btn btn-danger'

                    },

                    buttonsStyling: false

                })

                swalWithBootstrapButtons.fire({

                    title: 'Are you sure?',

                    text: "You won't be able to revert this!",

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, delete it!',

                    cancelButtonText: 'No, cancel!',

                    reverseButtons: true

                }).then((result) => {

                    if (result.isConfirmed) {



                        $.ajax({

                            url: "{{ url('admin/selling-zone') }}/"+id,

                            type: 'DELETE',

                            data:{ 

                                id:id,

                                _token:'{{csrf_token() }}'

                                        },

                            success:function(data)

                            { swalWithBootstrapButtons.fire(

                                        'Deleted!',

                                        'Your file has been deleted.',

                                        'success'

                                    )

                            

                                $("#"+id).remove() 

                            }

                            })

                    } else if (

                        /* Read more about handling dismissals below */

                        result.dismiss === Swal.DismissReason.cancel

                    ) {

                        swalWithBootstrapButtons.fire(

                            'Cancelled',

                            'Your imaginary file is safe :)',

                            'error'

                        )

                    }

                })

            });



        </script>

<script>

    $(document).ready(function(){

       $(document).on('click', '.del_shipOpt', function(event) {

             let id=$(this).parents('tr').attr('id');

             const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {

                        confirmButton: 'btn btn-success',

                        cancelButton: 'btn btn-danger'

                    },

                    buttonsStyling: false

                })

                swalWithBootstrapButtons.fire({

                    title: 'Are you sure?',

                    text: "You won't be able to revert this!",

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, delete it!',

                    cancelButtonText: 'No, cancel!',

                    reverseButtons: true

                }).then((result) => {

                    if (result.isConfirmed) {



                        $.ajax({

                            url: "{{ url('admin/remove-shipping-option') }}/"+id,

                            type: 'DELETE',

                            data:{ 

                                id:id,

                                _token:'{{csrf_token() }}'

                                        },

                            success:function(data){ 

                              swalWithBootstrapButtons.fire(

                                        'Deleted!',

                                        'Your file has been deleted.',

                                        'success'

                                    )

                            

                                $("#"+id).remove() 

                            }

                            })

                    } else if (

                        /* Read more about handling dismissals below */

                        result.dismiss === Swal.DismissReason.cancel

                    ) {

                        swalWithBootstrapButtons.fire(

                            'Cancelled',

                            'Your imaginary file is safe :)',

                            'error'

                        )

                    }

                })

       });

       //Edit Ship options

        $(document).on('click', '.edit_shipOpt', function(event) {

          event.preventDefault();

           let v=$(this).parents('tr').attr('id');

            $.get('{{url("admin/edit-shipping-option")}}/'+v, function(data) {

              $('.ship_control').prepend('<option value="'+data.shipping_option+'" selected>'+data.shipping_option=="charge"?"Shipping Charge":"Free"+'</option>')

              if(data.shipping_option=="free"){

                 $('.free_shipping').show();

                 $('.paid_shipping').hide();

              }else if(data.shipping_option=="charge"){

                 $('.free_shipping').hide();

                 $('.paid_shipping').show();  

              }

              $("#name").val(data.label);

              $("#ship_amount").val(data.cost)

              $('#order_above').val(data.order_above)

              $('#sid').val(data.id)

              $('#id').val(data.selling_zone_id)

              $('#view_shipping').modal('hide')

              $('#shipping_method').modal('show')



            });

        });

       //view shipping method

         $(document).on('click', '.view_ship', function(event) {

           event.preventDefault();

            let v=$(this).attr('id');

            $.get('{{url("admin/get-shipping-method")}}/'+v, function(data) {

                let td="";

             $.each(data, function(index, val) {

              let lab=(val.label==null)?"Order Above":val.label

              let c=(val.cost==null)?val.order_above:val.cost

               td +="<tr id='"+val.id+"'>" ;

                td +='<td>'+lab+'</td>';

                 td +='<td>'+c+'</td>';

                  td +='<td>'+val.shipping_option+'</td>';

                    td +='<td><a href="javascript:void(0)" class="badge badge-success edit_shipOpt"><i class="fa fa-edit"></i></a> /<a href="javascript:void(0)" class="badge badge-danger del_shipOpt" ><i class="fa fa-trash"></i></a></td>';

                    td +="</tr>"

             });

              

              $('.add_ship').html(td)

              $('#view_shipping').modal('show')

            });

         }); 

        //show modal for adding shipping method

        $('.showShipOpt').click(function(event) {

           let v=$(this).attr('id');

           $("#id").attr('value',v);

          $('#shipping_method').modal('show')

        });

        // shipping setting

        $('.free_shipping').hide();

        $('.paid_shipping').hide();

       $(document).on('change','.ship_control',function(){

         let v=$(this).val();

        if(v=="free"){

           $('.free_shipping').show();

           $('.paid_shipping').hide();

        }else if(v=="charge"){

           $('.free_shipping').hide();

           $('.paid_shipping').show();  

        }

       }) 

    });

</script>

<script type="text/javascript">

  $(document).ready(function() {

    $(document).on('submit', '#addShip', function(event) {

      event.preventDefault();

      $.ajax({

        url: '{{url("admin/set-shipping-option")}}',

        type: 'POST',

        contentType:false,

        processData:false,

        data: new FormData(this),

        success:function(data){

         Swal.fire('Shipping Method Added','success')   

          $('#addShip')[0].reset();

        }

      })

      

    });

  });

</script>



        <!-- for data search -->

        <script>

            $(document).ready(function() {

                $("#InputCat").on("keyup", function() {

                    var value = $(this).val().toLowerCase();

                    $(".c tr").filter(function() {

                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

                    });

                });

            });



        </script>

  <script type="text/javascript">

    $(document).on('change','.country', function(event) {

            var id=$(this).val();

            $.get('{{ url("admin/get-state") }}/'+id, function(data) {

                var d=$.parseJSON(data);

                var option="<option value=''>Select State</option>";

                $.each(d, function(index, val) {

                    option +='<option value="'+val.id+'">'+val.name+'</option>'

                });

                $(".state").html(option)

            });

            

        });

</script>



<script type="text/javascript">



        $(document).on('change','.state', function(event) {

            var id=$(this).val();

            $.get('{{ url("admin/get-city") }}/'+id, function(data) {

                var d=$.parseJSON(data);

                var option="<option value=''>Select City</option>";

                $.each(d, function(index, val) {

                    option +='<option value="'+val.id+'">'+val.name+'</option>'

                });

                $(".city").html(option)

            });

            

        });



</script>

    @endpush

@endsection

