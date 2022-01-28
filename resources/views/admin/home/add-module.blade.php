@extends('layouts.admin')

@section('content')



<div class="card">

  <div class="card-header">

    <div class="row">

      <div class="col-sm-6">

        <h4 class="card-title">

          {{ $title }}

        </h4>

      </div>

      <div class="col-sm-6 text-right">

        @can('page_create')

        <div style="margin-bottom: 10px;" class="row">

          <div class="col-lg-12">

            <a class="btn btn-success btn-sm" href="{{ route('dashboard.load-page') }}">

              View Page Module

            </a>

          </div>

        </div>

        @endcan

      </div>

    </div>

  </div>

  <div class="card-body">

    <form action="{{ route("dashboard.home.store") }}" method="POST" enctype="multipart/form-data">

      @csrf

      @if(session()->has('msg'))

      <p class="alert alert-success">{{ session('msg') }}</p>

      @endif

      <div class="row">

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6">

          <label for="name">Select Module</label>

          <select class="form-control module" name="module">

            <option value="{{ isset($page->page_title)?$page->page_title:'' }}">{{ isset($page->page_title)?$page->page_title:'Select Module' }}</option>

            <option {{ isset($data) && $data->page_module=="Offer Banner"?"selected":'' }}>
              Offer Banner</option>

            <option {{ isset($data) && $data->page_module=="Product Banner"?"selected":'' }}>Product Banner</option>

            <option {{ isset($data) && $data->page_module=="Display Products"?"selected":'' }}>Display Products</option>

          </select>

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6 ">

          <label for="name">Content Position</label>

          <select class="form-control" name="content_post">

            <option value="{{ isset($page->page_title)?$page->page_title:'' }}">{{ isset($page->page_title)?$page->page_title:'Select Content Position' }}</option>

            <option {{ isset($data) && $data->content_position=="Top"?"selected":'' }}>Top</option>

            <option {{ isset($data) && $data->content_position=="Middle"?"selected":'' }}>Middle</option>

            <option {{ isset($data) && $data->content_position=="Footer"?"selected":'' }}>Footer</option>

          </select>

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6 ">

          <label for="name">Position Priority</label>

          <select class="form-control" name="position">

            @for ($i =1 ; $i <=10 ; $i++) @if(isset($data) && $data->content_priority==$i)
              <option selected="">{{ $i }}</option>
              @else
              <option>{{ $i }}</option>
              @endif
              @endfor

          </select>

        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-6 hide">

          <label for="attribute">What will apply on banner?</label><br>

          <input type="radio" class="form-check-label pricingType" value="discount" name="pricingType" {{ isset($data) && $data->pricing_type=="discount"?"checked='checked'":'' }}> Product Discount &nbsp;&nbsp;&nbsp;

          <input type="radio" class="form-check-label pricingType" value="price" name="pricingType" {{ isset($data) && $data->pricing_type=="price"?"checked='checked'":'' }}> Product Price

        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-6 hide">

          <label for="attribute" id="minLabel"></label><br>

          <input type="number" class="form-control" name="minPricing" {{ isset($data->min_pricing)?$data->min_pricing:'' }}>

        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-6 hide">

          <label for="attribute" id="maxLabel"></label><br>

          <input type="number" class="form-control" name="maxPricing" {{ isset($data->min_pricing)?$data->max_pricing:'' }}>

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6">

          <label for="name">Add Product Categories(optional)</label>
          @php $i=0 @endphp
          <select class="form-control select2" name="cat" required>
            @if(isset($categories))
            @foreach($categories as $item)
            <option value="{{ $item->id }}" selected="">{{ $item->name }}</option>
            @endforeach
            @endif
            @isset($category)
            @foreach ($category as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
            @endisset
          </select>

        </div>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6 bannerImage hide">

          <label for="name">Larger Screen Banner Image
            @if(isset($data) && $data->images)
            <img src="{{ url('') }}/{{ $data->images }}" style="height:60px;width:60px;">
            @endif
          </label>

          <input type="file" name="banner" class="form-control" value="">

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6 bannerImage hide">

          <label for="name">Mobile Screen Banner Image
            @if(isset($data) && $data->mobile_banner)
            <img src="{{ url('') }}/{{ $data->mobile_banner }}" style="height:60px;width:60px;">
            @endif
          </label>

          <input type="file" name="mobile_banner" class="form-control" value="">

        </div>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6 totalProduct tot">

          <label for="name">Total Number of Product to show</label>

          <input type="number" name="totproduct" class="form-control" value="{{ isset($data->total_product_to_show)?$data->total_product_to_show:'' }}">

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6 totalRow tot">

          <label for="name">Total Number of Product in one row</label>

          <input type="number" name="productrow" class="form-control" value="{{ isset($data->total_product_in_row)?$data->total_product_in_row:'' }}">

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-12 title">

          <label for="name">Content Title (optional)</label>

          <input type="text" id="name" name="title" class="form-control" value="{{ isset($data->content_title)?$data->content_title:'' }}" value="">

          <input type="hidden" id="name" name="id" class="form-control" value="{{ isset($data->id)?$data->id:'' }}">
        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-12 content">

          <label for="email">Contents</label>

          <textarea style="height:400px !important;" class="editor1 form-control" name="content">{{isset($data->contents)?$data->contents:'' }}</textarea>

        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-12">

          <label for="attribute">Apply Attribute</label><br>

          <input type="radio" class="form-check-label" value="best seller" name="newA" {{ isset($data) && $data->attributes=="best seller"?"checked='checked'":'' }}> Best Seller &nbsp;&nbsp;&nbsp;

          <!--  <input type="radio" class="form-check-label" value="brand" name="newA"> Show By Brand-->
          <input type="radio" class="form-check-label" value="latest" name="newA" {{ isset($data) && $data->attributes=="latest"?"checked='checked'":'' }}> New Arrival&nbsp;&nbsp;&nbsp;

        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-12">

          <label for="attribute">Show As A</label><br>

          <input type="radio" class="form-check-label" value="slider" name="showas" {{ isset($data) &&
                 $data->show_as=="slider"?"checked='checked'":'' }}> Slider Image &nbsp;&nbsp;&nbsp;

          <input type="radio" class="form-check-label" value="single" name="showas" {{ isset($data) &&
                 $data->show_as=="single"?"checked='checked'":'' }}> Single Image

        </div>

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-12">

          <label for="name">Meta keyword</label>

          <input type="text" id="name" name="meta_title" class="form-control" value="{{isset($data->meta_title)?$data->meta_title:'' }}">

        </div>

        <div class="form-group {{$errors->has('email')?'has-error' : '' }} col-sm-12">

          <label for="email">Meta Description</label>

          <textarea class=" form-control" name="meta_keyword">{{isset($data->meta_description)?$data->meta_description:'' }}</textarea>

        </div>

        <div>

          <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }} & Update">

        </div>

      </div>

    </form>

  </div>

</div>

@push('ajax-script')

<script type="text/javascript">
  $(document).ready(function() {
    @if(isset($data) && $data - > page_module == "Offer Banner")

    $(".hide").show();

    $(".tot").hide();

    $(".post").hide();
    @elseif(isset($data) && $data - > page_module == "Product Banner")
    $(".hide").show();

    $(".tot").hide();

    $(".post").hide();
    @elseif(isset($data) && $data - > page_module == "Display Products")
    $(".hide").hide();

    $(".tot").show();

    $(".post").show();

    $(".pricingType").prop('checked', false);
    @endif


    $(document).on('change', '.module', function(event) {

      let v = $('.module option:selected').text();

      if (v == "Offer Banner") {

        $(".hide").show();

        $(".tot").hide();

        $(".post").hide();

      } else if (v == "Product Banner") {

        $(".hide").show();

        $(".tot").hide();

        $(".post").hide();

      } else if (v == "Display Products") {

        $(".hide").hide();

        $(".tot").show();

        $(".post").show();

        $(".pricingType").prop('checked', false);

      }

    });

    $(document).on('click', '.pricingType', function(event) {

      let v = $(this).val()

      if (v == "discount") {

        $('#minLabel').text("Minimum Product Discount in(%)");

        $('#maxLabel').text("Maximum Product Discount in(%)");

      }
      if (v == "price") {

        $('#minLabel').text("Minimum Product Price");

        $('#maxLabel').text("Maximum Product Price");

      }

    });

  });
</script>

@endpush

@endsection