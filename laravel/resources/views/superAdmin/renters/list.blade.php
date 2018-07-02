@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-xs-8">
            <h2 class="page-title">{{ trans('main.renters') }}</h2>
        </div>
        <div class="col-xs-4">
            <div class="title-action">
                <a href="{{ route('AddRenter') }}" class="btn btn-success">{{ trans('main.add_renter') }}</a>
            </div>
        </div>
    </div>
    @if (!$renters->isEmpty())
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>
                                    <th data-sort-ignore="true">{{ trans('main.full_name') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.email') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.phone') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.edit') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.delete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($renters as $renter)
                                    <tr>
                                        <td>{{ $renter->full_name }}</td>
                                        <td>{{ $renter->email }}</td>
                                        <td>{{ $renter->phone }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('EditRenter', $renter->id) }}"><i class="fa fa-edit"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="confirm-alert"
                                                data-confirm-link="{{ route('DeleteRenter', $renter->id) }}">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        {{ $renters->links() }}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection