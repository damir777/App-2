<div class="modal inmodal" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                {{ Form::hidden('is_featured', 'T', array('id' => 'is-featured')) }}
                <div class="row">
                    <div class="col-sm-12">
                        <div id="featured-cropper" style="display:none;">
                            <div id="featured-croppie"></div>
                        </div>
                        <div class="featured-crop-button-div" style="display: none">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-success btn-md pull-right crop"><i class="fa fa-crop"></i>
                                    {{ trans('main.crop') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div id="cropper" style="display:none;">
                            <div id="croppie"></div>
                        </div>
                        <div class="crop-button-div" style="display: none">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-success btn-md pull-right crop"><i class="fa fa-crop"></i>
                                    {{ trans('main.crop') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">{{ trans('main.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

{{ HTML::style('css/croppie.css') }}
{{ HTML::script('js/croppie.min.js') }}
{{ HTML::script('js/functions/images.js?v='.date('YmdHi')) }}