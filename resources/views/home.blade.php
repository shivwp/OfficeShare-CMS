@extends('layouts.admin')
@section('content')
<style type="text/css">
   .wrap-card{
      background-image: url('image/bg002.png');
      padding: 0px 0px 15px 30px;
      background-size:cover;
   }
   .card-img img{
      border-style: none;
      padding: 15px;
      border: 1px solid #ffd2d2;
      background-color: #ffd2d2;
      border-radius: 50%;
      margin: 0px 10px;
   }
   .properties-number {
      font-size: 27px;
      font-weight: bold;
      font-family: Poppins, sans-serif;
   }
   .card.img-card.box-secondary-shadow {
      background: #fff;
      box-shadow: -6px 3px 64px 2px rgb(0 0 0 / 20%);
      -webkit-box-shadow: 0px 1px 14px 2px rgb(0 0 0 / 3%);
      -moz-box-shadow: -6px 3px 64px 2px rgba(0,0,0,0.2);
   }
   .revenue-dashboard-section2 {
      background-color: #fff;
      margin: 0px 0px 0px 20px;
      -webkit-box-shadow: 1px 1px 15px -9px rgba(184,184,184,1);
      -moz-box-shadow: 1px 1px 15px -9px rgba(184,184,184,1);
      box-shadow: 1px 1px 15px -9px rgba(184,184,184,1);
   }
   .revenue-dashboard-section {
      -webkit-box-shadow: 1px 1px 15px -9px rgba(184,184,184,1);
      -moz-box-shadow: 1px 1px 15px -9px rgba(184,184,184,1);
      box-shadow: 1px 1px 15px -9px rgba(184,184,184,1);
   }
   .properties-text {
      font-size: 16px;
      font-family: Poppins, sans-serif;
      color: #6a6a6a;
      font-weight: 600;
   }

   .revenue-dashboard-section{
      background-image: url(http://officeshare-cms.ewtlive.in/image/logicon-1.png);
      background-color: #fff;
      padding: 1px 20px;
      background-repeat: no-repeat;
      background-position: right;
   }
   .revenue-dashboard-section2{

      background-image: url(http://officeshare-cms.ewtlive.in/image/logicon-2.png);
      background-repeat: no-repeat;
      background-color: #fff;
      padding: 17px 15px;
      background-position: right;

   }
   .box-primary-shadow .card-body{
      margin-top: 0px!important;
   }
   img.img-responsive {
      height: 103px;
      width: 120px;
      border-radius: 15px;
      margin-bottom:30px;
   }
   .card-body1 {
      margin-top: 7px;
   }
   #add_space {
      padding: 0px 0px 10px 10px;
   }
   div#add_space h5 {
      font-weight: 700;
      font-size: 22px;
      font-family: poppins;
   }
   .property-details i{
      color: #ffbb33;
   }
   .property-details {
      padding-left: 25px;
      line-height: normal;
   }
   .protitle{
      font-size: 18px;
      font-weight: 500;
      font-family: popppins;
      color: #0d324d;
   }
   .property-details p {
      font-size: 18px;
      font-weight: 500;
     font-family: 'Muli', sans-serif;
      color: #0d324d;
   }
   p.property-price {
      color: #838383;
      font-size: 14px;
      font-family: poppins;
      font-weight: 500;
   }
   p.revenue-text {
      font-size: 16px;
      font-weight: 600;
      font-family: 'poppins', sans-serif;
      text-align: center;
   }
   p.revenue-amount {
      font-size: 32px;
      font-weight: bold;
      font-family: 'poppins', sans-serif;
      text-align: center;
   }
   .addnew-btn.active {
      background-color: #fc6565;
   }
   .addnew-btn{
      border: 0px;
      font-size: 18px;
      font-family: 'Muli', sans-serif;
      font-weight: 400;
      padding: 14px 0px;
      background: transparent;
      width: 100%;
   }
   .one-new-btn{
      border: 0px;
      font-size: 18px;
      font-family: 'Muli', sans-serif;
      font-weight: 400;
      padding: 14px 0px;
      background: transparent;
      width: 100%;
   }
   .addnew-btn:hover{
      background-color: #fc6565;
      color: #fff;
      height: 48px;
   }
   .one-new-btn:hover{
      border-radius: 10px 0px 0px 10px;
      background-color: #fc6565;
      color: #fff;
      height: 48px;
   }
   a{
      color: #000;
   }
   a:hover, a:focus {
      color: #000;
   }
   .box-secondary-shadow .card .card-body {
      padding: 1px 12px 12px 10px;
      margin-top: 20px;
      position: relative;
   }

   .box-secondary-shadow:before {
      content: '';
      position: absolute;
      background-image: url('http://officeshare-cms.ewtlive.in/image/logicon.png');
      background-position: right;
      background-repeat: no-repeat;
      width: 100%;
      height: 67%;
      opacity: 0.5;
   }
   .col-sm-7.card-text {
      margin: 12px 0px;
   }
   #month-section{
      background-color: #f2f2f2;
      border-radius: 10px;
      height: 50px;
      border: 1px solid #dddddd;
   }
   #in-btn{
      border-right: 1px  solid #dddddd;
      height: 50px;
      margin: 0;
      padding: 0;
   }

   .col.active .one-new-btn {
      border-radius: 10px 0px 0px 10px;
      background-color: #fc6565;
      color: #fff;
      height: 48px;
   }
   .col.active .addnew-btn {
      background-color: #fc6565;
      color: #fff;
      height: 48px;
   }
   input[type=date]:not(.browser-default), input[type=datetime]:not(.browser-default), input[type=datetime-local]:not(.browser-default), input[type=tel]:not(.browser-default), input[type=number]:not(.browser-default), input[type=search]:not(.browser-default), textarea.materialize-textarea {
      font-size: 1rem;
      box-sizing: border-box;
      width: 100%;
      margin: 0 0 0px 0; 
      padding: 0px 0px; 
      -webkit-transition: box-shadow .3s, border .3s;
      transition: box-shadow .3s, border .3s;
      border: none;
      border-bottom: 0px solid #9e9e9e; 
      border-radius: 0;
      outline: none;
      background-color: transparent; 
      box-shadow: none; 
   }
   .date-text{
      font-size: 18px;
      font-family: 'Muli', sans-serif;
      font-weight: 400;
      padding: 14px 0px;
      color: #000;
      line-height: 1;
   }
   p.revenue-text {
      margin-top: 30px;
   }
   @media screen and (max-width: 1450px) and (min-width: 1200px){
      .property-details {
         padding-left: 50px;
         line-height: normal;
      }
      .protitle{
         font-size: 14px;}
         .property-details p {
            margin-top: 17px;
            font-size: 13px;
         }
         p.revenue-text {
            font-size: 13px;
         }
         p.revenue-amount {
            font-size: 22px;
            font-weight: 500;
         }
      }
      @media screen and (max-width: 1500px) and (min-width: 767px){
         .addnew-btn {
            border: 0px;
            font-size: 14px;
         }
         .date-text {
            font-size: 14px;
         } 
         .one-new-btn {
            font-size: 14px;
         }
      }
      @media screen and (max-width: 1500px) and (min-width: 1200px){
         .card-img img {
            border-style: none;
            padding: 4px;
         }

         .col-sm-7.card-text {
            margin: 5px 5px;
         }
      }
      @media screen and (max-width: 575px) and (min-width: 320px){
         .col-sm-7.col-xl-7.col-lg-7.col-md-7.card-text {
            width: 60%;
         }
         .col-sm-4.col-xl-4.col-lg-4.col-md-4.card-img {
            width: 40%;
         }
      }
      #h-card{
         padding: 0px 10px;
      }
   </style>
   <div class="content">
      <div class=" row">
         <div class="col-lg-12">
            <form >
               @csrf
               <div class="row py-2 ">
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3" id="h-card" >
                     <div class="card  img-card box-secondary-shadow">
                        <div class="card-body">
                           <div class=" row d-flex">
                              <div class="col-sm-4 col-xl-4 col-lg-4 col-md-4 card-img">
                                 <img src="{{asset('image/B.png')}}">
                              </div>
                              <div class="col-sm-7 col-xl-7 col-lg-7 col-md-7 card-text">
                                 <p class="properties-number">{{$tot_property}}</p>
                                 <p class="properties-text">Total Properties</p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3" id="h-card" >
                     <div class="card  img-card box-secondary-shadow">
                        <div class="card-body">
                           <div class="row d-flex">
                              <div class="col-sm-4 col-xl-4 col-lg-4 col-md-4 card-img">
                                 <img src="{{asset('image/C.png')}}">
                              </div>
                              <div class="col-sm-7 col-xl-7 col-lg-7 col-md-7 card-text">
                                 <p class="properties-number">{{$tot_space}}</p>
                                 <p class="properties-text">Total Spaces</p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3" id="h-card">
                     <div class="card  img-card box-secondary-shadow">
                        <div class="card-body">
                           <div class=" row d-flex">
                              <div class="col-sm-4 col-xl-4 col-lg-4 col-md-4 card-img">
                                 <img src="{{asset('image/D.png')}}">
                              </div>
                              <div class="col-sm-7 col-xl-7 col-lg-7 col-md-7 card-text">
                                 <p class="properties-number">{{$tot_booking}}</p>
                                 <p class="properties-text">Totl Bookings</p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3" id="h-card">
                     <div class="card  img-card box-secondary-shadow">
                        <div class="card-body">
                           <div class=" row d-flex">
                              <div class="col-sm-4 col-xl-4 col-lg-4 col-md-4 card-img">
                                 <img src="{{asset('image/A.png')}}">
                              </div>
                              <div class="col-sm-7 col-xl-7 col-lg-7 col-md-7 card-text">
                                 <p class="properties-number">{{$tot_packages}}</p>
                                 <p class="properties-text">Total Packages</p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- COL END -->
               </div>

               <div class="row  ">
                  <div class="col-sm-12 revenue-dashboard">
                     <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-8 col-lg-12 " style="padding:0px;">
                           <div class="revenue-dashboard-section ">
                              <div class="row mt-3 mb-3" id="month-section">
                                 <div class="col-sm-12 col-md-7 col-xl-7 col-lg-7" style="margin: 0;padding: 0;" >
                                    <div class="row">

                                       <div class="col {{ (request()->get('last_week')) ? 'active' : ''}}" id="in-btn">
                                          <form action="{{route('dashboard.home.index')}}">
                                             {{-- @csrf --}}
                                             <button class=" one-new-btn" id="one-new-btn" type="submit" name="last_week" value="last_week">Last Week</button>
                                          </form>
                                       </div>
                                       <div class="col {{ (request()->get('this_month')) ? 'active' : ''}}" id="in-btn"> 
                                          <form action="{{route('dashboard.home.index')}}">
                                             {{-- @csrf --}}
                                             <button class=" addnew-btn" type="submit" name="this_month" value="this_month">This Month</button>
                                          </form>
                                       </div>
                                          <div class="col {{ (request()->get('last_month')) ? 'active' : ''}}" id="in-btn"> <form action="{{route('dashboard.home.index')}}">
                                             {{-- @csrf --}}
                                             <button class=" addnew-btn" type="submit" name="last_month" value="last_month">Last Month</button>
                                          </form></div>
                                          <div class="col {{ (request()->get('last_year')) ? 'active' : ''}}" id="in-btn"> <form action="{{route('dashboard.home.index')}}">
                                             {{-- @csrf --}}
                                             <button class=" addnew-btn" type="submit" name="last_year" value="last_year">Last Year</button>
                                          </form></div>
                                       </div>
                                    </div>
                                    <div class="col-sm-12 col-md-5 col-xl-5 col-lg-5">
                                       <form action="{{route('dashboard.home.index')}}" id="date-rev">
                                          <div class="row">
                                             <div class="col-md-4">
                                                <label class="date-text">Custom:</label>
                                             </div>
                                             
                                             <div class="col-md-4">
                                                <input type="date" name="from" value="{{request()->get('from') ? request()->get('from') : ''}}"  class="form-control date-rev" style="margin: 6px;">
                                             </div>
                                             <div class="col-md-4">
                                                <input type="date" name="to" value="{{request()->get('to') ? request()->get('to') : ''}}" class="form-control date-rev" style="margin: 6px;">
                                             </div>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="card-body " id="add_space">
                                       <h6>Revenue</h6>
                                       <div class="sample-chart-wrapper">
                                          <canvas id="line-chart"  height="430" style="width:75% !important;"></canvas>
                                       </div>
                                       <div class="card-body ">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row mt-2">
                                    <div class="col-sm-6 col-md-3 col-xl-3 col-lg-3">
                                       <p class="revenue-text">{{$msgBookings}}</p>
                                       <p class="revenue-amount">{{count($totalnumBooking)}}</p>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-3 col-xl-3 col-lg-3">
                                       <div id="bookedbooking"></div>
                                       {{-- <div id="demo1" class="donut-size1">
                                          <div class="pie-wrapper1">
                                             <span class="label">
                                                <input type="hidden" name="demo2" id="cancelround" value="{{round($cancelbooking)}}">
                                                <span class="num">{{round($bookedbooking)}}</span><span class="">%</span>
                                                <p class="smaller1">Completed</p>
                                             </span>
                                             <div class="pie1">
                                                <div class="left-side half-circle1"></div>
                                                <div class="right-side1 half-circle1"></div>
                                             </div>
                                             <div class="shadow1"></div>
                                          </div>
                                       </div> --}}
                                    </div>

                                    <div class="col-sm-6 col-md-3 col-xl-3 col-lg-3">
                                       <div id="cancelbooking"></div>
                                       {{-- <div id="demo" class="donut-size">
                                          <div class="pie-wrapper">
                                             <span class="label">
                                                <input type="hidden" name="demo1" id="bookinground" value="{{round($bookedbooking)}}">
                                                <span class="num">{{round($cancelbooking)}}</span>
                                                <span class="">%</span>
                                                <p class="smaller">Cancelled</p>
                                             </span>
                                             <div class="pie">
                                                <div class="left-side half-circle"></div>
                                                <div class="right-side half-circle"></div>
                                             </div>
                                             <div class="shadow"></div>
                                          </div>
                                       </div> --}}
                                    </div>

                                    
                                    <div class="col-sm-6 col-md-3 col-xl-3 col-lg-3">
                                       <div id="pendingbooking"></div>
                                       {{-- <div id="demo2" class="donut-size2">
                                          <div class="pie-wrapper2">
                                             <span class="label">
                                                <input type="hidden" name="demo3" id="pendinground" value="{{round($pendingbooking)}}">
                                                <span class="num">{{round($pendingbooking)}}</span>
                                                <span class="">%</span>
                                                <p class="smaller2">Pending</p>
                                             </span>
                                             <div class="pie2">
                                                <div class="left-side half-circle12"></div>
                                                <div class="right-side2 half-circle12"></div>
                                             </div>
                                             <div class="shadow2"></div>
                                          </div>
                                       </div> --}}
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-sm-12 col-md-12 col-xl-4 col-lg-12 " style="padding:0px;">
                              <div class="revenue-dashboard-section2 ">
                                 <div class="card-body" id="add_space">
                                    <h5>Last 5 Bookings</h5>
                                 </div>
                                 <div class="card-body1">
                                    @if(count($last_five_booking) > 0)
                                    @foreach($last_five_booking as $key => $val)
                                    <a href="{{ route('dashboard.single-booking',$val->id) }}">
                                       <div class="row">
                                          <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 property-img">
                                             <img src="{{ asset('/media/thumbnail/' . $val->property_image) }}" class="img-responsive">
                                          </div>
                                          <div class=" col-xs-6 col-sm-8 col-md-8 col-xl-8 col-lg-8 property-details">
                                             <p class="protitle">{{$val->property_title}}</p>
                                             <p>
                                                @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $val->property_rating )
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                                @else
                                                <i class="far fa-star" aria-hidden="true"></i>
                                                @endif
                                                @endfor
                                             </p>
                                             @if($val->property_price_from == $val->property_price_to)
                                             <p class="property-price">Price: £{{$val->property_price_from}}</p>
                                             @else
                                             <p class="property-price">Price: £{{$val->property_price_from}} to £{{$val->property_price_to}}</p>
                                             @endif
                                          </div>
                                       </div>
                                    </a>
                                    @endforeach
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         @endsection
         @section('scripts')
         <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
         <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
         <script src="//code.jquery.com/jquery-3.1.1.slim.min.js"></script>
         <script src="{{ asset('js/simple-donut-jquery.js')}}"></script>
         <script type="text/javascript">
            $(function() {

               $('input[name="custom"]').daterangepicker({
                  autoUpdateInput: false,
                  locale: {
                     cancelLabel: 'Clear'
                  }
               });

               $('input[name="custom"]').on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
               });

               $('input[name="custom"]').on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
               });

            });
         </script>

