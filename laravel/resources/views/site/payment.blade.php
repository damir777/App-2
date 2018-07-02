<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ trans('main.payment') }} | xx</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{ URL::to('favicon.png') }}">

    {{ HTML::style('css/site/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/site/font-awesome.min.css') }}

    {{ HTML::style('css/site.css') }}

    {{ HTML::script('js/site/jquery.min.js') }}
    {{ HTML::script('js/site/bootstrap.bundle.min.js') }}
</head>
<body>

<div class="payment-page">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="payment-message">
                    <div class="adrimaris-logo">
                        {{ HTML::image('img/xx-logo.svg', '') }}
                    </div>
                    <div class="wspay-logo">
                        {{ HTML::image('img/wsPayLogo.png', '') }}
                    </div>
                    <div class="payment-page-text">
                        <h5>{{ trans('main.redirect_info') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="<?php echo $url; ?>" method="post" name="pay" id="betaware-wspay-redirect-form">
        <?php foreach ($data as $key => $value): ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
        <?php endforeach; ?>
    </form>

    <script type="text/javascript">
        document.getElementById("betaware-wspay-redirect-form").submit();
    </script>
</div>

</body>
</html>