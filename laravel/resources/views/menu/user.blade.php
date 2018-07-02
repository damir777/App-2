<li @if (Request::is('user/bookings/list')) {{ "class=active" }} @endif>
    <a href="{{ route('GetUserBookings') }}">
        <i class="fa fa-calendar"></i> <span class="nav-label">{{ trans('main.bookings') }}</span>
    </a>
</li>