<script type="text/javascript">
   $(window).on("load", function() {
      //Get the context of the Chart canvas element we want to select
      var ctx = $("#line-chart");

      // Chart Options
      var chartOptions = {
         responsive: true,
         maintainAspectRatio: false,
         legend: {
            position: "bottom"
         },
         hover: {
            mode: "label"
         },
         scales: {
            xAxes: [
            {
               display: true,
               gridLines: {
                  color: "#f3f3f3",
                  drawTicks: false
               },
               scaleLabel: {
                  display: true,
                  labelString: "{{ $xName }}"
               }
            }
            ],
            yAxes: [
            {
               display: true,
               gridLines: {
                  color: "#f3f3f3",
                  drawTicks: false
               },
               scaleLabel: {
                  display: true,
                  labelString: "Value"
               }
            }
            ]
         },

      };

      // Chart Data
      var chartData = {
         labels: [{!!$xAxes!!}],
         datasets: [
            {
               label: "Booking",
               data: [{{ $bookingGraph }}],
               fill: false,
               borderColor: "#e91e63",
               pointBorderColor: "#e91e63",
               pointBackgroundColor: "#FFF",
               pointBorderWidth: 2,
               pointHoverBorderWidth: 2,
               pointRadius: 4
            },
            {
               label: "Cancelled",
               data: [{{ $cancelGraph }}],
               fill: false,
               borderColor: "#03a9f4",
               pointBorderColor: "#03a9f4",
               pointBackgroundColor: "#FFF",
               pointBorderWidth: 2,
               pointHoverBorderWidth: 2,
               pointRadius: 4
            },
            {
               label: "Pending",
               data: [{{ $pendingGraph }}],
               fill: false,
               borderColor: "#ffc107",
               pointBorderColor: "#ffc107",
               pointBackgroundColor: "#FFF",
               pointBorderWidth: 2,
               pointHoverBorderWidth: 2,
               pointRadius: 4
            }
         ]
      };

      var config = {
         type: "line",

         // Chart Options
         options: chartOptions,

         data: chartData
      };

      // Create the chart
      var lineChart = new Chart(ctx, config);


   });

</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">

$(document).ready(function(){
   // var demo = $('#bookinground').val();
   // var demo1 = $('#cancelround').val();
   // var demo2 = $('#pendinground').val();
   // updateDonutChart('#demo', demo1,true);
   // updateDonutChart('#demo1', demo,true);
   // updateDonutChart('#demo2', demo2,true);
   

   var cancelbooking = {
      chart: {
         height: 185,
         type: "radialBar",
      },
      series: [{{round($cancelbooking)}}],
      colors: ["#009be0"],
      plotOptions: {
         radialBar: {
            hollow: {
               margin: 0,
               size: "60%",
               background: "#fff"
            },
            dataLabels: {
               name: {
                  offsetY: 10,
                  color: "#828282",
                  fontSize: "13px"
               },
               value: {
                  offsetY: -25,
                  color: "#293450",
                  fontSize: "22px",
                  show: true
               }
            }
         }
      },
      labels: ["Cancelled"]
   };

   var chart = new ApexCharts(document.querySelector("#cancelbooking"), cancelbooking);

   chart.render();


   var bookedbooking = {
      chart: {
         height: 185,
         type: "radialBar",
      },
      series: [{{round($bookedbooking)}}],
      colors: ["#fc595a"],
      plotOptions: {
         radialBar: {
            hollow: {
               margin: 0,
               size: "60%",
               background: "#fff"
            },
            dataLabels: {
               name: {
                  offsetY: 10,
                  color: "#828282",
                  fontSize: "13px"
               },
               value: {
                  offsetY: -25,
                  color: "#293450",
                  fontSize: "22px",
                  show: true
               }
            }
         }
      },
      labels: ["Booking"]
   };

   var chart = new ApexCharts(document.querySelector("#bookedbooking"), bookedbooking);

   chart.render();


   var pendingbooking = {
      chart: {
         height: 185,
         type: "radialBar",
      },
      series: [{{round($pendingbooking)}}],
      colors: ["#ffbb33"],
      plotOptions: {
         radialBar: {
            hollow: {
               margin: 0,
               size: "60%",
               background: "#fff"
            },
            dataLabels: {
               name: {
                  offsetY: 10,
                  color: "#828282",
                  fontSize: "13px"
               },
               value: {
                  offsetY: -25,
                  color: "#293450",
                  fontSize: "22px",
                  show: true
               }
            }
         }
      },
      labels: ["Pending"]
   };

   var chart = new ApexCharts(document.querySelector("#pendingbooking"), pendingbooking);

   chart.render();

});
</script>
<script>
   $(".date-rev").change(function() {
      $('#date-rev').submit();
   });
</script>
@parent
@endsection