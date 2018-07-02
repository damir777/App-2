@extends('layouts.site')

@section('content')
    <div class="hero-section">
        <div class="container-fluid">
            <div class="row">
                <div class="flex-fixed-width-item">
                    {{ Form::open(array('route' => 'HomePage', 'method' => 'get', 'autocomplete' => 'off')) }}
                    {{ Form::hidden('site_search', 1) }}
                    <div class="hero-search-holder">
                        <div class="form-group">
                            <label for="where">{{ trans('main.where') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                </div>
                                <div class="city-input">
                                {{ Form::text('city', null, array('id' => 'where', 'class' => 'form-control typeahead',
                                    'placeholder' => trans('main.choose_location'))) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="when">{{ trans('main.when') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                </div>
                                {{ Form::text('when', null, array('id' => 'when', 'class' => 'form-control',
                                    'placeholder' => trans('main.choose_dates'))) }}
                                    <div class="input-group-btn-vertical">
                                        <button class="btn btn-default clear-selection" type="button"><i class="fa fa-close"></i></button>
                                    </div>
                            </div>
                            {{ Form::hidden('start_date', null, array('id' => 'start-date')) }}
                            {{ Form::hidden('end_date', null, array('id' => 'end-date')) }}
                        </div>
                        <div class="form-group">
                            <label for="guests">{{ trans('main.guests') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-users" aria-hidden="true"></i></div>
                                </div>
                                {{ Form::text('guests', null, array('id' => 'guests', 'class' => 'form-control',
                                    'placeholder' => trans('main.number_of_guests'))) }}
                                <div class="input-group-btn-vertical">
                                    <button class="btn btn-default guests-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-default guests-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">
                            {{ trans('main.search') }} <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        <p>{{ trans('main.more_options') }}</p>
                        <a href="#" id="advanced-search">{{ trans('main.advanced_search') }}</a>
                    </div>
                    <div class="advanced-search-holder">
                        <a href="#" id="close-advanced-search"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php $i = 1; ?>
                        @foreach ($attributes as $attribute)
                            @if ($i % 2 == 1)
                                <div class="row m-0">
                            @endif
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        {{ Form::checkbox('attribute['.$attribute->id.']', 'attribute', null,
                                            array('class' => 'form-check-input', 'id' => 'attribute'.$attribute->id)) }}
                                        <label class="form-check-label" for="{{ 'attribute'.$attribute->id }}">
                                            {{ $attribute->$attribute_column_name }}
                                        </label>
                                    </div>
                                </div>
                            @if ($i % 2 == 0)
                                </div>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                        @if (($i - 1) % 2 != 0)
                            </div>
                        @endif
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="homepage-slider-wrapper">
                    <div class="homepage-slider">
                        @foreach ($slider as $image)
                            <div class="initialize-slick">
                                <a href="{{ route('GetVillaDetails', $image->villa->url_name) }}">
                                    <div class="homepage-slider-text">
                                        <h3>{{ $image->villa->name }}</h3>
                                        <p class="address">{{ $image->villa->city }}</p>
                                        <p class="homepage-slider-attributes">
                                            @foreach ($image->villa->featured_attributes as $attribute)
                                                <span>
                                                    {{ HTML::image('icon/'.$attribute->attribute->icon, '') }}
                                                    {{ $attribute->value }}
                                                </span>
                                            @endforeach
                                        </p>
                                    </div>
                                </a>
                                {{ HTML::image('images/'.$image->villa_id.'/'.$image->image, '', array('class' => 'img-fluid')) }}
                            </div>
                        @endforeach
                        <div class="initialize-slick">
                            <div class="homepage-slider-default-text">
                                <h1>{{ trans('main.enjoy_with_all_senses') }}!</h1>
                                <h2>{{ trans('main.slider_text') }}</h2>
                            </div>
                            {{ HTML::image('img/rovinj.jpg', '', array('class' => 'img-fluid')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="special-offers">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    {{ HTML::image('img/so-1.png', '', array('class' => 'img-fluid')) }}
                </div>
                <div class="col-sm-4">
                    {{ HTML::image('img/so-2.png', '', array('class' => 'img-fluid')) }}
                </div>
                <div class="col-sm-4">
                    {{ HTML::image('img/so-3.png', '', array('class' => 'img-fluid')) }}
                </div>
            </div>
        </div>
    </div>
    -->
    <div class="featured-villas">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 heading">
                    <h4>{{ trans('main.take_a_look') }}</h4>
                    <h3>{{ trans('main.featured_villas') }}</h3>
                </div>
            </div>
            <div class="row mt-60">
                @foreach ($villas as $villa)
                    <div class="col-lg-4 offset-lg-0 col-md-6 offset-md-0 col-sm-8 offset-sm-2 col-10 offset-1">
                        <div class="villa-outter">
                            <div class="villa-inner">
                                @if ($villa->discount)
                                    <div class="villa-discount">
                                        <p>{{ $villa->discount_text }}</p>
                                    </div>
                                @endif
                                <div class="slider slider-without-navigation">
                                    @foreach ($villa->images as $image)
                                        <div>
                                            {{ HTML::image('images/thumbs/'.$villa->id.'/'.$image->image, '',
                                                array('class' => 'img-fluid')) }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="villa-inner-content">
                                    <a href="{{ route('GetVillaDetails', $villa->url_name) }}"
                                       class="villa-name-link"><h4>{{ $villa->name }}</h4>
                                    </a>
                                    <div class="row">
                                        <div class="col-sm-9 address">
                                            <p><i class="fa fa-map-pin" aria-hidden="true"></i> {{ $villa->city }}</p>
                                        </div>
                                        <!--
                                        <div class="col-sm-3 rating">
                                            <p><i class="fa fa-star" aria-hidden="true"></i> 4.9</p>
                                        </div>
                                        -->
                                    </div>
                                    <div class="row short-description">
                                        <div class="col-sm-12">
                                            <p>{{ $villa->$desc_column_name }}</p>
                                        </div>
                                    </div>
                                    <div class="row accm-info">
                                        @foreach ($villa->featured_attributes as $attribute)
                                            <div class="col-sm-6 col-6 text-center">
                                                <p>
                                                    {{ HTML::image('icon/'.$attribute->attribute->icon, '') }}
                                                    {{ $attribute->value }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row text-center">
                                        <a href="{{ route('GetVillaDetails', $villa->url_name) }}"
                                           class="btn btn-primary">{{ trans('main.from') }} <span>{{ $villa->price }}</span>
                                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--
    <div class="destinations">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 heading">
                    <h4>{{ trans('main.feel_adventure') }}</h4>
                    <h3>{{ trans('main.beautiful_destinations') }}</h3>
                </div>
            </div>
            <div class="row mt-60">
                <div class="col-md-5">
                    <a href="{{ $destination_posts[0]->link }}" class="article-anchor" target="_blank">
                        <div class="destinations-outter-big" style="background-image:url({{ $destination_posts[0]->image }});">
                            <div class="destinations-inner">
                                <h3>{{ $destination_posts[0]->title }}</h3>
                                <p>Lorem ipsum dolor sit</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-7">
                    <div class="row mb-30">
                        <div class="col-sm-6">
                            <a href="{{ $destination_posts[1]->link }}" class="article-anchor" target="_blank">
                                <div class="destinations-outter-small" style="background-image:url({{ $destination_posts[1]->image }});">
                                    <div class="destinations-inner">
                                        <h3>{{ $destination_posts[1]->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ $destination_posts[2]->link }}" class="article-anchor" target="_blank">
                                <div class="destinations-outter-small" style="background-image:url({{ $destination_posts[2]->image }});">
                                    <div class="destinations-inner">
                                        <h3>{{ $destination_posts[2]->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ $destination_posts[3]->link }}" class="article-anchor" target="_blank">
                                <div class="destinations-outter-small" style="background-image:url({{ $destination_posts[3]->image }});">
                                    <div class="destinations-inner">
                                        <h3>{{ $destination_posts[3]->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ $destination_posts[4]->link }}" class="article-anchor" target="_blank">
                                <div class="destinations-outter-small" style="background-image:url({{ $destination_posts[4]->image }});">
                                    <div class="destinations-inner">
                                        <h3>{{ $destination_posts[4]->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
    <div class="adventures">
        <div class="top-fader">
            <h2>{{ trans('main.enjoy_with_all_senses') }}</h2>
            <h3>{{ trans('main.adventures_every_day') }}</h3>
        </div>
        <div class="bottom-fader"></div>
    </div>
    <div class="read">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 heading">
                    <h3>{{ trans('main.read_and_enjoy') }}</h3>
                </div>
            </div>
            <div class="row mt-60">
                <div class="col-lg-6">
                    <div class="row mb-30">
                        <div class="col-sm-12">
                            <a href="{{ $home_posts[0]->link }}" class="article-anchor" target="_blank">
                                <div class="blog-post-outter-big" style="background-image:url({{ $home_posts[0]->image }});">
                                    <div class="blog-post-inner">
                                        <h4>{{ $home_posts[0]->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ $home_posts[1]->link }}" class="article-anchor" target="_blank">
                                <div class="blog-post-outter-small" style="background-image:url({{ $home_posts[1]->image }});">
                                    <div class="blog-post-inner">
                                        <h4>{{ $home_posts[1]->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ $home_posts[2]->link }}" class="article-anchor" target="_blank">
                                <div class="blog-post-outter-small" style="background-image:url({{ $home_posts[2]->image }});">
                                    <div class="blog-post-inner">
                                        <h4>{{ $home_posts[2]->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mb-30">
                        <div class="col-sm-6">
                            <a href="{{ $home_posts[3]->link }}" class="article-anchor" target="_blank">
                                <div class="blog-post-outter-small" style="background-image:url({{ $home_posts[3]->image }});">
                                    <div class="blog-post-inner">
                                        <h4>{{ $home_posts[3]->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ $home_posts[4]->link }}" class="article-anchor" target="_blank">
                                <div class="blog-post-outter-small" style="background-image:url({{ $home_posts[4]->image }});">
                                    <div class="blog-post-inner">
                                        <h4>{{ $home_posts[4]->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{ $home_posts[5]->link }}" class="article-anchor" target="_blank">
                                <div class="blog-post-outter-big" style="background-image:url({{ $home_posts[5]->image }});">
                                    <div class="blog-post-inner">
                                        <h4>{{ $home_posts[5]->title }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="subscribe">
        <div class="top-fader"></div>
        <div class="container">
            <div class="row mb-30">
                <div class="col-sm-12">
                    <h2>{{ trans('main.subscribe') }}</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-5">
                    <form action="https://xx.us17.list-manage.com/subscribe/post?u={{ $mail_chimp['action'] }}"
                          method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                          class="form-inline mb-30 validate" target="_blank" novalidate>
                        <div class="form-group">
                            <div class="input-group subscribe-wrapper">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                </div>
                                <input type="email" value="" name="EMAIL" class="email form-control" id="mce-EMAIL"
                                    placeholder="{{ trans('main.enter_email') }}" required>
                                <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                    <input type="text" name="{{ $mail_chimp['name'] }}" tabindex="-1" value="">
                                </div>
                                <div class="clear">
                                    <button type="submit" class="btn btn-primary" id="mc-embedded-subscribe">
                                        {{ trans('main.subscribe') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <p>{{ trans('main.spam_notice') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        @foreach ($cities as $city)
            var city = '{{ $city->city }}';
            search_cities.push(city);
        @endforeach

        var initial_slide = parseInt('{{ $initial_slide }}');
        var is_booking_page = 'F';
    </script>

    {{ HTML::style('css/slick/slick.css') }}
    {{ HTML::style('css/slick/slick-theme.css') }}
    {{ HTML::script('js/slick.min.js') }}

    {{ HTML::script('js/functions/slick.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/booking-calendar.js?v='.date('YmdHi')) }}
@endsection