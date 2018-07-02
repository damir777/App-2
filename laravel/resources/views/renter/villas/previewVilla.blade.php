@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.preview_villa') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'villa-form')) }}
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ trans('main.name') }}</label>
                                {{ Form::text('name', $villa->name, array('class' => 'form-control name', 'disabled')) }}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('main.map_tip') }}</label>
                                <div id="villa-map"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ trans('main.active') }}</label>
                                {{ Form::select('active', array('T' => trans('main.yes'), 'F' => trans('main.no')),
                                    $villa->active, array('class' => 'form-control active-status', 'disabled')) }}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('main.address') }}</label>
                                {{ Form::text('address', $villa->address, array('class' => 'form-control address', 'disabled')) }}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('main.city') }}</label>
                                {{ Form::text('city', $villa->city, array('class' => 'form-control city', 'disabled')) }}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('main.zip_code') }}</label>
                                {{ Form::text('zip_code', $villa->zip_code, array('class' => 'form-control zip-code', 'disabled')) }}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('main.featured') }}</label>
                                {{ Form::select('featured', array('T' => trans('main.yes'), 'F' => trans('main.no')),
                                    $villa->featured, array('class' => 'form-control featured', 'disabled')) }}
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.availability') }}</label>
                                        {{ Form::select('start_month', $months, $villa->start_month,
                                            array('class' => 'form-control start-month', 'disabled')) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        {{ Form::select('end_month', $months, $villa->end_month,
                                            array('class' => 'form-control end-month', 'disabled')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.season') }}</label>
                                        {{ Form::select('season_start_month', $months, $villa->season_start_month,
                                            array('class' => 'form-control season-start-month', 'disabled')) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        {{ Form::select('season_end_month', $months, $villa->season_end_month,
                                            array('class' => 'form-control season-end-month', 'disabled')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox collapsed m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.description') }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>{{ trans('main.language') }}</label>
                                    {{ Form::select('language', $languages_array, null,
                                        array('class' => 'form-control form-language')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($languages_array as $key => $language)
                                <?php $short_description_column_name = $key.'_short_description';
                                $description_column_name = $key.'_description'; ?>
                                <div id="{{ $key }}_div" @if ($key != 'en') {{ 'style=display:none' }} @endif>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.short_description').' ('.$language.')' }}</label>
                                            {{ Form::textarea($key.'_short_description',
                                                $villa->$short_description_column_name,
                                                array('class' => 'form-control '.$key.'-short-description',
                                                'rows' => 6, 'disabled')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.description').' ('.$language.')' }}</label>
                                            {{ Form::textarea($key.'_description', $villa->$description_column_name,
                                                array('class' => 'form-control '.$key.'-description', 'rows' => 6, 'disabled')) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="ibox collapsed m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.attributes') }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group attributes-list">
                                    <?php $j = 1; ?>
                                    @foreach ($villa->attributes as $attribute)
                                        @if ($j % 4 == 1)
                                            <div class="row">
                                        @endif
                                        <div class="col-sm-3 attribute-checkboxes">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="checkbox m-r-xs">
                                                        {{ Form::checkbox('attribute_'.$attribute['id'], 'attribute',
                                                            $attribute['checked'], array('class' => 'attribute-checkbox',
                                                            'data-attribute-id' => $attribute['id'],
                                                            'data-va-id' => $attribute['va_id'], 'disabled')) }}
                                                        <label>{{ $attribute['name'] }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    @if ($attribute['is_input'] == 'T')
                                                        @if ($attribute['featured'] == 'F')
                                                            <div class="input-group">
                                                                {{ Form::text('value_'.$attribute['id'], $attribute['value'],
                                                                    array('class' => 'form-control attribute-value', 'disabled')) }}
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default attribute-translation"
                                                                        data-attribute-id="{{ $attribute['id'] }}" disabled="disabled">
                                                                        {{ trans('main.translate') }}
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        @else
                                                            {{ Form::text('value_'.$attribute['id'], $attribute['value'],
                                                                array('class' => 'form-control attribute-value', 'disabled')) }}
                                                        @endif
                                                    @else
                                                        {{ Form::text('value_'.$attribute['id'], $attribute['value'],
                                                            array('class' => 'form-control attribute-value', 'disabled')) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if ($j % 4 == 0)
                                            </div><hr>
                                        @endif
                                        <?php $j++; ?>
                                    @endforeach
                                    @if (($j - 1) % 4 != 0)
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox collapsed m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.prices') }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="prices">
                            <?php $counter = 1; ?>
                            @foreach ($villa->prices as $price)
                                <div class="price-element">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>{{ trans('main.start_day') }}</label>
                                                @if ($counter == 1)
                                                    {{ Form::text('start_day', $price['start_day'],
                                                        array('class' => 'form-control start-day prices-datepicker', 'disabled')) }}
                                                @else
                                                    {{ Form::text('start_day', $price['start_day'],
                                                        array('class' => 'form-control start-day prices-datepicker', 'disabled')) }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>{{ trans('main.end_day') }}</label>
                                                {{ Form::text('end_day', $price['end_day'],
                                                    array('class' => 'form-control end-day prices-datepicker', 'disabled')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>{{ trans('main.price') }} (€)</label>
                                                {{ Form::text('price', $price['price'], array('class' => 'form-control price',
                                                    'disabled')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $counter++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="ibox collapsed m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.policies') }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ trans('main.deposit') }}</label>
                                    {{ Form::text('deposit', $villa->deposit, array('class' => 'form-control deposit', 'disabled')) }}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('main.cash_payment') }}</label>
                                    {{ Form::select('cash_payment', array('T' => trans('main.yes'), 'F' => trans('main.no')),
                                        $villa->cash_payment, array('class' => 'form-control cash-payment', 'disabled')) }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ trans('main.pets_price') }}</label>
                                    {{ Form::text('pets_price', $villa->pets_price,
                                        array('class' => 'form-control pets-price', 'disabled')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox collapsed m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.discounts') }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>{{ trans('main.discount_type') }}</label>
                                    {{ Form::select('discount_type', array(0 => '', 1 => trans('main.first_minute'),
                                        2 => trans('main.last_minute')), $villa->discount_type,
                                        array('class' => 'form-control discount-type', 'disabled')) }}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>{{ trans('main.percent') }}</label>
                                    {{ Form::text('discount', $villa->discount,
                                        array('class' => 'form-control discount', 'disabled')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.featured_images') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="lightBoxGallery featured-images" style="margin-top: -5px">
                                    @foreach ($villa->featured_images as $image)
                                        <div class="image-wrapper">
                                            <a href="{{ $villa->image_path.$image->image }}" data-gallery="">
                                                {{ HTML::image('images/'.$villa->id.'/'.$image->image, '',
                                                    array('style' => 'height: 120px')) }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox m-t-md">
                    <div class="ibox-title">
                        <h5>{{ trans('main.images') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="lightBoxGallery images" style="margin-top: -5px">
                                    @foreach ($villa->images as $image)
                                        <div class="image-wrapper">
                                            <a href="{{ $villa->image_path.$image->image }}" data-gallery="">
                                                {{ HTML::image('images/'.$villa->id.'/'.$image->image, '',
                                                    array('style' => 'height: 120px')) }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="blueimp-gallery" class="blueimp-gallery">
                    <div class="slides"></div>
                    <h3 class="title"></h3>
                    <a class="prev">‹</a>
                    <a class="next">›</a>
                    <a class="close">×</a>
                    <a class="play-pause"></a>
                    <ol class="indicator"></ol>
                </div>
            </div>
        </div>
        {{ Form::hidden('latitude', $villa->latitude, array('class' => 'latitude')) }}
        {{ Form::hidden('longitude', $villa->longitude, array('class' => 'longitude')) }}
        {{ Form::close() }}
    </div>

    <script>
        var is_search = 'F';
    </script>

    {{ HTML::script('http://maps.googleapis.com/maps/api/js?key=AIzaSyC20q078OqQkTjcqA08JQHEpwqvIvLCI9c') }}
    {{ HTML::script('js/functions/maps.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/villas.js?v='.date('YmdHi')) }}
@endsection