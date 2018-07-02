<div class="modal inmodal" id="attributeTranslationsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceIn">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.attribute_translation') }}</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                {{ Form::hidden('attribute_id', null, array('id' => 'attribute-id')) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>En</label>
                            {{ Form::text('en_translation', null, array('class' => 'form-control en-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Hr</label>
                            {{ Form::text('hr_translation', null, array('class' => 'form-control hr-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>De</label>
                            {{ Form::text('de_translation', null, array('class' => 'form-control de-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Fr</label>
                            {{ Form::text('fr_translation', null, array('class' => 'form-control fr-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>It</label>
                            {{ Form::text('it_translation', null, array('class' => 'form-control it-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Ru</label>
                            {{ Form::text('ru_translation', null, array('class' => 'form-control ru-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Dk</label>
                            {{ Form::text('dk_translation', null, array('class' => 'form-control dk-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>No</label>
                            {{ Form::text('no_translation', null, array('class' => 'form-control no-translation')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Sv</label>
                            {{ Form::text('sv_translation', null, array('class' => 'form-control sv-translation')) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                <button type="button" class="btn btn-danger remove-translation">{{ trans('main.delete') }}</button>
                <button type="button" class="btn btn-primary add-translation">{{ trans('main.save') }}</button>
            </div>
        </div>
    </div>
</div>