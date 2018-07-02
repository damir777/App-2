@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.edit_category') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    {{ Form::open(array('route' => 'UpdateCategory', 'autocomplete' => 'off')) }}
                    {{ Form::hidden('id', $category->id) }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('en_name')) has-error @endif">
                                            <label>{{ trans('main.name') }} (En)</label>
                                            {{ Form::text('en_name', $category->en_name, array('class' => 'form-control',
                                                'required')) }}
                                            @if ($errors->has('en_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('en_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (De)</label>
                                            {{ Form::text('de_name', $category->de_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (It)</label>
                                            {{ Form::text('it_name', $category->it_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Dk)</label>
                                            {{ Form::text('dk_name', $category->dk_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Sv)</label>
                                            {{ Form::text('sv_name', $category->sv_name, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Hr)</label>
                                            {{ Form::text('hr_name', $category->hr_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Fr)</label>
                                            {{ Form::text('fr_name', $category->fr_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Ru)</label>
                                            {{ Form::text('ru_name', $category->ru_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (No)</label>
                                            {{ Form::text('no_name', $category->no_name, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <a href="{{ route('GetCategories') }}" class="btn btn-warning">
                                    <strong>{{ trans('main.cancel') }}</strong>
                                </a>
                                <button class="btn btn-primary"><strong>{{ trans('main.save') }}</strong></button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection