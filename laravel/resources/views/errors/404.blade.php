<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>404 | xx</title>
    <link rel="icon" href="{{ URL::to('favicon.png') }}">

    {{ HTML::style('css/site/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/site/font-awesome.min.css') }}

    {{ HTML::style('css/site.css') }}

    {{ HTML::script('js/site/jquery.min.js') }}
    {{ HTML::script('js/site/bootstrap.bundle.min.js') }}
</head>
<body>

<div class="adrimaris-page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 offset-1">
                <div class="adrimaris-page">
                    <div class="adrimaris-logo">
                        {{ HTML::image('img/xx-logo.svg', '') }}
                    </div>
                    <div class="adrimaris-page-text">
                        <h1>404</h1>
                        <h3>{{ trans('errors.not_found_1') }}</h3>
                        <p>{{ trans('errors.not_found_2') }}</p>
                        <a href="{{ route('HomePage') }}" class="btn btn-primary">{{ trans('main.homepage') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>