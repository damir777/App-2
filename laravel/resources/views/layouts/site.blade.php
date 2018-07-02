<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ $page_title }} | xx</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{ URL::to('favicon.png') }}">

    {{ HTML::style('css/site/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/site/font-awesome.min.css') }}

    @if (Request::is('user/*'))
        {{ HTML::style('css/plugins/datapicker/datepicker3.css') }}
    @else
        {{ HTML::style('css/booking-calendar.min.css') }}
    @endif

    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}
    {{ HTML::style('css/select2.min.css') }}

    {{ HTML::style('css/site.css') }}

    {{ HTML::script('js/site/jquery.min.js') }}
    {{ HTML::script('js/site/bootstrap.bundle.min.js') }}

    {{ HTML::script('js/plugins/datapicker/bootstrap-datepicker.js') }}
    {{ HTML::script('js/plugins/toastr/toastr.min.js') }}
    {{ HTML::script('js/fecha.min.js') }}
    {{ HTML::script('js/booking-calendar.min.js') }}
    {{ HTML::script('js/typeahead.min.js') }}
    {{ HTML::script('js/select2.min.js') }}

    {{ HTML::script('js/siteMain.js') }}

    <script>
        var ajax_url = '<?php echo URL::to('/'); ?>/';
        var logged_in = 'F';

        @if (Auth::check())
            logged_in = 'T';
        @endif
    </script>
</head>
<body>

<nav class="desktop-navbar">
    <div class="container-fluid">
        <div class="row">
            <div class="flex-fixed-width-item-navbar">
                <a class="navbar-brand" href="{{ route('HomePage') }}">
                    {{ HTML::image('img/xx-logo.svg', '') }} Your best small decision
                </a>
            </div>
            <div class="col nav-col">
                <div class="row first-row">
                    <div class="col-sm-12 top-navbar">
                        <div class="top-navbar-left">
                            <p><a href="mailto:info@xx.com">info@xx.com</a></p>
                            <p class="social-icons-mobile">
                                <a href="https://www.facebook.com/xx" target="_blank">
                                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                </a>
                                <a href="https://twitter.com/xx" target="_blank">
                                    <i class="fa fa-twitter-square" aria-hidden="true"></i>
                                </a>
                                <a href="https://www.instagram.com/xx" target="_blank">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                                <a href="https://www.youtube.com/channel/UCEZczgV9F92OJ9vikPH5g5Q" target="_blank">
                                    <i class="fa fa-youtube-square" aria-hidden="true"></i>
                                </a>
                                <a href="https://www.pinterest.com/63ec283e08cf896c6b2e4931de4f6a" target="_blank">
                                    <i class="fa fa-pinterest-square" aria-hidden="true"></i>
                                </a>
                            </p>
                            <div class="vertical-separator"></div>
                        </div>
                        <div class="top-navbar-right">
                            @if (!Auth::check())
                                <a href="#" class="login-link">{{ trans('main.login') }}</a>
                            @else
                                <a class="btn btn-secondary dropdown-toggle logout-link" href="#" role="button" id="user-menu"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $username }}
                                </a>
                                <div class="dropdown-menu user-menu" aria-labelledby="user-menu">
                                    @if (Auth::user()->hasRole('SuperAdmin'))
                                        <a class="dropdown-item" href="{{ route('GetCategories') }}">{{ trans('main.administration') }}</a>
                                    @elseif (Auth::user()->hasRole('Renter'))
                                        <a class="dropdown-item" href="{{ route('GetRenterNewBookings') }}">
                                            {{ trans('main.administration') }}
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('GetUserBookings') }}">{{ trans('main.bookings') }}</a>
                                        <a class="dropdown-item" href="{{ route('GetUserProfile') }}">{{ trans('main.profile') }}</a>
                                    @endif
                                        <div class="dropdown-divider"></div>
                                    <a href="{{ route('LogoutUser') }}" class="dropdown-item">{{ trans('main.logout') }}</a>
                                </div>
                            @endif
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="currency-select">{{ trans('main.currency') }}:</label>
                                    {{ Form::select('language', array('EUR' => 'EUR', 'USD' => 'USD'), session('currency'),
                                        array('id' => 'currency-select', 'class' => 'form-control')) }}
                                </div>
                            </form>
                            <div class="lang-search-mobile">
                                <form class="form-inline">
                                    <div class="form-group">
                                        {{ Form::select('language', array('en' => 'EN', 'de' => 'DE', 'hr' => 'HR'),
                                            session('locale'), array('class' => 'form-control lang-select-mobile')) }}
                                    </div>
                                </form>
                            </div>
                            <div class="vertical-separator"></div>
                            <p class="social-icons">
                                <a href="https://www.facebook.com/xx" target="_blank">
                                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                </a>
                                <a href="https://twitter.com/xx" target="_blank">
                                    <i class="fa fa-twitter-square" aria-hidden="true"></i>
                                </a>
                                <a href="https://www.instagram.com/xx" target="_blank">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                                <a href="https://www.youtube.com/channel/UCEZczgV9F92OJ9vikPH5g5Q" target="_blank">
                                    <i class="fa fa-youtube-square" aria-hidden="true"></i>
                                </a>
                                <a href="https://www.pinterest.com/63ec283e08cf896c6b2e4931de4f6a" target="_blank">
                                    <i class="fa fa-pinterest-square" aria-hidden="true"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row second-row">
                    <div class="col-sm-12 main-navbar navbar navbar-expand-lg navbar-light" style="padding: 0;">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar"
                            aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="main-navbar">
                            <ul class="navbar-nav" style="width: 100%;">
                                <li>
                                    <a href="{{ $blog_links['guide'] }}" target="_blank">
                                        {{ trans('main.guide') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $blog_links['destinations'] }}" target="_blank">
                                        {{ trans('main.destinations') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $blog_links['adventures'] }}" target="_blank">
                                        {{ trans('main.adventures') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('AboutUs') }}">{{ trans('main.about') }}</a>
                                </li>
                                <li class="lang-search">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            {{ Form::select('language', array('en' => 'EN', 'de' => 'DE', 'hr' => 'HR'),
                                                session('locale'), array('class' => 'form-control lang-select')) }}
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@include('vendor.calendarTranslation')

