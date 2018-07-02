<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ trans('main.password_reset') }} | xx</title>
    <link rel="icon" href="{{ URL::to('favicon.png') }}">

    {{ HTML::style('css/site/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/site/font-awesome.min.css') }}

    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}
    {{ HTML::style('css/site.css') }}

    {{ HTML::script('js/site/jquery.min.js') }}
    {{ HTML::script('js/site/bootstrap.bundle.min.js') }}
    {{ HTML::script('js/plugins/toastr/toastr.min.js') }}

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "preventDuplicates": true,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "8000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
</head>
<body>

<div class="adrimaris-page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-3">
                <div class="adrimaris-page">
                    <div class="adrimaris-logo">
                        {{ HTML::image('img/xx-logo.svg', '') }}
                    </div>
                    <div class="reset-password-form">
                        {{ Form::open(['url' => '/password/reset', 'role' => 'form']) }}
                        {{ Form::hidden('token', $token) }}
                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                            {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('main.email'),
                                'required']) }}
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('password')) has-error @endif">
                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('main.password'),
                                'required']) }}
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                            {{ Form::password('password_confirmation', ['class' => 'form-control',
                                'placeholder' => trans('main.confirm_password'), 'required']) }}
                            @if ($errors->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password_confirmation') }}
                                </div>
                            @endif
                        </div>
                        <div class="row text-center">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">{{ trans('main.reset_password') }}</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('success_message'))
    <script>
        $(document).ready(function() {
            toastr.success("{{ session('success_message') }}");
        });
    </script>
@endif

</body>
</html>