<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Repositories\VillaRepository;
use App\Repositories\GeneralRepository;
use App\Repositories\BookingRepository;
use App\Repositories\BlogRepository;

class SiteController extends Controller
{
    //set repo variable
    private $repo;

    //set username variable
    private $username;

    public function __construct(Request $request)
    {
        //set repo
        $this->repo = new VillaRepository;

        if (!$request->ajax())
        {
            $this->middleware(function ($request, $next) {

                //call shareCountriesList method to share countries list
                $this->shareCountriesList();

                //call translateBookingCalendar method to translate booking calendar
                $this->translateBookingCalendar();

                //call setBlogLinks method to set blog links
                $this->setBlogLinks();

                if ($request->user())
                {
                    $user = Auth::user();

                    //set username
                    $this->username = $user->full_name;

                    //share username with all views
                    view()->share('username', $this->username);
                }

                return $next($request);
            });
        }
    }

    //change locale
    public function changeLocale($code)
    {
        //set app locale
        App::setLocale($code);

        //set locale session
        Session::put('locale', $code);

        return redirect()->back();
    }

    //change currency
    public function changeCurrency($code)
    {
        //set currency session
        Session::put('currency', $code);

        return redirect()->back();
    }

    //get home page
    public function getHomePage(Request $request)
    {
        if ($request->lang)
        {
            //if login parameter exists set login flash
            if ($request->login)
            {
                //set login flash
                Session::flash('login', 1);
            }

            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'HomePage');
        }

        //set default page title, view, destination posts, slider, initial slide, mail chimp and villas collection
        $page_title = 'homepage';
        $view = 'site.homepage';
        $posts = null;
        $slider = null;
        $initial_slide = 0;
        $mail_chimp = null;
        $villas = null;

        //call getCities method from GeneralRepository to get cities
        $this->repo = new GeneralRepository;
        $cities = $this->repo->getCities();

        //if response status = '0' show error page
        if ($cities['status'] == 0)
        {
            return view('errors.site500');
        }

        //call formatColumnName method from GeneralRepository to format attribute and description column name
        $attribute_column_name = GeneralRepository::formatColumnName('name');
        $desc_column_name = GeneralRepository::formatColumnName('short_description');

        //get search parameters
        $site_search = $request->site_search;
        $city = $request->city;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $picker_date = null;
        $guests = $request->guests;
        $search_attributes = $request->attribute;

        //if date is set format picker date
        if ($start_date)
        {
            $picker_date = $start_date.' - '.$end_date;
        }

        if ($site_search)
        {
            //call getFilteredAttributes method from GeneralRepository to get filtered attributes
            $this->repo = new GeneralRepository;
            $attributes = $this->repo->getFilteredAttributes($search_attributes);

            //if response status = '0' show error page
            if ($attributes['status'] == 0)
            {
                return view('errors.site500');
            }

            //set attributes array
            $attributes = $attributes['data'];

            //set page title and search view
            $page_title = 'search_page';
            $view = 'site.search';
        }
        else
        {
            //call getMailChimpData method from GeneralRepository to get mail chimp data
            $mail_chimp = GeneralRepository::getMailChimpData();

            //call getBlogPosts method from GeneralRepository to get blog posts
            $this->repo = new BlogRepository;
            $posts = $this->repo->getBlogPosts();

            //call getAttributes method from GeneralRepository to get attributes
            $this->repo = new GeneralRepository;
            $attributes = $this->repo->getAttributes(null, 1);

            //call getHomepageSlider method from GeneralRepository to get homepage slider
            $slider = $this->repo->getHomePageSlider();

            //call getVillas method from VillaRepository to get villas
            $this->repo = new VillaRepository;
            $villas = $this->repo->getVillas('F', 'T');

            //if response status = '0' show error page
            if ($posts['status'] == 0 || $attributes['status'] == 0 || $slider['status'] == 0 || $villas['status'] == 0)
            {
                return view('errors.site500');
            }

            //set initial slide
            $initial_slide = count($slider['data']);

            //set attributes collection
            $attributes = $attributes['data'];

            //set villas collection
            $villas = $villas['data'];
        }

        //call setPageTitle method to set page title
        $this->setPageTitle($page_title, 1);

