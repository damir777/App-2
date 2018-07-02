function cropImage(is_featured, image)
{
    $.ajax({
        url: ajax_url + 'villas/uploadImage',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));},
        data: JSON.stringify({'is_featured': is_featured, 'villa_id': villa_id, 'is_insert': is_insert, 'image': image}),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('#cropperModal').modal('hide');

                    var image_path = ajax_url + 'images/' + data.image_name;

                    if (data.villa_id != 0)
                    {
                        image_path = ajax_url + 'images/' +data.villa_id + '/' + data.image_name;
                    }

                    var image_element = '<div class="image-wrapper"><div class="delete-image" data-id="' + data.image_id + '"' +
                        ' data-is-featured="' + is_featured + '" title="' + delete_trans + '"><i class="fa fa-close"></i></div>' +
                        '<a href="' + image_path + '" data-gallery=""><img src="' + image_path + '" style="height: 120px"></a>' +
                        '</div>';

                    if (is_featured === 'T')
                    {
                        $('.featured-images').append(image_element);
                    }
                    else
                    {
                        $('.images').append(image_element);
                    }

                    break;
                case 2:
                    toastr.error(validation_error);
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function deleteImage(is_featured, image_id, div)
{
    $.ajax({
        url: ajax_url + 'villas/deleteImage',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));},
        data: {'is_featured': is_featured, 'is_insert': is_insert, 'image_id': image_id, 'villa_id': villa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    div.parents('.image-wrapper').remove();
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function clearCropperModal()
{
    $('#featured-cropper').hide();
    $('.featured-crop-button-div').hide();
    $('.featured-image-input').val('');

    $('#cropper').hide();
    $('.crop-button-div').hide();
    $('.image-input').val('');
}

var this_image;
var featured_options;
var options;

$(document).ready(function() {

    featured_options = $('#featured-croppie').croppie({
        viewport: {
            width: 1920,
            height: 1280
        },
        boundary: {
            width: 2000,
            height: 1300
        }
    });

    options = $('#croppie').croppie({
        viewport: {
            width: 1920,
            height: 1280
        },
        boundary: {
            width: 2000,
            height: 1300
        }
    });

    function applyFeaturedImage(input)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function(e) {
                featured_options.croppie('bind', {
                    url: e.target.result
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
        else
        {
            alert("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    function applyImage(input)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function(e) {
                options.croppie('bind', {
                    url: e.target.result
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
        else
        {
            alert("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $('.featured-image-input').change(function() {

        this_image = this;
        $('#is-featured').val('T');

        $('#cropperModal').modal('show');
    });

    $('.image-input').change(function() {

        this_image = this;
        $('#is-featured').val('F');

        $('#cropperModal').modal('show');
    });

    $('.crop').on('click', function() {

        var is_featured = $('#is-featured').val();

        if (is_featured === 'T')
        {
            featured_options.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(resp) {

                var split_src = resp.split(',');

                cropImage('T', split_src[1]);
            })
        }
        else
        {
            options.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(resp) {

                var split_src = resp.split(',');

                cropImage('F', split_src[1]);
            })
        }
    });

    $('.ibox-content').on('click', '.delete-image', function() {

        var this_button = $(this);
        var image_id = this_button.attr('data-id');
        var is_featured = this_button.attr('data-is-featured');

        deleteImage(is_featured, image_id, this_button);
    });

    $('#cropperModal').on('shown.bs.modal', function() {

        var is_featured = $('#is-featured').val();

        if (is_featured === 'T')
        {
            applyFeaturedImage(this_image);

            $('#featured-cropper').fadeIn();
            $('.featured-crop-button-div').show();
        }
        else
        {
            applyImage(this_image);

            $('#cropper').fadeIn();
            $('.crop-button-div').show();
        }
    });

    $('#cropperModal').on('hidden.bs.modal', function () {

       clearCropperModal();
    });
});