<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\GeneralRepository;
use App\Repositories\VillaRepository;
use App\Repositories\BookingRepository;

class RenterController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new BookingRepository;
    }

    /*
    |--------------------------------------------------------------------------
    | Villas
    |--------------------------------------------------------------------------
    */

    //get villas
    public function getVillas()
    {
        //call getVillas method from VillaRepository to get villas
        $this->repo = new VillaRepository;
        $villas = $this->repo->getVillas('T', 'F');

        //if response status = '0' show error page
        if ($villas['status'] == 0)
        {
            return view('errors.500');
        }

        return view('renter.villas.list', ['villas' => $villas['data']]);
    }

    //preview villa
    public function previewVilla($id)
    {
        //call getVillaDetails method from VillaRepository to get villa details
        $this->repo = new VillaRepository;
        $villa = $this->repo->getVillaDetails($id);

        //get languages form array from GeneralRepository
        $languages_array = GeneralRepository::$lang_form_array;

        //call getMonthsSelect method from GeneralRepository to get months - select
        $months = GeneralRepository::getMonthsSelect();

        //if response status = '0' show error page
        if ($villa['status'] == 0)
        {
            return view('errors.500');
        }

        return view('renter.villas.previewVilla', ['villa' => $villa['data'], 'languages_array' => $languages_array, 'months' => $months]);
    }

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    //get booking calendar
    public function getCalendar()
    {
        //call getVillasSelect method from VillaRepository to get villas - select
        $this->repo = new VillaRepository;
        $villas = $this->repo->getVillasSelect('T');

        //if response status = '0' show error page
        if ($villas['status'] == 0)
        {
            return view('errors.500');
        }

        return view('renter.bookings.calendar', ['villas' => $villas['data']]);
    }

    //get new bookings
    public function getNewBookings(Request $request)
    {
        //get search parameters
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $villa = $request->villa;

        //call getVillasSelect method from VillaRepository to get villas - select
        $this->repo = new VillaRepository;
        $villas = $this->repo->getVillasSelect('T', 1);

        //call getBookings method from BookingRepository to get bookings
        $this->repo = new BookingRepository;
        $bookings = $this->repo->getBookings($start_date, $end_date, $villa, 1);

        //if response status = '0' show error page
        if ($villas['status'] == 0 || $bookings['status'] == 0)
        {
            return view('errors.500');
        }

        return view('renter.bookings.newBookings', ['villas' => $villas['data'], 'villa' => $villa, 'start_date' => $start_date,
            'end_date' => $end_date, 'bookings' => $bookings['data'], 'current_date' => $bookings['current_date']]);
    }

    //get bookings
    public function getBookings(Request $request)
    {
        //get search parameters
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $villa = $request->villa;
        $status = $request->status;

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $this->repo = new GeneralRepository;
        $statuses = $this->repo->getStatusesSelect(1);

        //call getVillasSelect method from VillaRepository to get villas - select
        $this->repo = new VillaRepository;
        $villas = $this->repo->getVillasSelect('T', 1);

        //call getBookings method from BookingRepository to get bookings
        $this->repo = new BookingRepository;
        $bookings = $this->repo->getBookings($start_date, $end_date, $villa, $status);

        //if response status = '0' show error page
        if ($statuses['status'] == 0 || $villas['status'] == 0 || $bookings['status'] == 0)
        {
            return view('errors.500');
        }

        return view('renter.bookings.list', ['statuses' => $statuses['data'], 'villas' => $villas['data'], 'status' => $status,
            'villa' => $villa, 'start_date' => $start_date, 'end_date' => $end_date, 'bookings' => $bookings['data']]);
    }
}
