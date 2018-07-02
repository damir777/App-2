<div class="modal fade" id="authenticationModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.login') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-pills nav-justified" role="tablist" id="login-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab"
                                   aria-controls="login" aria-selected="true">{{ trans('main.login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab"
                                   aria-controls="register" aria-selected="false">{{ trans('main.register') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.email') }}</label>
                                            <input type="text" name="login_email" class="form-control login-email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.password') }}</label>
                                            <input type="password" name="login_password" class="form-control login-password">
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary login">{{ trans('main.login') }}</button>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-sm-12">
                                        <a href="{{ url('password/reset') }}" class="reset-password-link">{{ trans('main.forgotten_password') }}</a>
                                    </div>
                                </div>
                                <div class="row text-center" style="margin-top: 15px">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-info social-login" id="facebook"
                                            data-provider="facebook">Facebook {{ lcfirst(trans('main.login')) }}</button>
                                        <button type="button" class="btn btn-info social-login" id="google"
                                            data-provider="google">Google {{ lcfirst(trans('main.login')) }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.full_name') }}</label>
                                            <input type="text" name="full_name" class="form-control full-name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.country') }}</label>
                                            {{ Form::select('country', $countries, null, array('class' => 'form-control country')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.phone') }}</label>
                                            <input type="text" name="phone" class="form-control phone">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.email') }}</label>
                                            <input type="text" name="register_email" class="form-control register-email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.password') }}</label>
                                            <input type="password" name="password" class="form-control password">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ trans('main.confirm_password') }}</label>
                                            <input type="password" name="password_confirmation" class="form-control password-confirmation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! NoCaptcha::display() !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <button type="button" class="btn btn-primary register">
                                            {{ trans('main.register') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

{{ HTML::script('js/functions/authentication.js?v='.date('YmdHi')) }}