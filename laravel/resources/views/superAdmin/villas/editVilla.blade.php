@extends('layouts.main')

@section('content')
    <div class="page-heading-wrapper" data-spy="affix" data-offset-top="60">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-6">
                <h2 class="page-title">{{ trans('main.edit_villa') }}</h2>
            </div>
            <div class="col-xs-6">
                <div class="title-action">
                    <button href="#" class="btn btn-warning cancel" data-toggle="tooltip" data-placement="top"
                        title="{{ trans('main.cancel') }}">{{ trans('main.cancel') }}
                    </button>
                    <button class="btn btn-primary update" data-toggle="tooltip" data-placement="top"
                        title="{{ trans('main.save') }}">{{ trans('main.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row affix-padding">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off', 'class' => 'villa-form')) }}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.name') }}</label>
                                        {{ Form::text('name', $villa->name, array('class' => 'form-control name')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.url_name') }}</label>
                                        {{ Form::text('url_name', $villa->url_name, array('class' => 'form-control url-name')) }}
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
                                            $villa->active, array('class' => 'form-control active-status')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.address') }}</label>
                                        {{ Form::text('address', $villa->address, array('class' => 'form-control address')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.city') }}</label>
                                        {{ Form::text('city', $villa->city, array('class' => 'form-control city')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.zip_code') }}</label>
                                        {{ Form::text('zip_code', $villa->zip_code, array('class' => 'form-control zip-code')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.renter') }}</label>
                                        {{ Form::select('renter', $renters, $villa->renter_id,
                                            array('class' => 'form-control renter')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.featured') }}</label>
                                        {{ Form::select('featured', array('T' => trans('main.yes'), 'F' => trans('main.no')),
                                            $villa->featured, array('class' => 'form-control featured')) }}
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ trans('main.availability') }}</label>
                                                {{ Form::select('start_month', $months, $villa->start_month,
                                                    array('class' => 'form-control start-month')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                {{ Form::select('end_month', $months, $villa->end_month,
                                                    array('class' => 'form-control end-month')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ trans('main.season') }}</label>
                                                {{ Form::select('season_start_month', $months, $villa->season_start_month,
                                                    array('class' => 'form-control season-start-month')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                {{ Form::select('season_end_month', $months, $villa->season_end_month,
                                                    array('class' => 'form-control season-end-month')) }}
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
                                                        'rows' => 6)) }}
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>{{ trans('main.description').' ('.$language.')' }}</label>
                                                    {{ Form::textarea($key.'_description', $villa->$description_column_name,
                                                        array('class' => 'form-control '.$key.'-description', 'rows' => 6)) }}
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
                                                                    'data-va-id' => $attribute['va_id'])) }}
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
                                                                            array('class' => 'form-control attribute-value')) }}
                                                                        <span class="input-group-btn">
                                                                            <button type="button"
                                                                                class="btn btn-default attribute-translation"
                                                                                data-attribute-id="{{ $attribute['id'] }}">
                                                                                {{ trans('main.translate') }}
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                @else
                                                                    {{ Form::text('value_'.$attribute['id'], $attribute['value'],
                                                                        array('class' => 'form-control attribute-value')) }}
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
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-success btn-sm add-price">{{ trans('main.add') }}</button>
                                    </div>
                                </div>
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
                                                                array('class' => 'form-control start-day prices-datepicker')) }}
                                                        @else
                                                            {{ Form::text('start_day', $price['start_day'],
                                                                array('class' => 'form-control start-day prices-datepicker')) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>{{ trans('main.end_day') }}</label>
                                                        {{ Form::text('end_day', $price['end_day'],
                                                            array('class' => 'form-control end-day prices-datepicker')) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>{{ trans('main.price') }} (€)</label>
                                                        {{ Form::text('price', $price['price'], array('class' => 'form-control price')) }}
                                                    </div>
                                                </div>
                                                @if ($counter > 1)
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-danger remove-price"
                                                                style="display: block">
                                                                {{ trans('main.delete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
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
                                            {{ Form::text('deposit', $villa->deposit, array('class' => 'form-control deposit')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.cash_payment') }}</label>
                                            {{ Form::select('cash_payment', array('T' => trans('main.yes'),
                                                'F' => trans('main.no')), $villa->cash_payment,
                                                array('class' => 'form-control cash-payment')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.pets_price') }}</label>
                                            {{ Form::text('pets_price', $villa->pets_price,
                                                array('class' => 'form-control pets-price')) }}
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
                                                array('class' => 'form-control discount-type')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>{{ trans('main.percent') }}</label>
                                            {{ Form::text('discount', $villa->discount, array('class' => 'form-control discount')) }}
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
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::file('image',
                                                        array('class' => 'form-control featured-image-input')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lightBoxGallery featured-images" style="margin-top: -5px">
                                            @foreach ($villa->featured_images as $image)
                                                <div class="image-wrapper">
                                                    <div class="delete-image" data-id="{{ $image->id }}" data-is-featured="T"
                                                        title="{{ trans('main.delete') }}">
                                                        <i class="fa fa-close"></i>
                                                    </div>
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
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::file('image', array('class' => 'form-control image-input')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lightBoxGallery images" style="margin-top: -5px">
                                            @foreach ($villa->images as $image)
                                                <div class="image-wrapper">
                                                    <div class="delete-image" data-id="{{ $image->id }}" data-is-featured="F"
                                                        title="{{ trans('main.delete') }}">
                                                        <i class="fa fa-close"></i>
                                                    </div>
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
        </div>
    </div>

    @include('modals.cropper')
    @include('modals.attributeTranslation')

    <script>
        var is_search = 'F';
        var is_insert = 'F';
        var villa_id = '{{ $villa->id }}';
        var delete_trans = '{{ trans('main.delete') }}';
        var start_day_trans = '{{ trans('main.start_day') }}';
        var end_day_trans = '{{ trans('main.end_day') }}';
        var price_trans = '{{ trans('main.price') }}';
        var missing_location = '{{ trans('errors.missing_location') }}';
        var translation_insert = '{{ trans('main.translation_insert') }}';
        var translation_delete = '{{ trans('main.translation_delete') }}';
    </script>

    {{ HTML::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyC20q078OqQkTjcqA08JQHEpwqvIvLCI9c') }}
    {{ HTML::script('js/functions/maps.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/villas.js?v='.date('YmdHi')) }}

    <script>
        @foreach ($villa->attributes as $attribute)
            @if ($attribute['checked'] && count($attribute['translations']) > 0)
                attribute_translations['{{ $attribute['id'] }}'] = {'en': '{{ $attribute['translations'][$attribute['id']]['en'] }}',
                    'hr': '{{ $attribute['translations'][$attribute['id']]['hr'] }}',
                    'de': '{{ $attribute['translations'][$attribute['id']]['de'] }}',
                    'fr': '{{ $attribute['translations'][$attribute['id']]['fr'] }}',
                    'it': '{{ $attribute['translations'][$attribute['id']]['it'] }}',
                    'ru': '{{ $attribute['translations'][$attribute['id']]['ru'] }}',
                    'dk': '{{ $attribute['translations'][$attribute['id']]['dk'] }}',
                    'no': '{{ $attribute['translations'][$attribute['id']]['no'] }}',
                    'sv': '{{ $attribute['translations'][$attribute['id']]['sv'] }}'};
            @endif
        @endforeach

        @foreach ($villa->prices as $price)
            var price_object = {start_day: '{{ $price['start_day'] }}', end_day: '{{ $price['end_day'] }}', price: '{{ $price['price'] }}'};
            prices_array.push(price_object);
        @endforeach
    </script>
@endsection