@yield('content')

<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                {{ HTML::image('img/xx-logo.svg', '') }}
            </div>
            <div class="col-sm-4 payment-cards">
                <a href="https://usa.visa.com/" target="_blank">
                    {{ HTML::image('img/Visa.gif', '', array('class' => 'img-fluid')) }}
                </a>
                <a href="http://www.maestrocard.com/gateway/index.html" target="_blank">
                    {{ HTML::image('img/Maestro.gif', '', array('class' => 'img-fluid')) }}
                </a>
                <a href="https://www.mastercard.us/en-us.html" target="_blank">
                    {{ HTML::image('img/MasterCard.gif', '', array('class' => 'img-fluid')) }}
                </a>
                <a href="https://www.americanexpress.com/" target="_blank">
                    {{ HTML::image('img/AmericanExpress.jpg', '', array('class' => 'img-fluid')) }}
                </a>
                <a href="https://www.wspay.info/" target="_blank">
                    {{ HTML::image('img/wsPayLogo.png', '', array('class' => 'img-fluid')) }}
                </a>
                <ul>
                    <li>
                        <a href="{{ route('TermsAndConditions') }}">{{ trans('main.terms_and_conditions') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-4"><!--
                <p>At vero eos et accusamus et iusto odio simos
                    ducimus qui blanditiis praesentium voluptatum
                    deleniti atque corrupti quos dolores.
                    Et quas molestias excepturipraesentium
                    voluptatum deleniti atque corrupti quos dolores
                    et quas.
                </p>-->
                <p class="social">
                    <a href="https://www.facebook.com/xx" target="_blank">
                        <i class="fa fa-facebook-square" aria-hidden="true"></i>
                    </a>
                    <a href="https://twitter.com/xx" target="_blank">
                        <i class="fa fa-twitter-square" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.instagram.com/xx" target="_blank">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.youtube.com/channel/UCEZczgV9F92OJ9vikPH5g5Q" target="_blank">
                        <i class="fa fa-youtube-square" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.pinterest.com/63ec283e08cf896c6b2e4931de4f6a" target="_blank">
                        <i class="fa fa-pinterest-square" aria-hidden="true"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

@include('modals.authentication')

@if (session('success_message'))
    <script>
        $(document).ready(function() {
            toastr.success("{{ session('success_message') }}");
        });
    </script>
@endif

@if (session('info_message'))
    <script>
        $(document).ready(function() {
            toastr.info("{{ session('info_message') }}");
        });
    </script>
@endif

@if (session('warning_message'))
    <script>
        $(document).ready(function() {
            toastr.warning("{{ session('warning_message') }}");
        });
    </script>
@endif

@if (session('error_message'))
    <script>
        $(document).ready(function() {
            toastr.error("{{ session('error_message') }}");
        });
    </script>
@endif

<script>

    @if (session('login'))
        $('#authenticationModal').modal('show');
    @endif

    var validation_error = '{{ trans('errors.validation_error') }}';
    var error = '{{ trans('errors.error') }}';
</script>

</body>
</html>