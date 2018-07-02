@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-xs-8">
            <h2 class="page-title">{{ trans('main.villas') }}</h2>
        </div>
        <div class="col-xs-4">
            <div class="title-action">
                <a href="{{ route('AddVilla') }}" class="btn btn-success">{{ trans('main.add_villa') }}</a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        {{ Form::open(array('route' => 'GetVillas', 'class' => 'form-inline', 'method' => 'get',
                            'autocomplete' => 'off')) }}
                        <div class="form-group">
                            {{ Form::text('search_string', $search_string, array('class' => 'form-control',
                                'placeholder' => trans('main.search_placeholder'))) }}
                        </div>
                        <div class="form-group">
                            {{ Form::select('renter', $renters, $renter, array('class' => 'form-control')) }}
                        </div>
                        <button class="btn btn-white">{{ trans('main.search') }}</button>
                        {{ Form::close() }}
                    </div>
                    @if (!$villas->isEmpty())
                        <div class="ibox-content m-t-md">
                            <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>
                                    <th data-sort-ignore="true">{{ trans('main.name') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.renter') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.address') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.city') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.edit') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.delete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($villas as $villa)
                                    <tr>
                                        <td>{{ $villa->name}}</td>
                                        <td>{{ $villa->renter->full_name }}</td>
                                        <td>{{ $villa->address }}</td>
                                        <td>{{ $villa->city }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('EditVilla', $villa->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="confirm-alert"
                                                data-confirm-link="{{ route('DeleteVilla', $villa->id) }}">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6">
                                        @if ($search_string || $renter)
                                            {{ $villas->appends(['search_string' => $search_string, 'renter' => $renter]) }}
                                        @else
                                            {{ $villas->links() }}
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