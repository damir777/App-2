<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Repositories\GeneralRepository;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //locale redirect
    protected function localeRedirect($code, $route, $parameter = false)
    {
        //set lang array
        $lang_array = ['en', 'de', 'hr'];

        //if lang code is not correct redirect to home page
        if (!in_array($code, $lang_array))
        {
            return redirect()->route('HomePage');
        }

        //set app locale
        App::setLocale($code);

        //set locale session
        Session::put('locale', $code);

        if ($parameter)
        {
            return redirect()->route($route, $parameter);
        }

        return redirect()->route($route);
    }

    //set page title
    protected function setPageTitle($title, $translate = false)
    {
        $page_title = $title;

        if ($translate)
        {
            $page_title = trans('main.'.$title);
        }

        view()->share('page_title', $page_title);
    }

    //set blog links
    protected function setBlogLinks()
    {
        //call setBlogLinks method from GeneralRepository to set blog links
        $links = GeneralRepository::setBlogLinks();

        view()->share('blog_links', $links);
    }

    //translate booking calendar
    protected function translateBookingCalendar()
    {
        //call translateBookingCalendar method from GeneralRepository to translate booking calendar
        $translation = GeneralRepository::translateBookingCalendar();

        view()->share('calendar_translation', $translation);
    }

    //share countries list
    protected function shareCountriesList()
    {
        //call getCountriesSelect method from GeneralRepository to get countries list - select
        $repo = new GeneralRepository;
        $countries = $repo->getCountriesSelect();

        view()->share('countries', $countries);
    }
}
