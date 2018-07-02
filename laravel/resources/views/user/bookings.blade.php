@extends('layouts.site')

@section('content')
    <div class="user-section">
        <div class="container">
            <div class="row pt-5 pb-4">
                <div class="col-sm-12">
                    <h4>{{ trans('main.bookings') }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="user-bookings-filter">
                        <div class="row">
                            <div class="col-sm-12">
                                {{ Form::open(array('route' => 'GetUserBookings', 'class' => 'form-inline', 'method' => 'get',
                                    'autocomplete' => 'off')) }}
                                <div class="form-group">
                                    <div class="input-group date">
                                        <span class="input-group-addon" style=""><i class="fa fa-calendar"></i></span>
                                        {{ Form::text('start_date', $start_date, array('class' => 'form-control',
                                            'placeholder' => trans('main.from'))) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group date">
                                        <span class="input-group-addon" style=""><i class="fa fa-calendar"></i></span>
                                        {{ Form::text('end_date', $end_date, array('class' => 'form-control',
                                            'placeholder' => trans('main.to'))) }}
                                    </div>
                                </div>
                                <button class="btn btn-search">{{ trans('main.search') }}</button>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$bookings->isEmpty())
            <div class="row pb-5">
                <div class="col-sm-12">
                    <div class="user-bookings-list">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('main.villa') }}</th>
                                <th>{{ trans('main.check_in') }}</th>
                                <th>{{ trans('main.check_out') }}</th>
                                <th>{{ trans('main.status') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->villa->name}}</td>
                                    <td>{{ $booking->start_date }}</td>
                                    <td>{{ $booking->end_date }}</td>
                                    <td>{{ trans_choice('main.'.$booking->status->code, 1) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if ($start_date || $end_date)
                            {{ $bookings->appends(['start_date' => $start_date, 'end_date' => $end_date]) }}
                        @else
                            {{ $bookings->links() }}
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection