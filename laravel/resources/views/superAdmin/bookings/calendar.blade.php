@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.calendar') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        {{ Form::open(array('url' => '#', 'class' => 'form-inline', 'autocomplete' => 'off')) }}
                                        {{ Form::hidden('date', date('d.m.Y.'), array('id' => 'date')) }}
                                        {{ Form::hidden('booking_date', null, array('id' => 'booking-date')) }}
                                        <div class="form-group">
                                            {{ Form::select('villa', $villas, null, array('id' => 'villa',
                                                'class' => 'form-control select2-villa')) }}
                                        </div>
                                        <button type="button" class="btn btn-white show-calendar">
                                            {{ trans('main.search') }}
                                        </button>
                                        <div class="sk-spinner sk-spinner-wave calendar-loader">
                                            <div class="sk-rect1"></div>
                                            <div class="sk-rect2"></div>
                                            <div class="sk-rect3"></div>
                                            <div class="sk-rect4"></div>
                                            <div class="sk-rect5"></div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="calendar">
                                            <div id="calendar-arrows">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <div id="previous-date" class="text-center previous-arrow">
                                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="text-center">
                                                            <div id="calendar-date"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div id="next-date" class="text-center next-arrow">
                                                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="calendar-days">
                                                <div class="row">
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.monday') }}</div>
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.tuesday') }}</div>
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.wednesday') }}</div>
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.thursday') }}</div>
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.friday') }}</div>
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.saturday') }}</div>
                                                    <div class="col-sm-2 calendar-days">{{ trans('main.sunday') }}</div>
                                                </div>
                                            </div>
                                            <div id="calendar-content"></div>
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

    @include('modals.adminBooking')

    <script>
        var is_renter = 'F';
    </script>

    {{ HTML::script('js/functions/adminBooking.js?v='.date('YmdHi')) }}
@endsection