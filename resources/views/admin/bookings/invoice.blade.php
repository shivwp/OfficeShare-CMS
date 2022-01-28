  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<div class="container-fluid">
    <div id="ui-view" data-select2-id="ui-view">
        <div>
            <div class="card">
                <div class="card-header">Invoice
                    <strong>#{{$data->id}}</strong><br/>
                    <p>{{\Carbon\Carbon::parse($data->created_at)->format('d-M-Y')}}</p>
                    <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                        <i class="fa fa-print"></i> Print</a>
                    {{--<a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="#" data-abc="true">
                        <i class="fa fa-save"></i> Save</a>--}}
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <h6 class="mb-3">From:</h6>
                            <div>
                                <strong>from http://officeshare.ewtlive.in</strong>
                            </div>
                            <div>{{$data->landload->name}}</div>
                            <div>42, Awesome Enclave</div>
                            <div>New York City, New york, 10394</div>
                            <div>Email: admin@bbbootstrap.com</div>
                            <div>Phone: +48 123 456 789</div>
                        </div>
                        <div class="col-sm-4">
                        </div>

                         {{-- <div class="col-sm-4">
                            <h6 class="mb-3">Details:</h6>
                            <div>Invoice
                                <strong>#{{$data->id}}</strong>
                            </div>
                            <div>{{\Carbon\Carbon::parse($data->created_at)->format('d-M-Y')}}</div>
                        </div>--}}
                        <div class="col-sm-4">
                            <h6 class="mb-3">To:</h6>
                            <div>
                                <strong>{{$data->user->name}}</strong>
                            </div>
                            @if(!empty($data->userAddress))
                            <div>{{$data->userAddress->address_1.' '.$data->userAddress->address_2}}</div>
                            <div>{{$data->userAddress->city.' '.$data->userAddress->state.' '.$data->userAddress->country.' '.$data->userAddress->postcode}}</div>
                            @endif
                            <div>Email: {{$data->user->email}}</div>
                            <div>Phone: {{$data->user->phone}}</div>
                        </div>
                      
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Landload</th>
                                    <th>Property</th>
                                    <th>Space</th>
                                    <th class="center">Start Date</th>
                                    <th class="right">Booking Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">1</td>
                                    <td class="left">{{$data->landload->name}}</td>
                                    <td class="left">{{$data->user->name}}</td>
                                    <td class="center">{{$data->space->space_title}}</td>
                                    <td class="right">{{\Carbon\Carbon::parse($data->created_at)->format('d-M-Y')}}</td>
                                    <td class="right">
                                        @if(!empty($data->booking_details->booking_dated))
                                            @foreach(json_decode($data->booking_details->booking_dated) as $val)
                                            <p>{{\Carbon\Carbon::parse($val)->format('d-M-Y')}}</p>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</div>
                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-clear">
                                <tbody>
                                    <tr>
                                        <td class="left">
                                            <strong>Subtotal</strong>
                                        </td>
                                        <td class="right">{{$data->price}}$</td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong>Discount (0%)</strong>
                                        </td>
                                        <td class="right">{{$data->price}}$</td>
                                    </tr>
                                    {{--<tr>
                                        <td class="left">
                                            <strong>VAT (10%)</strong>
                                        </td>
                                        <td class="right">$679,76</td>
                                    </tr>--}}
                                    <tr>
                                        <td class="left">
                                            <strong>Total</strong>
                                        </td>
                                        <?php 
                                        $total =count(json_decode($data->booking_details->booking_dated));
                                        ?>
                                        <td class="right">
                                            <strong>{{$data->price * $total}}$</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .card {
    margin-bottom: 1.5rem
}

.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #c8ced3;
    border-radius: .25rem
}

.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0
}

.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #f0f3f5;
    border-bottom: 1px solid #c8ced3
} 
</style>