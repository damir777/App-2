<li @if (Request::is('categories/*') || Request::is('attributes/*')) {{ "class=active" }} @endif>
    <a href="#">
        <i class="fa fa-list"></i> <span class="nav-label">{{ trans('main.attributes') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li @if (Request::is('categories/*')) {{ "class=active" }} @endif>
            <a href="{{ route('GetCategories') }}">{{ trans('main.categories') }}</a>
        </li>
        <li @if (Request::is('attributes/*')) {{ "class=active" }} @endif>
            <a href="{{ route('GetAttributes') }}">{{ trans('main.attributes') }}</a>
        </li>
    </ul>
</li>

<li @if (Request::is('renters/*') || Request::is('users/*')) {{ "class=active" }} @endif>
    <a href="#">
        <i class="fa fa-user"></i> <span class="nav-label">{{ trans('main.users') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li @if (Request::is('renters/*')) {{ "class=active" }} @endif>
            <a href="{{ route('GetRenters') }}">{{ trans('main.renters') }}</a>
        </li>
        <li @if (Request::is('users/*')) {{ "class=active" }} @endif>
            <a href="{{ route('GetUsers') }}">{{ trans('main.guests') }}</a>
        </li>
    </ul>
</li>

<li @if (Request::is('villas/*')) {{ "class=active" }} @endif>
    <a href="{{ route('GetVillas') }}">
        <i class="fa fa-building-o"></i> <span class="nav-label">{{ trans('main.villas') }}</span>
    </a>
</li>

<li @if (Request::is('bookings/*')) {{ "class=active" }} @endif>
    <a href="#">
        <i class="fa fa-calendar"></i> <span class="nav-label">{{ trans('main.bookings') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li @if (Request::is('bookings/calendar')) {{ "class=active" }} @endif>
            <a href="{{ route('SuperAdminGetCalendar') }}">{{ trans('main.calendar') }}</a>
        </li>
        <li @if (Request::is('bookings/list')) {{ "class=active" }} @endif>
            <a href="{{ route('SuperAdminGetBookings') }}">{{ trans('main.bookings') }}</a>
        </li>
    </ul>
</li>