<script>
    var name;
    var day_names_short = [];
    var day_names = [];
    var month_names_short = [];
    var month_names = [];

    @foreach ($calendar_translation['day-names-short'] as $name)
        name = '{{ $name }}';
        day_names_short.push(name);
    @endforeach

    @foreach ($calendar_translation['day-names'] as $name)
        name = '{{ $name }}';
        day_names.push(name);
    @endforeach

    @foreach ($calendar_translation['month-names-short'] as $name)
        name = '{{ $name }}';
        month_names_short.push(name);
    @endforeach

    @foreach ($calendar_translation['month-names'] as $name)
        name = '{{ $name }}';
        month_names.push(name);
    @endforeach

    i18n = {
        selected: '{{ $calendar_translation['selected'] }}',
        night: '{{ $calendar_translation['night'] }}',
        nights: '{{ $calendar_translation['nights'] }}',
        button: '{{ $calendar_translation['button'] }}',
        clear: '{{ $calendar_translation['clear'] }}',
        'checkin-disabled': '{{ $calendar_translation['checkin-disabled'] }}',
        'checkout-disabled': '{{ $calendar_translation['checkout-disabled'] }}',
        'day-names-short': day_names_short,
        'day-names': day_names,
        'month-names-short': month_names_short,
        'month-names': month_names,
        'error-more': '{{ $calendar_translation['error-more'] }}',
        'error-more-plural': '{{ $calendar_translation['error-more-plural'] }}',
        'error-less': '{{ $calendar_translation['error-less'] }}',
        'error-less-plural': '{{ $calendar_translation['error-less-plural'] }}',
        'info-more': '{{ $calendar_translation['info-more'] }}',
        'info-more-plural': '{{ $calendar_translation['info-more-plural'] }}',
        'info-range': '{{ $calendar_translation['info-range'] }}',
        'info-default': '{{ $calendar_translation['info-default'] }}'
    };
</script>