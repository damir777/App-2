@extends('layouts.main')

@section('content')
    <div class="page-heading-wrapper" data-spy="affix" data-offset-top="60">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-6">
                <h2 class="page-title">{{ trans('main.new_villa') }}</h2>
            </div>
            <div class="col-xs-6">
                <div class="title-action">
                    <button href="#" class="btn btn-warning cancel" data-toggle="tooltip" data-placement="top"
                        title="{{ trans('main.cancel') }}">{{ trans('main.cancel') }}
                    </button>
                    <button class="btn btn-primary insert" data-toggle="tooltip" data-placement="top"
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
                                        {{ Form::text('name', null, array('class' => 'form-control name')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.url_name') }}</label>
                                        {{ Form::text('url_name', null, array('class' => 'form-control url-name')) }}
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
                                            'T', array('class' => 'form-control active-status')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.address') }}</label>
                                        {{ Form::text('address', null, array('class' => 'form-control address')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.city') }}</label>
                                        {{ Form::text('city', null, array('class' => 'form-control city')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.zip_code') }}</label>
                                        {{ Form::text('zip_code', null, array('class' => 'form-control zip-code')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.renter') }}</label>
                                        {{ Form::select('renter', $renters, null, array('class' => 'form-control renter')) }}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.featured') }}</label>
                                        {{ Form::select('featured', array('T' => trans('main.yes'), 'F' => trans('main.no')),
                                            'F', array('class' => 'form-control featured')) }}
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ trans('main.availability') }}</label>
                                                {{ Form::select('start_month', $months, 1,
                                                    array('class' => 'form-control start-month')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                {{ Form::select('end_month', $months, 12,
                                                    array('class' => 'form-control end-month')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ trans('main.season') }}</label>
                                                {{ Form::select('season_start_month', $months, 1,
                                                    array('class' => 'form-control season-start-month')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                {{ Form::select('season_end_month', $months, 12,
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
                                        <div id="{{ $key }}_div" @if ($key != 'en') {{ 'style=display:none' }} @endif>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>{{ trans('main.short_description').' ('.$language.')' }}</label>
                                                    {{ Form::textarea($key.'_short_description', null,
                                                        array('class' => 'form-control '.$key.'-short-description',
                                                        'rows' => 6)) }}
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>{{ trans('main.description').' ('.$language.')' }}</label>
                                                    {{ Form::textarea($key.'_description', null,
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
                                            @foreach ($attributes as $attribute)
                                                @if ($j % 4 == 1)
                                                    <div class="row">
                                                @endif
                                                <div class="col-sm-3 attribute-checkboxes">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="checkbox m-r-xs">
                                                                {{ Form::checkbox('attribute_'.$attribute->id, 'attribute', false,
                                                                    array('class' => 'attribute-checkbox',
                                                                    'data-attribute-id' => $attribute->id)) }}
                                                                <label>{{ $attribute->hr_name }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            @if ($attribute->is_input == 'T')
                                                                @if ($attribute->featured == 'F')
                                                                    <div class="input-group">
                                                                        {{ Form::text('value_'.$attribute->id, null,
                                                                            array('class' => 'form-control attribute-value')) }}
                                                                        <span class="input-group-btn">
                                                                            <button type="button"
                                                                                class="btn btn-default attribute-translation"
                                                                                data-attribute-id="{{ $attribute->id }}">
                                                                                {{ trans('main.translate') }}
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                @else
                                                                    {{ Form::text('value_'.$attribute->id, null,
                                                                        array('class' => 'form-control attribute-value')) }}
                                                                @endif
                                                            @else
                                                                {{ Form::text('value_'.$attribute->id, null,
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
                                    <div class="price-element">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>{{ trans('main.start_day') }}</label>
                                                    {{ Form::text('start_day', null,
                                                        array('class' => 'form-control start-day prices-datepicker')) }}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>{{ trans('main.end_day') }}</label>
                                                    {{ Form::text('end_day', null,
                                                        array('class' => 'form-control end-day prices-datepicker')) }}
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>{{ trans('main.price') }} (€)</label>
                                                    {{ Form::text('price', null, array('class' => 'form-control price')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                            {{ Form::text('deposit', 0, array('class' => 'form-control deposit')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.cash_payment') }}</label>
                                            {{ Form::select('cash_payment', array('T' => trans('main.yes'), 'F' => trans('main.no')),
                                                'T', array('class' => 'form-control cash-payment')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.pets_price') }}</label>
                                            {{ Form::text('pets_price', 0, array('class' => 'form-control pets-price')) }}
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
                                            {{ Form::select('language', array(0 => '', 1 => trans('main.first_minute'),
                                                2 => trans('main.last_minute')), 0,
                                                array('class' => 'form-control discount-type')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>{{ trans('main.percent') }}</label>
                                            {{ Form::text('discount', null, array('class' => 'form-control discount')) }}
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
                                        <div class="lightBoxGallery featured-images" style="margin-top: -5px"></div>
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
                                        <div class="lightBoxGallery images" style="margin-top: -5px"></div>
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
                {{ Form::hidden('latitude', 0, array('class' => 'latitude')) }}
                {{ Form::hidden('longitude', 0, array('class' => 'longitude')) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @include('modals.cropper')
    @include('modals.attributeTranslation')

    <script>
        var is_search = 'F';
        var is_insert = 'T';
        var villa_id = 0;
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
@endsection