        return view($view, ['attributes' => $attributes, 'city' => $city, 'start_date' => $start_date,
            'end_date' => $end_date, 'picker_date' => $picker_date, 'guests' => $guests, 'search_attributes' => $search_attributes,
            'attribute_column_name' => $attribute_column_name, 'desc_column_name' => $desc_column_name, 'villas' => $villas,
            'destination_posts' => $posts['destination_posts'], 'home_posts' => $posts['home_posts'], 'slider' => $slider['data'],
            'initial_slide' => $initial_slide, 'mail_chimp' => $mail_chimp, 'cities' => $cities['data']]);
    }

    //search villas
    public function searchVillas(Request $request)
    {
        $page_number = $request->page_number;
        $city = $request->city;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $guests = (int)$request->guests;
        $attributes = $request->search_attributes;

        //call getVillas method from VillaRepository to get villas
        $villas = $this->repo->getVillas('F', 'F', null, null, 1, $page_number, $city, $start_date, $end_date, $guests,
            $attributes);

        return response()->json($villas);
    }

    //get villa details
    public function getVillaDetails(Request $request, $url_name)
    {
        if ($request->lang)
        {
            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'GetVillaDetails', $url_name);
        }

        //call getSiteVillaDetails method from VillaRepository to get villa details
        $villa = $this->repo->getSiteVillaDetails($url_name);

        //if response status = '0' show error page
        if ($villa['status'] == 0)
        {
            return view('errors.site500');
        }

        //call setPageTitle method to set page title
        $this->setPageTitle($villa['data']->name);

        return view('site.villaDetails', ['villa' => $villa['data']]);
    }

    //get booking page
    public function getBookingPage($url_name)
    {
        //call getSiteVillaDetails method from VillaRepository to get villa details
        $villa = $this->repo->getSiteVillaDetails($url_name);

        //call setBookingCalendar method from BookingRepository to set booking calendar
        $this->repo = new BookingRepository;
        $calendar = $this->repo->setBookingCalendar($url_name);

        //call getPaymentTypes method from GeneralRepository to get payment types
        $this->repo = new GeneralRepository;
        $payment_types = $this->repo->getPaymentTypes();

        //call getTempBookingDetails method from BookingRepository to get temp booking details
        $this->repo = new BookingRepository;
        $booking = $this->repo->getTempBookingDetails();

        //if response status = '0' show error page
        if ($villa['status'] == 0 || $calendar['status'] == 0 || $payment_types['status'] == 0 || $booking['status'] == 0)
        {
            return view('errors.site500');
        }

        //call setPageTitle method to set page title
        $this->setPageTitle('booking', true);

        return view('site.booking', ['villa' => $villa['data'], 'no_check_in_array' => $calendar['no_check_in_dates'],
            'no_check_out_array' => $calendar['no_check_out_dates'], 'disabled_months' => $calendar['disabled_months'],
            'disabled_dates' => $calendar['disabled_dates'], 'season_months' => $calendar['season_months'],
            'calendar_end_date' => $calendar['calendar_end_date'], 'start_date' => $booking['start_date'],
            'end_date' => $booking['end_date'], 'adults' => $booking['adults'], 'children' => $booking['children'],
            'infants' => $booking['infants'], 'full_name' => $booking['full_name'], 'country' => $booking['country'],
            'phone' => $booking['phone'], 'email' => $booking['email'], 'formatted_downpayment' => $booking['formatted_downpayment'],
            'formatted_remaining_payment' => $booking['formatted_remaining_payment'], 'payment_types' => $payment_types['data'],
            'due_date' => $booking['due_date']]);
    }

    //about us page
    public function aboutUs(Request $request)
    {
        if ($request->lang)
        {
            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'AboutUs');
        }

        //call setPageTitle method to set page title
        $this->setPageTitle('about', 1);

        //get app locale
        $locale = App::getLocale();

        return view('site.pages.about_'.$locale);
    }

    //terms and conditions page
    public function termsAndConditions(Request $request)
    {
        if ($request->lang)
        {
            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'TermsAndConditions');
        }

        //call setPageTitle method to set page title
        $this->setPageTitle('terms_and_conditions', 1);

        //get app locale
        $locale = App::getLocale();

        return view('site.pages.terms_and_conditions_'.$locale);
    }

    //newsletter page
    public function newsletter()
    {
        //call setPageTitle method to set page title
        $this->setPageTitle('newsletter', 1);

        //get app locale
        $locale = App::getLocale();

        return view('site.pages.newsletter_'.$locale);
    }

    //get blog villas
    public function getBlogVillas(Request $request)
    {
        $has_location = $request->has_location;
        $location = $request->location;
        $radius = $request->radius;

        //call getBlogVillas method from VillaRepository to get blog villas
        $villas = $this->repo->getBlogVillas($has_location, $location, $radius);

        return response()->json($villas);
    }

    //show not found page
    public function showNotFoundPage()
    {
        return view('errors.404');
    }
}
