@extends('layouts.site')

@section('content')
    <div class="search-section">
        <div class="container-fluid">
            <div class="row">
                <div class="flex-fixed-width-item-search">
                    {{ Form::open(array('route' => 'HomePage', 'method' => 'get' ,'autocomplete' => 'off', 'class' => 'sticky')) }}
                    {{ Form::hidden('site_search', 1) }}
                    {{ Form::hidden('page', 1, array('id' => 'page-number')) }}
                    <div class="hero-search-holder">
                        <div class="form-group">
                            <label for="where">{{ trans('main.where') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                </div>
                                <div class="city-input">
                                    {{ Form::text('city', $city, array('id' => 'where', 'class' => 'form-control typeahead',
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
                                {{ Form::text('when', $picker_date, array('id' => 'when', 'class' => 'form-control',
                                    'placeholder' => trans('main.choose_dates'))) }}
                                <div class="input-group-btn-vertical">
                                    <button class="btn btn-default clear-selection" type="button"><i class="fa fa-close"></i></button>
                                </div>
                            </div>
                            {{ Form::hidden('start_date', $start_date, array('id' => 'start-date')) }}
                            {{ Form::hidden('end_date', $end_date, array('id' => 'end-date')) }}
                        </div>
                        <div class="form-group">
                            <label for="guests">{{ trans('main.guests') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-users" aria-hidden="true"></i></div>
                                </div>
                                {{ Form::text('guests', $guests, array('id' => 'guests', 'class' => 'form-control',
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
                        <div class="advanced-search-attributes">
                            <?php $i = 1; ?>
                            @foreach ($attributes as $attribute)
                                @if ($i % 2 == 1)
                                    <div class="row m-0">
                                        @endif
                                        <div class="col-sm-6 px-0 py-1">
                                            <div class="form-check">
                                                {{ Form::checkbox('attribute['.$attribute['id'].']', 'attribute', $attribute['checked'],
                                                    array('class' => 'form-check-input search-attributes',
                                                        'id' => 'attribute'.$attribute['id'], 'data-id' => $attribute['id'])) }}
                                                <label class="form-check-label" for="{{ 'attribute'.$attribute['id'] }}">
                                                    {{ $attribute['name'] }}
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
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="col-lg-5 col-md-12 col-12 search-villas-list" style="padding-bottom: 1rem">
                    <div class="row" id="villas-list"></div>
                    <div class="row mt-3 show-more-div" style="display: none">
                        <button type="button" class="btn btn-primary show-more-button">{{ trans('main.show_more_villas') }}</button>
                    </div>
                </div>
                <div class="col search-villas-list">
                    <div class="row sticky">
                        <div class="col-sm-12">
                            <div id="villa-map" class="search-villa-map"></div>
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
                    <form class="form-inline mb-30">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                <input type="email" class="form-control" id="subscribe"
                                    placeholder="{{ trans('main.enter_email') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ trans('main.subscribe') }}</button>
                    </form>
                    <p>{{ trans('main.spam_notice') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{ Form::hidden('latitude', null, array('class' => 'latitude')) }}
    {{ Form::hidden('longitude', null, array('class' => 'longitude')) }}

    <script>
        @foreach ($cities as $city)
            var city = '{{ $city->city }}';
            search_cities.push(city);
        @endforeach

        var is_search = 'T';
        var initial_slide = 0;
        var is_booking_page = 'F';
        var from_trans = '{{ trans('main.from') }}';
        var no_villas_trans = '{{ trans('main.no_villas_info') }}';
    </script>

    {{ HTML::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyC20q078OqQkTjcqA08JQHEpwqvIvLCI9c') }}

    {{ HTML::style('css/slick/slick.css') }}
    {{ HTML::style('css/slick/slick-theme.css') }}
    {{ HTML::script('js/slick.min.js') }}

    {{ HTML::script('js/functions/maps.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/slick.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/searchVillas.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/booking-calendar.js?v='.date('YmdHi')) }}
@endsection