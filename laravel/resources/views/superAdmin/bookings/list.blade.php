@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.bookings') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        {{ Form::open(array('route' => 'SuperAdminGetBookings', 'class' => 'form-inline', 'method' => 'get',
                            'autocomplete' => 'off')) }}
                        <div class="form-group">
                            {{ Form::select('status', $statuses, $status, array('class' => 'form-control')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::select('villa', $villas, $villa, array('class' => 'form-control select2-villa')) }}
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
                                    <th data-sort-ignore="true">{{ trans('main.status') }}</th>
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
                                        <td>{{ trans_choice('main.'.$booking->status->code, 1) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6">
                                        @if ($status || $villa || $start_date || $end_date)
                                            {{ $bookings->appends(['status' => $status, 'villa' => $villa,
                                                'start_date' => $start_date, 'end_date' => $end_date]) }}
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
@endsection