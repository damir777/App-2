@extends('layouts.site')

@section('content')
    <div class="user-section">
        <div class="container">
            <div class="row pt-5 pb-4">
                <div class="col-sm-12">
                    <h4>{{ trans('main.profile') }}</h4>
                </div>
            </div>
            <div class="row pb-5">
                <div class="col-sm-12">
                    <div class="user-profile">
                        {{ Form::open(array('route' => 'UpdateUserProfile', 'autocomplete' => 'off')) }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @if ($errors->has('full_name')) has-error @endif">
                                    <label>{{ trans('main.full_name') }}</label>
                                    {{ Form::text('full_name', $user->full_name, array('class' => 'form-control', 'required')) }}
                                    @if ($errors->has('full_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('full_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                    <label>{{ trans('main.email') }}</label>
                                    {{ Form::text('email', $user->email, array('class' => 'form-control', 'required')) }}
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                    <label>{{ trans('main.password') }}</label>
                                    {{ Form::password('password', ['class' => 'form-control']) }}
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                                    <label>{{ trans('main.confirm_password') }}</label>
                                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @if ($errors->has('country')) has-error @endif">
                                    <label>{{ trans('main.country') }}</label>
                                    {{ Form::select('country', $countries, $user->country, array('class' => 'form-control country',
                                        'required')) }}
                                    @if ($errors->has('country'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @if ($errors->has('phone')) has-error @endif">
                                    <label>{{ trans('main.phone') }}</label>
                                    {{ Form::text('phone', $user->phone, array('class' => 'form-control', 'required')) }}
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row m-t">
                            <div class="col-sm-12">
                                <div class="text-center">
                                    <button class="btn btn-primary">{{ trans('main.save') }}</button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection