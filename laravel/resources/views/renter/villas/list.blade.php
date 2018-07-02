@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.villas') }}</h2>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{{ route('AddVilla') }}" class="btn btn-success">{{ trans('main.add_villa') }}</a>
            </div>
        </div>
    </div>
    @if (!$villas->isEmpty())
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>
                                    <th data-sort-ignore="true">{{ trans('main.name') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.address') }}</th>
                                    <th data-sort-ignore="true">{{ trans('main.city') }}</th>
                                    <th class="text-center" data-sort-ignore="true">{{ trans('main.preview') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($villas as $villa)
                                    <tr>
                                        <td>{{ $villa->name}}</td>
                                        <td>{{ $villa->address }}</td>
                                        <td>{{ $villa->city }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('PreviewVilla', $villa->id) }}">
                                                <i class="fa fa-file-text-o"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4">
                                        {{ $villas->links() }}
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