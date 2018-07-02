@extends('layouts.site')

@section('content')
    <div class="single-content">
        <div class="container">
            <div class="main-info-holder">
                <div class="row no-gutters">
                    <div class="col-sm-12 col-md-12 col-lg-8">
                        <div class="villa-slider-wrapper">
                            @if ($villa->discount)
                                <div class="villa-discount">
                                    <p>{{ $villa->discount_text }}</p>
                                </div>
                            @endif
                            <div class="slider slider-for">
                                @foreach ($villa->images as $image)
                                    <div class="initialize-slick">
                                        <a href="{{ url('images/'.$villa->id.'/'.$image) }}" data-fancybox="group">
                                            <img src="{{ url('images/'.$villa->id.'/'.$image) }}" class="img-fluid">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="slider slider-nav">
                                @foreach ($villa->images as $image)
                                    <div class="initialize-slick">
                                        <img alt="Preview Image 1" src="{{ url('images/thumbs/'.$villa->id.'/'.$image) }}"
                                             class="img-fluid">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4 main-info-text">
                        <div class="main-info-text-wrapper">
                            <div class="row main-info-top">
                                <div class="col-sm-12 text-center">
                                    <h2>{{ $villa->name }}</h2>
                                </div>
                                <!--
                                <div class="col-sm-4 reviews">
                                    <p>
                                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                                    </p>
                                    <p>
                                        22 reviews
                                    </p>
                                </div>
                                -->
                            </div>
                            <!--
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="reviews-carousel" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <li data-target="#reviews-carousel" data-slide-to="0" class="active"></li>
                                            <li data-target="#reviews-carousel" data-slide-to="1"></li>
                                            <li data-target="#reviews-carousel" data-slide-to="2"></li>
                                        </ol>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <p>“Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque…”</p>
                                        </div>
                                        <div class="carousel-item">
                                            <p>“Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque…”</p>
                                        </div>
                                        <div class="carousel-item">
                                            <p>“Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque…”</p>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row ratings-stats no-gutters">
                                <div class="col-sm-6">
                                    <h4>97% <span>recomend this</span> </h4>
                                </div>
                                <div class="col-sm-6">
                                    <h4>4.9 <span>rating</span> </h4>
                                </div>
                            </div>
                            -->
                            <div class="row ratings-stats-price no-gutters">
                                <div class="col-sm-12">
                                    <p>{{ trans('main.from') }} <span>{{ $villa->price }}</span> {{ trans('main.per_week') }}</p>
                                </div>
                            </div>
                            <div class="row book-btn-holder text-center">
                                <div class="col-sm-12">
                                    <a href="{{ route('GetBookingPage', $villa->url_name) }}" class="btn btn-primary">
                                        {{ trans('main.book_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if ($villa->similar_villas->isNotEmpty())
                            <div class="side-widget similar-villas similiar-villas-top">
                                <div class="side-widget-heading-orange">
                                    <h4>{{ trans('main.similar_villas') }}</h4>
                                </div>
                                <div class="side-widget-content">
                                    @foreach ($villa->similar_villas as $similar_villa)
                                        <div class="row villa-side">
                                            <div class="col-sm-6">
                                                {{ HTML::image('images/thumbs/'.$similar_villa->id.'/'.
                                                    $similar_villa->images[0]->image, '',
                                                    array('class' => 'img-fluid similar-villa-img')) }}
                                            </div>
                                            <div class="col-sm-6 p-0">
                                                <h3>
                                                    <a href="{{ route('GetVillaDetails', $similar_villa->url_name) }}">
                                                        {{ $similar_villa->name }}
                                                    </a>
                                                </h3>
                                                <p class="location">
                                                    <i class="fa fa-map-pin" aria-hidden="true"></i>
                                                    {{ $similar_villa->city }}
                                                </p>
                                                @foreach ($similar_villa->featured_attributes as $attribute)
                                                    <span class="q-info">
                                                        {{ HTML::image('icon/'.$attribute->attribute->icon, '') }}
                                                        {{ $attribute->value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="villa-info">
                        <ul class="nav nav-pills nav-justified" id="villa-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="desc-tab" data-toggle="tab" href="#desc" role="tab"
                                   aria-controls="desc" aria-selected="true">{{ trans('main.description') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="avaliability-tab" data-toggle="tab" href="#avaliability" role="tab"
                                   aria-controls="avaliability" aria-selected="false">{{ trans('main.availability') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-tab" data-toggle="tab" href="#content" role="tab"
                                   aria-controls="content" aria-selected="false">{{ trans('main.amenities') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="policies-tab" data-toggle="tab" href="#policies" role="tab"
                                   aria-controls="policies" aria-selected="false">{{ trans('main.policies') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="villa-tabs-content">
                            <div class="tab-pane fade show active" id="desc" role="tabpanel" aria-labelledby="desc-tab">
                                <div class="row featured-attributes">
                                    @foreach ($villa->featured_attributes as $attribute)
                                        <div class="col-3 text-center">
                                            <p>
                                                {{ HTML::image('icon/'.$attribute->attribute->icon, '') }}
                                                {{ $attribute->value }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-7 mt-2">
                                        <p>{{ $villa->description }}</p>
                                    </div>
                                    <div class="col-md-5 p-0">
                                        <div id="villa-map"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="avaliability" role="tabpanel" aria-labelledby="avaliability-tab">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1 col-sm-12">
                                        {{ Form::hidden('id', $villa->id, array('id' => 'villa')) }}
                                        {{ Form::hidden('date', date('d.m.Y.'), array('id' => 'date')) }}
                                        <div class="calendar-wrapper">
                                            <div class="month mb-2">
                                                <div class="row">
                                                    <div class="col-sm-8 col-6 text-center">
                                                        <h5 id="calendar-date"></h5>
                                                    </div>
                                                    <div class="col-sm-2 col-3 text-center">
                                                        <div id="calendar-prev">
                                                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-3 text-center">
                                                        <div id="calendar-next">
                                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="day-names">
                                                <div class="row m-0">
                                                    <div class="col">
                                                        <p>{{ trans('main.monday') }}</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ trans('main.tuesday') }}</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ trans('main.wednesday') }}</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ trans('main.thursday') }}</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ trans('main.friday') }}</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ trans('main.saturday') }}</p>
                                                    </div>
                                                    <div class="col">
                                                        <p>{{ trans('main.sunday') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="days"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="content" role="tabpanel" aria-labelledby="content-tab">
                                <div class="amenities-container">
                                    @foreach ($villa->attributes as $attribute)
                                        <div class="row amenities-category">
                                            <div class="col-sm-12">
                                                <h6>{{ $attribute['category'] }}:</h6>
                                            </div>
                                        </div>
                                        <div class="row amenities-list">
                                            @foreach ($attribute['attributes'] as $single_attribute)
                                                <div class="col-sm-4">
                                                    <p>
                                                        @if ($single_attribute->value)
                                                            {{ $single_attribute->attribute->name.': '.
                                                            $single_attribute->value }}
                                                        @elseif ($single_attribute->en_value)
                                                            {{ $single_attribute->attribute->name.': '.
                                                            $single_attribute->trans_value }}
                                                        @else
                                                            {{ $single_attribute->attribute->name }}
                                                        @endif
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="policies" role="tabpanel" aria-labelledby="policies-tab">
                                <div class="row">
                                    <div class="col-sm-3 text-center">
                                        <p>{{ trans('main.deposit').': '.$villa->deposit }}</p>
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <p>{{ trans('main.pets_price').': '.$villa->pets_price }}</p>
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <p>{{ trans('main.cash_payment').': '.$villa->cash_payment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 villa-info-side">
                    <!--
                    <h5>12 people is currently looking this villa.</h5>
                    <h5>Booked 2 times in last 24 hours.</h5>
                    <div class="side-widget">
                        <div class="side-widget-content">
                            <p>“It was very comfortable to stay and staff were pleasant and welcoming.”</p>
                            <span class="customer">by Ellison from United Kingdom</span>
                        </div>
                    </div>
                    -->
                    <div class="side-widget">
                        <div class="side-widget-heading-blue p-3">
                            <h4>{{ trans('main.need_assistance') }}</h4>
                        </div>
                        <div class="side-widget-content p-3">
                            <p>{{ trans('main.assistance_info') }}</p>
                            <span class="support"><a href="tel:+385 31 445 490">xx</a> |
                                <a href="mailto:info@xx.com">info@xx.com</a>
                            </span>
                        </div>
                    </div>
                    <div class="side-widget similar-villas similiar-villas-bottom">
                        <div class="side-widget-heading-orange">
                            <h4>{{ trans('main.similar_villas') }}</h4>
                        </div>
                        <div class="side-widget-content">
                            @foreach ($villa->similar_villas as $similar_villa)
                                <div class="row villa-side">
                                    <div class="col-4">
                                        {{ HTML::image('images/thumbs/'.$similar_villa->id.'/'.
                                            $similar_villa->images[0]->image, '',
                                            array('class' => 'img-fluid similar-villa-img')) }}
                                    </div>
                                    <div class="col-8 p-0">
                                        <h3><a href="">{{ $similar_villa->name }}</a></h3>
                                        <p class="location">
                                            <i class="fa fa-map-pin" aria-hidden="true"></i>
                                            {{ $similar_villa->city }}
                                        </p>
                                        @foreach ($similar_villa->featured_attributes as $attribute)
                                            <span class="q-info">
                                                {{ HTML::image('icon/'.$attribute->attribute->icon, '') }}
                                                {{ $attribute->value }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::hidden('latitude', $villa->latitude, array('class' => 'latitude')) }}
    {{ Form::hidden('longitude', $villa->longitude, array('class' => 'longitude')) }}

    <script>
        var is_search = 'F';
        var initial_slide = 0;
        var is_booking_page = 'F';
    </script>

    {{ HTML::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyC20q078OqQkTjcqA08JQHEpwqvIvLCI9c') }}

    {{ HTML::style('css/fancybox.min.css') }}
    {{ HTML::style('css/slick/slick.css') }}
    {{ HTML::style('css/slick/slick-theme.css') }}
    {{ HTML::script('js/slick.min.js') }}
    {{ HTML::script('js/fancybox.min.js') }}

    {{ HTML::script('js/functions/maps.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/booking.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/slick.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/gallery.js?v='.date('YmdHi')) }}
@endsection