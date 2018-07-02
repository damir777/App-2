@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.new_bookings') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        {{ Form::open(array('route' => 'GetRenterNewBookings', 'class' => 'form-inline', 'method' => 'get',
                            'autocomplete' => 'off')) }}
                        <div class="form-group">
                            {{ Form::select('villa', $villas, $villa, array('class' => 'form-control')) }}
                        </div>
                        <div class="form-group">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('start_date', $start_date, array('class' => 'form-control',
                                    'placeholder' => trans('main.from'))) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {{ Form::text('end_date', $end_date, array('class' => 'form-control',
                                    'placeholder' => trans('main.to'))) }}
                            </div>
                        </div>
                        <button class="btn btn-white">{{ trans('main.search') }}</button>
                        {{ Form::close() }}
                    </div>
                    @if (!$bookings->isEmpty())
                        <div class="ibox-content m-t-md">
                            <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>
                                    <th data-sort-ignore="true">{{ trans('main.villa') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.guest') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.check_in') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.check_out') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.payment_type') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.confirm') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.reject') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->villa->name}}</td>
                                        <td>{!! $booking->booking_user !!}</td>
                                        <td>{{ $booking->start_date }}</td>
                                        <td>{{ $booking->end_date }}</td>
                                        <td>{{ trans('main.'.$booking->downpaymentType->code) }}</td>
                                        @if ($current_date <= $booking->sql_start_date && $booking->user_id != 2)
                                            <td class="text-center">
                                                <a href="#" class="confirm-alert"
                                                   data-confirm-link="{{ route('ConfirmBooking', $booking->id) }}">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="reject-booking" data-toggle="modal"
                                                    data-target="#rejectBookingModal" data-booking-id="{{ $booking->id }}">
                                                    <i class="fa fa-ban"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td></td>
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="7">
                                        @if ($villa || $start_date || $end_date)
                                            {{ $bookings->appends(['villa' => $villa, 'start_date' => $start_date,
                                                'end_date' => $end_date]) }}
                                        @else
                                            {{ $bookings->links() }}
                                        @endif
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('modals.rejectBooking')

    {{ HTML::script('js/functions/adminBooking.js?v='.date('YmdHi')) }}
@endsection