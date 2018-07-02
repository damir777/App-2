function validateForm()
{
    //set inputs array
    var inputs_array = ['full-name', 'phone'];

    var email_test = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    var check_validation = 1;

    //loop through all inputs and make validation
    $.each(inputs_array, function(index, value) {

        var current_input_string = '.' + value;
        var current_input = $(current_input_string);

        current_input.removeAttr('style');

        if (current_input.val().trim() == '')
        {
            $(current_input).css('border', '1px solid #FF0000');

            check_validation = 0;
        }
    });

    var email_input = $('.register-email');
    var password_input = $('.password');
    var password_confirmation_input = $('.password-confirmation');

    email_input.removeAttr('style');
    password_input.removeAttr('style');
    password_confirmation_input.removeAttr('style');

    if (!email_test.test(email_input.val()))
    {
        $(email_input).css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (password_input.val().trim() == '')
    {
        $(password_input).css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (password_confirmation_input.val() != password_input.val())
    {
        $(password_confirmation_input).css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    return check_validation;
}

function registerUser()
{
    var full_name = $('.full-name').val();
    var email = $('.register-email').val();
    var password = $('.password').val();
    var password_confirmation = $('.password-confirmation').val();
    var country = $('.country').val();
    var phone = $('.phone').val();
    var captcha = $('.g-recaptcha-response').val();
    var current_url = window.location.href;

    $.ajax({
        url: ajax_url + 'auth/register/user',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'full_name': full_name, 'email': email, 'password': password, 'password_confirmation': password_confirmation,
            'country': country, 'phone': phone, 'captcha': captcha, 'redirect_url': current_url},
        success: function(data) {

            $('.register').prop('disabled', false);

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    toastr.success(data.success);
                    $('#authenticationModal').modal('hide');
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
            }
        },
        error: function() {
            $('.register').prop('disabled', false);
            toastr.error(error);
        }
    });
}

function loginUser()
{
    var email = $('.login-email').val();
    var password = $('.login-password').val();
    var current_url = window.location.href;

    $.ajax({
        url: ajax_url + 'auth/login/user',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'email': email, 'password': password, 'redirect_url': current_url},
        success: function(data) {

            $('.login').prop('disabled', false);

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    if (data.redirect === 'T')
                    {
                        location.href = data.redirect_url;
                    }
                    else
                    {
                        location.reload();
                    }

                    break;
                case 0:
                    toastr.error(data.error);
                    break;
            }
        },
        error: function() {
            $('.login').prop('disabled', false);
            toastr.error(error);
        }
    });
}

function formatCurrentUrl()
{
    var url = window.location.href;
    var hash_position = url.indexOf('#');

    if (hash_position !== -1)
    {
        url = url.substr(0, hash_position);
    }

    return url;
}

$(document).ready(function() {

    $('.login-link').on('click', function(e) {

        e.preventDefault();

        $('#authenticationModal').modal('show');
    });

    $('.social-login').on('click', function() {

        var current_url = formatCurrentUrl();
        var url_hash = window.location.hash.substring(1);
        var provider = $(this).attr('data-provider');

        location.href = ajax_url + 'auth/social/redirect/' + provider + '?redirect_url=' + current_url + '&hash=' + url_hash;
    });

    $('.register').on('click', function() {

        $('.register').prop('disabled', true);

        var validation = validateForm();

        if (!validation)
        {
            $('.register').prop('disabled', false);
            toastr.error(validation_error);
            return 0;
        }

        registerUser();
    });

    $('.login').on('click', function() {

        $('.login').prop('disabled', true);

        loginUser();
    });
});