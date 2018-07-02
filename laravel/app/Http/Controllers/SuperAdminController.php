<?php

namespace App\Http\Controllers;

use App\AttributeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Attribute;
use App\User;
use App\Villa;
use App\Repositories\GeneralRepository;
use App\Repositories\UserRepository;
use App\Repositories\VillaRepository;
use App\Repositories\ImageRepository;
use App\Repositories\BookingRepository;

class SuperAdminController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new GeneralRepository;
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute categories
    |--------------------------------------------------------------------------
    */

    //get categories
    public function getCategories()
    {
        //call getCategories method from GeneralRepository to get categories
        $categories = $this->repo->getCategories();

        //if response status = '0' show error page
        if ($categories['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.categories.list', ['categories' => $categories['data']]);
    }

    //add category
    public function addCategory()
    {
        return view('superAdmin.categories.addCategory');
    }

    //insert category
    public function insertCategory(Request $request)
    {
        $en_name = $request->en_name;
        $hr_name = $request->hr_name;
        $de_name = $request->de_name;
        $it_name = $request->it_name;
        $fr_name = $request->fr_name;
        $ru_name = $request->ru_name;
        $dk_name = $request->dk_name;
        $no_name = $request->no_name;
        $sv_name = $request->sv_name;

        //validate form inputs
        $validator = Validator::make($request->all(), AttributeCategory::validateCategoryForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('AddCategory')->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call insertCategory method from GeneralRepository to insert category
        $response = $this->repo->insertCategory($en_name, $hr_name, $de_name, $it_name, $fr_name, $ru_name, $dk_name, $no_name,
            $sv_name);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('AddCategory')->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetCategories')->with('success_message', trans('main.category_insert'));
    }

    //edit category
    public function editCategory($id)
    {
        //call getCategoryDetails method from GeneralRepository to get category details
        $category = $this->repo->getCategoryDetails($id);

        //if response status = '0' show error page
        if ($category['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.categories.editCategory', ['category' => $category['data']]);
    }

    //update category
    public function updateCategory(Request $request)
    {
        $id = $request->id;
        $en_name = $request->en_name;
        $hr_name = $request->hr_name;
        $de_name = $request->de_name;
        $it_name = $request->it_name;
        $fr_name = $request->fr_name;
        $ru_name = $request->ru_name;
        $dk_name = $request->dk_name;
        $no_name = $request->no_name;
        $sv_name = $request->sv_name;

        //validate form inputs
        $validator = Validator::make($request->all(), AttributeCategory::validateCategoryForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('EditCategory', $id)->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call updateCategory method from GeneralRepository to update category
        $response = $this->repo->updateCategory($id, $en_name, $hr_name, $de_name, $it_name, $fr_name, $ru_name, $dk_name,
            $no_name, $sv_name);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('EditCategory', $id)->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetCategories')->with('success_message', trans('main.category_update'));
    }

    //delete category
    public function deleteCategory($id)
    {
        //call deleteCategory method from GeneralRepository to delete category
        $response = $this->repo->deleteCategory($id);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('GetCategories')->with('error_message', trans('errors.error'));
        }

        return redirect()->route('GetCategories')->with('success_message', trans('main.category_delete'));
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    //get attributes
    public function getAttributes()
    {
        //call getAttributes method from GeneralRepository to get attributes
        $attributes = $this->repo->getAttributes();

        //if response status = '0' show error page
        if ($attributes['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.attributes.list', ['attributes' => $attributes['data']]);
    }

    //add attribute
    public function addAttribute()
    {
        //call getCategoriesSelect method from GeneralRepository to get categories - select
        $categories = $this->repo->getCategoriesSelect();

        //if response status = '0' show error page
        if ($categories['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.attributes.addAttribute', ['categories' => $categories['data']]);
    }

    //insert attribute
    public function insertAttribute(Request $request)
    {
        $category = $request->category;
        $featured = $request->featured;
        $searchable = $request->searchable;
        $is_input = $request->is_input;
        $icon = $request->icon;
        $en_name = $request->en_name;
        $hr_name = $request->hr_name;
        $de_name = $request->de_name;
        $it_name = $request->it_name;
        $fr_name = $request->fr_name;
        $ru_name = $request->ru_name;
        $dk_name = $request->dk_name;
        $no_name = $request->no_name;
        $sv_name = $request->sv_name;

        //validate form inputs
        $validator = Validator::make($request->all(), Attribute::validateAttributeForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('AddAttribute')->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call insertAttribute method from GeneralRepository to insert attribute
        $response = $this->repo->insertAttribute($category, $featured, $searchable, $is_input, $icon, $en_name, $hr_name, $de_name,
            $it_name, $fr_name, $ru_name, $dk_name, $no_name, $sv_name);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('AddAttribute')->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetAttributes')->with('success_message', trans('main.attribute_insert'));
    }

    //edit attribute
    public function editAttribute($id)
    {
        //call getCategoriesSelect method from GeneralRepository to get categories - select
        $categories = $this->repo->getCategoriesSelect();

        //call getAttributeDetails method from GeneralRepository to get attribute details
        $attribute = $this->repo->getAttributeDetails($id);

        //if response status = '0' show error page
        if ($categories['status'] == 0 || $attribute['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.attributes.editAttribute', ['categories' => $categories['data'],
            'attribute' => $attribute['data']]);
    }

    //update attribute
    public function updateAttribute(Request $request)
    {
        $id = $request->id;
        $category = $request->category;
        $featured = $request->featured;
        $searchable = $request->searchable;
        $is_input = $request->is_input;
        $icon = $request->icon;
        $en_name = $request->en_name;
        $hr_name = $request->hr_name;
        $de_name = $request->de_name;
        $it_name = $request->it_name;
        $fr_name = $request->fr_name;
        $ru_name = $request->ru_name;
        $dk_name = $request->dk_name;
        $no_name = $request->no_name;
        $sv_name = $request->sv_name;

        //validate form inputs
        $validator = Validator::make($request->all(), Attribute::validateAttributeForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('EditAttribute', $id)->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call updateAttribute method from GeneralRepository to update attribute
        $response = $this->repo->updateAttribute($id, $category, $featured, $searchable, $is_input, $icon, $en_name, $hr_name, $de_name,
            $it_name, $fr_name, $ru_name, $dk_name, $no_name, $sv_name);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('EditAttribute', $id)->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetAttributes')->with('success_message', trans('main.attribute_update'));
    }

    //delete attribute
    public function deleteAttribute($id)
    {
        //call deleteAttribute method from GeneralRepository to delete attribute
        $response = $this->repo->deleteAttribute($id);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('GetAttributes')->with('error_message', trans('errors.error'));
        }

        return redirect()->route('GetAttributes')->with('success_message', trans('main.attribute_delete'));
    }

    /*
    |--------------------------------------------------------------------------
    | Renters
    |--------------------------------------------------------------------------
    */

    //get renters
    public function getRenters()
    {
        //call getRenters method from UserRepository to get renters
        $this->repo = new UserRepository;
        $renters = $this->repo->getRenters();

        //if response status = '0' show error page
        if ($renters['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.renters.list', ['renters' => $renters['data']]);
    }

    //add renter
    public function addRenter()
    {
        return view('superAdmin.renters.addRenter');
    }

    //insert renter
    public function insertRenter(Request $request)
    {
        $full_name = $request->full_name;
        $email = $request->email;
        $password = $request->password;
        $address = $request->address;
        $city = $request->city;
        $zip_code = $request->zip_code;
        $phone = $request->phone;
        $oib = $request->oib;
        $invoice_full_name = $request->invoice_full_name;
        $invoice_address = $request->invoice_address;
        $invoice_city = $request->invoice_city;
        $invoice_zip_code = $request->invoice_zip_code;
        $domestic_owner = $request->domestic_owner;
        $domestic_bank = $request->domestic_bank;
        $account_number = $request->account_number;
        $foreign_owner = $request->foreign_owner;
        $foreign_bank = $request->foreign_bank;
        $iban = $request->iban;
        $swift = $request->swift;
        $host_full_name = $request->host_full_name;
        $host_email = $request->host_email;
        $languages = $request->host_languages;

        //validate form inputs
        $validator = Validator::make($request->all(), User::validateRenterForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('AddRenter')->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call insertRenter method from UserRepository to insert renter
        $this->repo = new UserRepository;
        $response = $this->repo->insertRenter($full_name, $email, $password, $address, $city, $zip_code, $phone, $oib,
            $invoice_full_name, $invoice_address, $invoice_city, $invoice_zip_code, $domestic_owner, $domestic_bank, $account_number,
            $foreign_owner, $foreign_bank, $iban, $swift, $host_full_name, $host_email, $languages);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('AddRenter')->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetRenters')->with('success_message', trans('main.renter_insert'));
    }

    //edit renter
    public function editRenter($id)
    {
        //call getRenterDetails method from UserRepository to get renter details
        $this->repo = new UserRepository;
        $renter = $this->repo->getRenterDetails($id);

        //if response status = '0' show error page
        if ($renter['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.renters.editRenter', ['renter' => $renter['data']]);
    }

    //update renter
    public function updateRenter(Request $request)
    {
        $id = $request->id;
        $full_name = $request->full_name;
        $email = $request->email;
        $password = $request->password;
        $address = $request->address;
        $city = $request->city;
        $zip_code = $request->zip_code;
        $phone = $request->phone;
        $oib = $request->oib;
        $invoice_full_name = $request->invoice_full_name;
        $invoice_address = $request->invoice_address;
        $invoice_city = $request->invoice_city;
        $invoice_zip_code = $request->invoice_zip_code;
        $domestic_owner = $request->domestic_owner;
        $domestic_bank = $request->domestic_bank;
        $account_number = $request->account_number;
        $foreign_owner = $request->foreign_owner;
        $foreign_bank = $request->foreign_bank;
        $iban = $request->iban;
        $swift = $request->swift;
        $host_full_name = $request->host_full_name;
        $host_email = $request->host_email;
        $languages = $request->host_languages;

        //validate form inputs
        $validator = Validator::make($request->all(), User::validateRenterForm($id, $password));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('EditRenter', $id)->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call updateRenter method from UserRepository to update renter
        $this->repo = new UserRepository;
        $response = $this->repo->updateRenter($id, $full_name, $email, $password, $address, $city, $zip_code, $phone, $oib,
            $invoice_full_name, $invoice_address, $invoice_city, $invoice_zip_code, $domestic_owner, $domestic_bank, $account_number,
            $foreign_owner, $foreign_bank, $iban, $swift, $host_full_name, $host_email, $languages);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('EditRenter', $id)->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetRenters')->with('success_message', trans('main.renter_update'));
    }

    //delete renter
    public function deleteRenter($id)
    {
        //call deleteRenter method from UserRepository to delete renter
        $this->repo = new UserRepository;
        $response = $this->repo->deleteRenter($id);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('GetRenters')->with('error_message', trans('errors.error'));
        }

        return redirect()->route('GetRenters')->with('success_message', trans('main.renter_delete'));
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    //get users
    public function getUsers()
    {
        //call getUsers method from UserRepository to get users
        $this->repo = new UserRepository;
        $users = $this->repo->getUsers();

        //if response status = '0' show error page
        if ($users['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.users.list', ['users' => $users['data']]);
    }

    /*
    |--------------------------------------------------------------------------
    | Villas
    |--------------------------------------------------------------------------
    */

    //get villas
    public function getVillas(Request $request)
    {
        //get search parameters
        $search_string = $request->search_string;
        $renter = $request->renter;

        //call getRentersSelect method from UserRepository to get renters - select
        $this->repo = new UserRepository;
        $renters = $this->repo->getRentersSelect(1);

        //call getVillas method from VillaRepository to get villas
        $this->repo = new VillaRepository;
        $villas = $this->repo->getVillas('F', 'F', $search_string, $renter);

        //if response status = '0' show error page
        if ($renters['status'] == 0 || $villas['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.villas.list', ['renters' => $renters['data'], 'search_string' => $search_string,
            'renter' => $renter, 'villas' => $villas['data']]);
    }

    //add villa
    public function addVilla()
    {
        //call getRentersSelect method from UserRepository to get renters - select
        $this->repo = new UserRepository;
        $renters = $this->repo->getRentersSelect();

        //call getAttributes method from GeneralRepository to get attributes
        $this->repo = new GeneralRepository;
        $attributes = $this->repo->getAttributes(1);

        //call deleteTempImages method from ImageRepository to delete temp images
        $this->repo = new ImageRepository;
        $delete = $this->repo->deleteTempImages();

        //get languages form array from GeneralRepository
        $languages_array = GeneralRepository::$lang_form_array;

        //call getMonthsSelect method from GeneralRepository to get months - select
        $months = GeneralRepository::getMonthsSelect();

        //if response status = '0' show error page
        if ($attributes['status'] == 0 || $renters['status'] == 0 || $delete['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.villas.addVilla', ['renters' => $renters['data'], 'attributes' => $attributes['data'],
            'languages_array' => $languages_array, 'months' => $months]);
    }

    //insert villa
    public function insertVilla(Request $request)
    {
        //call formatName method from GeneralRepository to format url name
        $request['url_name']= GeneralRepository::formatName($request['url_name']);

        //validate form inputs
        $validator = Validator::make($request->all(), Villa::validateVillaForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //validate villa prices
        $validator = CustomValidator::villaPrices($request['prices']);

        //if form input is not correct return error message
        if ($validator['status'] != 1)
        {
            return response()->json($validator);
        }

        //call insertVilla method from VillaRepository to insert villa
        $this->repo = new VillaRepository;
        $response = $this->repo->insertVilla($request);

        return response()->json($response);
    }

    //edit villa
    public function editVilla($id)
    {
        //call getRentersSelect method from UserRepository to get renters - select
        $this->repo = new UserRepository;
        $renters = $this->repo->getRentersSelect();

        //call getVillaDetails method from VillaRepository to get renter details
        $this->repo = new VillaRepository;
        $villa = $this->repo->getVillaDetails($id);

        //get languages form array from GeneralRepository
        $languages_array = GeneralRepository::$lang_form_array;

        //call getMonthsSelect method from GeneralRepository to get months - select
        $months = GeneralRepository::getMonthsSelect();

        //if response status = '0' show error page
        if ($renters['status'] == 0 || $villa['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.villas.editVilla', ['renters' => $renters['data'], 'villa' => $villa['data'],
            'languages_array' => $languages_array, 'months' => $months]);
    }

    //update villa
    public function updateVilla(Request $request)
    {
        //call formatName method from GeneralRepository to format url name
        $request['url_name']= GeneralRepository::formatName($request['url_name']);

        //validate form inputs
        $validator = Validator::make($request->all(), Villa::validateVillaForm($request->id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //validate villa prices
        $validator = CustomValidator::villaPrices($request['prices']);

        //if form input is not correct return error message
        if ($validator['status'] != 1)
        {
            return response()->json($validator);
        }

        //call updateVilla method from VillaRepository to update villa
        $this->repo = new VillaRepository;
        $response = $this->repo->UpdateVilla($request);

        return response()->json($response);
    }

    //delete villa
    public function deleteVilla($id)
    {
        //call deleteVilla method from VillaRepository to delete villa
        $this->repo = new VillaRepository;
        $response = $this->repo->deleteVilla($id);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('GetVillas')->with('error_message', trans('errors.error'));
        }

        return redirect()->route('GetVillas')->with('success_message', trans('main.villa_delete'));
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
        $villas = $this->repo->getVillasSelect('F');

        //if response status = '0' show error page
        if ($villas['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.bookings.calendar', ['villas' => $villas['data']]);
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
        $statuses = $this->repo->getStatusesSelect(1);

        //call getVillasSelect method from VillaRepository to get villas - select
        $this->repo = new VillaRepository;
        $villas = $this->repo->getVillasSelect('F', 1);

        //call getBookings method from BookingRepository to get bookings
        $this->repo = new BookingRepository;
        $bookings = $this->repo->getBookings($start_date, $end_date, $villa, $status);

        //if response status = '0' show error page
        if ($statuses['status'] == 0 || $villas['status'] == 0 || $bookings['status'] == 0)
        {
            return view('errors.500');
        }

        return view('superAdmin.bookings.list', ['statuses' => $statuses['data'], 'villas' => $villas['data'], 'status' => $status,
            'villa' => $villa, 'start_date' => $start_date, 'end_date' => $end_date, 'bookings' => $bookings['data']]);
    }
}
