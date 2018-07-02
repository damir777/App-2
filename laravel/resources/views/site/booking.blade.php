@extends('layouts.site')

@section('content')
    <div class="booking-header" style="background: url('{{ url('images/'.$villa->id.'/'.$villa->images[0]) }}')">
        <div class="booking-page-villa-info">
            <h3>{{ $villa->name }}</h3>
            <p class="address">{{ $villa->city }}</p>
            <p class="booking-page-villa-attributes">
                @foreach ($villa->featured_attributes as $attribute)
                    <span>{{ HTML::image('icon/'.$attribute->attribute->icon, '') }} {{ $attribute->value }}</span>
                @endforeach
            </p>
        </div>
    </div>
    <div class="booking-process">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="booking-steps-wrapper">
                        <div class="booking-steps-header">
                            <div class="row text-center">
                                <div class="col-sm-3">
                                    <p><b>{{ trans('main.check_in') }}: </b><span id="booking-start-date"></span></p>
                                </div>
                                <div class="col-sm-3">
                                    <p><b>{{ trans('main.check_out') }}: </b><span id="booking-end-date"></span></p>
                                </div>
                                <div class="col-sm-3">
                                    <p><b>{{ trans('main.duration') }}: </b><span id="booking-nights"></span></p>
                                </div>
                                <div class="col-sm-3">
                                    <p id="booking-price"></p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-steps-inner">
                            <div class="booking-steps-content">
                                <div id="booking-calendar">
                                    <div class="row">
                                        <div class="col-sm-10 offset-1">
                                            <input type="text" id="booking-calendar-input">
                                        </div>
                                    </div>
                                    {{ Form::open(array('url' => '#')) }}
                                    <div class="row pt-4">
                                        <div class="col-sm-8 offset-sm-2">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="adults">{{ trans('main.adults') }}:</label>
                                                        {{ Form::select('adults', $villa->booking_select_menu, $adults,
                                                            array('class' => 'form-control', 'id' => 'adults')) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="children">{{ trans('main.children') }}:</label>
                                                        {{ Form::select('children', $villa->booking_select_menu, $children,
                                                            array('class' => 'form-control', 'id' => 'children')) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="infants">{{ trans('main.infants') }}:</label>
                                                        {{ Form::select('infants', $villa->booking_select_menu, $infants,
                                                            array('class' => 'form-control', 'id' => 'infants')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{Form::close()  }}
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <div class="booking-steps-navigation">
                                                <button type="button" class="btn btn-primary move-to-authentication" style="display: none">
                                                    {{ trans('main.next') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-center booking-rates">
                                        <div class="col-md-6 offset-md-3 col-12">
                                            <a class="show-rates" data-toggle="collapse" href="#bookingRates" role="button"
                                                aria-expanded="false" aria-controls="collapseExample">
                                                {{ trans('main.show_all_rates') }}
                                            </a>
                                            <div class="collapse" id="bookingRates">
                                                <div class="card card-body">
                                                    <table class="table table-sm table-striped">
                                                        <tbody>
                                                            @foreach ($villa->prices as $price)
                                                                <tr>
                                                                    <td>
                                                                        {{ $price['start_day'].' - '.$price['end_day'] }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $price['price'] }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="booking-authentication" style="display: none">
                                    {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="booking-full-name">{{ trans('main.full_name') }}:</label>
                                                {{ Form::text('full_name', $full_name, array('class' => 'form-control',
                                                    'id' => 'booking-full-name')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="booking-country">{{ trans('main.country') }}:</label>
                                                {{ Form::select('country', $countries, $country, array('class' => 'form-control',
                                                    'id' => 'booking-country')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="booking-phone">{{ trans('main.phone') }}:</label>
                                                {{ Form::text('phone', $phone, array('class' => 'form-control',
                                                    'id' => 'booking-phone')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="booking-email">{{ trans('main.email') }}:</label>
                                                {{ Form::text('email', $email, array('class' => 'form-control',
                                                    'id' => 'booking-email')) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="booking-confirm-email">{{ trans('main.confirm_email') }}:</label>
                                                {{ Form::text('confirm_email', $email, array('class' => 'form-control',
                                                    'id' => 'booking-confirm-email')) }}
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <div class="booking-steps-navigation">
                                                <button type="button" class="btn btn-primary back-to-calendar">
                                                    {{ trans('main.previous') }}
                                                </button>
                                                <button type="button" class="btn btn-primary move-to-payment">
                                                    {{ trans('main.next') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="booking-payment" style="display: none">
                                    {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <p>{{ trans('main.payment_step_info') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p>
                                                {{ trans('main.downpayment') }} (30%):
                                                <span class="booking-amount" id="downpayment">{{ $formatted_downpayment }}</span>
                                            </p>
                                            <p class="payment-info">
                                                <i>{{ trans('main.pay_now') }}:</i>
                                            </p>
                                            @foreach ($payment_types as $payment_type)
                                                @if ($payment_type->downpayment == 'T')
                                                    <div class="custom-control custom-radio pb-3">
                                                        <input type="radio" id="downpayment{{ $payment_type->id }}" name="downpayment"
                                                            class="custom-control-input downpayment" value="{{ $payment_type->id }}">
                                                        <label class="custom-control-label" for="downpayment{{ $payment_type->id }}">
                                                            {{ trans('main.'.$payment_type->code) }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col-sm-6">
                                            <p>
                                                {{ trans('main.remaining_payment') }} (70%):
                                                <span class="booking-amount" id="remaining-payment">
                                                    {{ $formatted_remaining_payment }}
                                                </span>
                                            </p>
                                            <p class="payment-info">
                                                <i>
                                                    {{ trans('main.due_on') }}
                                                    <span id="due-date">{{ $due_date }}</span>, {{ trans('main.choose_payment_type') }}:
                                                </i>
                                            </p>
                                            @foreach ($payment_types as $payment_type)
                                                @if ($payment_type->remaining_payment == 'T')
                                                    <div class="custom-control custom-radio pb-3">
                                                        <input type="radio" id="remaining-payment{{ $payment_type->id }}"
                                                            name="remaining_payment" class="custom-control-input remaining-payment"
                                                            value="{{ $payment_type->id }}">
                                                        <label class="custom-control-label" for="remaining-payment{{ $payment_type->id }}">
                                                            {{ trans('main.'.$payment_type->code) }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <div class="booking-steps-navigation">
                                                <button type="button" class="btn btn-primary back-to-authentication">
                                                    {{ trans('main.previous') }}
                                                </button>
                                                <button type="button" class="btn btn-primary proceed-payment" disabled="disabled"
                                                    style="display: none">
                                                    {{ trans('main.payment') }}
                                                </button>
                                                <button type="button" class="btn btn-primary confirm-booking" disabled="disabled">
                                                    {{ trans('main.book') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.bookingPersonalData')

    <script>
        var no_check_in_dates = [];
        var no_check_out_dates = [];
        var disabled_months = [];
        var disabled_dates = [];
        var season_months = [];
        var calendar_end_date = '{{ $calendar_end_date }}';
        var selected_start_date = false;
        var selected_end_date = false;

            @foreach ($no_check_in_array as $no_check_in)
                var no_check_in = '{{ $no_check_in }}';
                no_check_in_dates.push(no_check_in);
            @endforeach

            @foreach ($no_check_out_array as $no_check_out)
                var no_check_out = '{{ $no_check_out }}';
                no_check_out_dates.push(no_check_out);
            @endforeach

            @foreach ($disabled_months as $disabled_month)
                var disabled_month = '{{ $disabled_month }}';
                disabled_months.push(parseInt(disabled_month));
            @endforeach

            @foreach ($disabled_dates as $disabled_date)
                var disabled_date = '{{ $disabled_date }}';
                disabled_dates.push(disabled_date);
            @endforeach

            @foreach ($season_months as $season_month)
                var season_month = '{{ $season_month }}';
                season_months.push(parseInt(season_month));
            @endforeach

            @if ($start_date)
                selected_start_date = '{{ $start_date }}';
                selected_end_date = '{{ $end_date }}';
            @endif

        var is_booking_page = 'T';
        var villa_id = '{{ $villa->id }}';
        var booking_start_date = '{{ $start_date }}';
        var booking_end_date = '{{ $end_date }}';
        var booking_time_error = '{{ trans('errors.booking_time_error') }}';
    </script>

    {{ HTML::script('js/functions/booking-calendar.js?v='.date('YmdHi')) }}
    {{ HTML::script('js/functions/booking.js?v='.date('YmdHi')) }}
@endsection