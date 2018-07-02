@extends('layouts.main')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2 class="page-title">{{ trans('main.new_renter') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    {{ Form::open(array('route' => 'InsertRenter', 'autocomplete' => 'off')) }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('full_name')) has-error @endif">
                                            <label>{{ trans('main.full_name') }}</label>
                                            {{ Form::text('full_name', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('full_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('full_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('oib')) has-error @endif">
                                            <label>OIB</label>
                                            {{ Form::text('oib', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('oib'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('oib') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                                            <label>{{ trans('main.email') }}</label>
                                            {{ Form::text('email', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('password')) has-error @endif">
                                            <label>{{ trans('main.password') }}</label>
                                            {{ Form::password('password', ['class' => 'form-control', 'required']) }}
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                                            <label>{{ trans('main.confirm_password') }}</label>
                                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'required']) }}
                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content m-t-md">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('phone')) has-error @endif">
                                            <label>{{ trans('main.phone') }}</label>
                                            {{ Form::text('phone', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('address')) has-error @endif">
                                            <label>{{ trans('main.address') }}</label>
                                            {{ Form::text('address', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('address'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('city')) has-error @endif">
                                            <label>{{ trans('main.city') }}</label>
                                            {{ Form::text('city', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('city'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @if ($errors->has('zip_code')) has-error @endif">
                                            <label>{{ trans('main.zip_code') }}</label>
                                            {{ Form::text('zip_code', null, array('class' => 'form-control', 'required')) }}
                                            @if ($errors->has('zip_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('zip_code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox collapsed m-t-md">
                                <div class="ibox-title">
                                    <h5>{{ trans('main.invoice_address') }}</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('invoice_full_name')) has-error @endif">
                                                <label>{{ trans('main.full_name') }}</label>
                                                {{ Form::text('invoice_full_name', null, array('class' => 'form-control',
                                                    'required')) }}
                                                @if ($errors->has('invoice_full_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('invoice_full_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('invoice_address')) has-error @endif">
                                                <label>{{ trans('main.address') }}</label>
                                                {{ Form::text('invoice_address', null, array('class' => 'form-control',
                                                    'required')) }}
                                                @if ($errors->has('invoice_address'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('invoice_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('invoice_city')) has-error @endif">
                                                <label>{{ trans('main.city') }}</label>
                                                {{ Form::text('invoice_city', null, array('class' => 'form-control',
                                                    'required')) }}
                                                @if ($errors->has('invoice_city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('invoice_city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('invoice_zip_code')) has-error @endif">
                                                <label>{{ trans('main.zip_code') }}</label>
                                                {{ Form::text('invoice_zip_code', null, array('class' => 'form-control',
                                                    'required')) }}
                                                @if ($errors->has('invoice_zip_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('invoice_zip_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox collapsed m-t-md">
                                <div class="ibox-title">
                                    <h5>{{ trans('main.bank_account') }}</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            {{ trans('main.domestic_account') }}
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('domestic_owner')) has-error @endif">
                                                                        <label>{{ trans('main.owner') }}</label>
                                                                        {{ Form::text('domestic_owner', null, array('class' => 'form-control',
                                                                            'required')) }}
                                                                        @if ($errors->has('domestic_owner'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('domestic_owner') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('domestic_bank')) has-error @endif">
                                                                        <label>{{ trans('main.bank') }}</label>
                                                                        {{ Form::text('domestic_bank', null, array('class' => 'form-control',
                                                                            'required')) }}
                                                                        @if ($errors->has('domestic_bank'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('domestic_bank') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('account_number')) has-error @endif">
                                                                        <label>{{ trans('main.account_number') }}</label>
                                                                        {{ Form::text('account_number', null, array('class' => 'form-control',
                                                                            'required')) }}
                                                                        @if ($errors->has('account_number'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('account_number') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            {{ trans('main.foreign_account') }}
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('foreign_owner')) has-error @endif">
                                                                        <label>{{ trans('main.owner') }}</label>
                                                                        {{ Form::text('foreign_owner', null, array('class' => 'form-control',
                                                                            'required')) }}
                                                                        @if ($errors->has('foreign_owner'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('foreign_owner') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('foreign_bank')) has-error @endif">
                                                                        <label>{{ trans('main.bank') }}</label>
                                                                        {{ Form::text('foreign_bank', null, array('class' => 'form-control',
                                                                            'required')) }}
                                                                        @if ($errors->has('foreign_bank'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('foreign_bank') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('iban')) has-error @endif">
                                                                        <label>IBAN</label>
                                                                        {{ Form::text('iban', null, array('class' => 'form-control', 'required')) }}
                                                                        @if ($errors->has('iban'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('iban') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group @if ($errors->has('swift')) has-error @endif">
                                                                        <label>SWIFT</label>
                                                                        {{ Form::text('swift', null, array('class' => 'form-control', 'required')) }}
                                                                        @if ($errors->has('swift'))
                                                                            <span class="help-block">
                                                                                <strong>{{ $errors->first('swift') }}</strong>
                                                                            </span>
                                                                        @endif
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
                            <div class="ibox collapsed m-t-md">
                                <div class="ibox-title">
                                    <h5>{{ trans('main.host') }}</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('host_full_name')) has-error @endif">
                                                <label>{{ trans('main.full_name') }}</label>
                                                {{ Form::text('host_full_name', null, array('class' => 'form-control',
                                                    'required')) }}
                                                @if ($errors->has('host_full_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('host_full_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('host_email')) has-error @endif">
                                                <label>{{ trans('main.email') }}</label>
                                                {{ Form::text('host_email', null, array('class' => 'form-control', 'required')) }}
                                                @if ($errors->has('host_email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('host_email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group @if ($errors->has('host_languages')) has-error @endif">
                                                <label>{{ trans('main.languages') }}</label>
                                                {{ Form::text('host_languages', null, array('class' => 'form-control', 'required')) }}
                                                @if ($errors->has('host_languages'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('host_languages') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <a href="{{ route('GetRenters') }}" class="btn btn-warning">
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