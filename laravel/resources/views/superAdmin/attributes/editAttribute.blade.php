@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.edit_attribute') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    {{ Form::open(array('route' => 'UpdateAttribute', 'autocomplete' => 'off', 'files' => true)) }}
                    {{ Form::hidden('id', $attribute->id) }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('featured')) has-error @endif">
                                            <label>{{ trans('main.featured') }}</label>
                                            {{ Form::select('featured', array('F' => trans('main.no'), 'T' => trans('main.yes')),
                                                $attribute->featured, array('class' => 'form-control')) }}
                                            @if ($errors->has('featured'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('featured') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('searchable')) has-error @endif">
                                            <label>{{ trans('main.searchable') }}</label>
                                            {{ Form::select('searchable', array('F' => trans('main.no'), 'T' => trans('main.yes')),
                                                $attribute->searchable, array('class' => 'form-control')) }}
                                            @if ($errors->has('searchable'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('searchable') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('category')) has-error @endif">
                                            <label>{{ trans('main.category') }}</label>
                                            {{ Form::select('category', $categories, $attribute->category_id,
                                                array('class' => 'form-control')) }}
                                            @if ($errors->has('category'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('category') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('is_input')) has-error @endif">
                                            <label>{{ trans('main.is_input') }}</label>
                                            {{ Form::select('is_input', array('F' => trans('main.no'), 'T' => trans('main.yes')),
                                                $attribute->is_input, array('class' => 'form-control')) }}
                                            @if ($errors->has('is_input'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('is_input') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="form-group @if ($errors->has('icon')) has-error @endif">
                                                    <label>{{ trans('main.icon') }}</label>
                                                    {{ Form::file('icon', array('class' => 'form-control')) }}
                                                    @if ($errors->has('icon'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('icon') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-2 icon-preview">
                                                {{ HTML::image('icon/'.$attribute->icon, '', array('class' => 'img')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content m-t-md">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('en_name')) has-error @endif">
                                            <label>{{ trans('main.name') }} (En)</label>
                                            {{ Form::text('en_name', $attribute->en_name, array('class' => 'form-control',
                                                'required')) }}
                                            @if ($errors->has('en_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('en_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (De)</label>
                                            {{ Form::text('de_name', $attribute->de_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (It)</label>
                                            {{ Form::text('it_name', $attribute->it_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Dk)</label>
                                            {{ Form::text('dk_name', $attribute->dk_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Sv)</label>
                                            {{ Form::text('sv_name', $attribute->sv_name, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Hr)</label>
                                            {{ Form::text('hr_name', $attribute->hr_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Fr)</label>
                                            {{ Form::text('fr_name', $attribute->fr_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (Ru)</label>
                                            {{ Form::text('ru_name', $attribute->ru_name, array('class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label>{{ trans('main.name') }} (No)</label>
                                            {{ Form::text('no_name', $attribute->no_name, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <a href="{{ route('GetAttributes') }}" class="btn btn-warning">
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