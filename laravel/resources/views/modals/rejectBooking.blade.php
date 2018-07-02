<div class="modal inmodal" id="rejectBookingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceIn">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.rejecting_booking') }}</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            {{ Form::textarea('message', null, array('class' => 'form-control reject-message',
                                'placeholder' => trans('main.message'))) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                <button type="button" class="btn btn-primary reject">{{ trans('main.reject') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    var message_error = '{{ trans('errors.reject_booking_message') }}';
</script>