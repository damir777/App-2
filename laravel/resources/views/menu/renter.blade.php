<li @if (Request::is('renter/villas/*')) {{ "class=active" }} @endif>
    <a href="{{ route('GetRenterVillas') }}">
        <i class="fa fa-building-o"></i> <span class="nav-label">{{ trans('main.my_villas') }}</span>
    </a>
</li>

<li @if (Request::is('renter/bookings/*')) {{ "class=active" }} @endif>
    <a href="#">
        <i class="fa fa-calendar"></i> <span class="nav-label">{{ trans('main.bookings') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li @if (Request::is('renter/bookings/new')) {{ "class=active" }} @endif>
            <a href="{{ route('GetRenterNewBookings') }}">{{ trans('main.new_bookings') }}</a>
        </li>
        <li @if (Request::is('renter/bookings/list')) {{ "class=active" }} @endif>
            <a href="{{ route('GetRenterBookings') }}">{{ trans('main.bookings') }}</a>
        </li>
        <li @if (Request::is('renter/bookings/calendar')) {{ "class=active" }} @endif>
            <a href="{{ route('GetRenterCalendar') }}">{{ trans('main.calendar') }}</a>
        </li>
    </ul>
</li>