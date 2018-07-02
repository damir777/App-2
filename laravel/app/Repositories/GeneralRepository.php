<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Country;
use App\Status;
use App\PaymentType;
use App\AttributeCategory;
use App\Attribute;
use App\Villa;
use App\VillaFeaturedImage;

class GeneralRepository
{
    public static $lang_form_array = ['en' => 'En', 'hr' => 'Hr', 'de' => 'De', 'it' => 'It', 'fr' => 'Fr', 'ru' => 'Ru',
        'dk' => 'Dk', 'no' => 'No', 'sv' => 'Sv'];

    public static function formatName($string)
    {
        $a = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö',
            'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ',
            'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ',
            'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ',
            'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ',
            'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ',
            'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů',
            'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ',
            'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', ' - ', '/', '(',
            ')', ' '];

        $b = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O',
            'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n',
            'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
            'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L',
            'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R',
            'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u',
            'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '-',
            '', '', '', '-'];

        return strtolower(str_replace($a, $b, $string));
    }

    public static function formatColumnName($column)
    {
        //get app locale
        $locale = App::getLocale();

        //format column name
        return $locale.'_'.$column;
    }

    public static function setBlogLinks()
    {
        //get app locale
        $locale = App::getLocale();

        //set default logged user variable
        $logged_user = 'false';

        //if user is logged in set logged user to 'true'
        if (Auth::user())
        {
            $logged_user = 'true';
        }

        //set links array
        $links_array = ['en' => [
            'guide' => 'http://blog.xx.com/en/guide?logged='.$logged_user,
                'destinations' => 'http://blog.xx.com/en/destinations?logged='.$logged_user,
                'adventures' => 'http://blog.xx.com/en/adventures?logged='.$logged_user],
            'de' => ['guide' => 'http://blog.xx.com/de/reisefuhrer?logged='.$logged_user,
                'destinations' => 'http://blog.xx.com/de/reiseziele?logged='.$logged_user,
                'adventures' => 'http://blog.xx.com/de/abenteuer?logged='.$logged_user],
            'hr' => ['guide' => 'http://blog.xx.com/hr/vodic?logged='.$logged_user,
                'destinations' => 'http://blog.xx.com/hr/destinacije?logged='.$logged_user,
                'adventures' => 'http://blog.xx.com/hr/avanture?logged='.$logged_user
            ]
        ];

        return $links_array[$locale];
    }

    public static function translateBookingCalendar()
    {
        //get app locale
        $locale = App::getLocale();

        //set calendar array
        $calendar_array = [
            'en' => ['selected' => '', 'night' => 'Night', 'nights' => 'Nights', 'button' => 'Close', 'clear' => 'Clear',
                'checkin-disabled' => 'Check-in disabled', 'checkout-disabled' => 'Check-out disabled',
                'day-names-short' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'day-names' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'month-names-short' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'month-names' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                    'November', 'December'],
                'error-more' => 'Date range should not be more than one night',
                'error-more-plural' => 'Date range should not be more than %d nights',
                'error-less' => 'Date range should not be less than one night',
                'error-less-plural' => 'Date range should not be less than %d nights',
                'info-more' => 'Please select a date range of at least one night',
                'info-more-plural' => 'Please select a date range of at least %d nights',
                'info-range' => 'Please select a date range between %d and %d nights',
                'info-default' => 'Please select a date range'],
            'de' => ['selected' => '', 'night' => 'Night', 'nights' => 'Nights', 'button' => 'Close', 'clear' => 'Clear',
                'checkin-disabled' => 'Check-in disabled', 'checkout-disabled' => 'Check-out disabled',
                'day-names-short' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'day-names' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'month-names-short' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'month-names' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                    'November', 'December'],
                'error-more' => 'Date range should not be more than one night',
                'error-more-plural' => 'Date range should not be more than %d nights',
                'error-less' => 'Date range should not be less than one night',
                'error-less-plural' => 'Date range should not be less than %d nights',
                'info-more' => 'Please select a date range of at least one night',
                'info-more-plural' => 'Please select a date range of at least %d nights',
                'info-range' => 'Please select a date range between %d and %d nights',
                'info-default' => 'Please select a date range'],
            'hr' => ['selected' => '', 'night' => 'Noć', 'nights' => 'Noći', 'button' => 'Zatvori', 'clear' => 'Poništi',
                'checkin-disabled' => 'Check-in nije moguć', 'checkout-disabled' => 'Check-out nije moguć',
                'day-names-short' => ['Ned', 'Pon', 'Uto', 'Sri', 'Čet', 'Pet', 'Sub'],
                'day-names' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'month-names-short' => ['Siječ', 'Velj', 'Ožu', 'Tra', 'Svi', 'Lip', 'Srp', 'Kol', 'Ruj', 'List', 'Stud', 'Pros'],
                'month-names' => ['Siječanj', 'Veljača', 'Ožujak', 'Travanj', 'Svibanj', 'Lipanj', 'Srpanj', 'Kolovoz', 'Rujan',
                    'Listopad', 'Studeni', 'Prosinac'],
                'error-more' => 'Period rezervacije ne smije biti veći od jedne noći',
                'error-more-plural' => 'Period rezervacije ne smije biti veći od %d noći',
                'error-less' => 'Period rezervacije ne smije biti manji od jedne noći',
                'error-less-plural' => 'Period rezervacije ne smije biti manji od %d noći',
                'info-more' => 'Molimo označite period rezervacije od najmanje jedne noći',
                'info-more-plural' => 'Molimo označite period rezervacije od najmanje %d noći',
                'info-range' => 'Molimo označite period rezervacije od %d do %d noći',
                'info-default' => 'Molimo označite period rezervacije']
        ];

        return $calendar_array[$locale];
    }

    //get months - select
    public static function getMonthsSelect()
    {
        $months = [trans('main.january'), trans('main.february'), trans('main.march'), trans('main.april'),
            trans('main.may'), trans('main.june'), trans('main.july'), trans('main.august'), trans('main.september'),
            trans('main.october'), trans('main.november'), trans('main.december')];

        //set months array
        $months_array = [];

        //loop through all months
        for ($i = 1; $i < 13; $i++)
        {
            //add month to months array
            $months_array[$i] = $months[$i - 1];
        }

        return $months_array;
    }

    //get mail chimp data
    public static function getMailChimpData()
    {
        //get app locale
        $locale = App::getLocale();

        //set mail chimp data array
        $mail_chimp_data_array = ['en' => ['action' => '8a74db3e2f9d37bccab9fba18&amp;id=4f7dcaff4d',
            'name' => 'b_8a74db3e2f9d37bccab9fba18_4f7dcaff4d'], 'de' => ['action' => '8a74db3e2f9d37bccab9fba18&amp;id=c785b6c2ac',
            'name' => 'b_8a74db3e2f9d37bccab9fba18_c785b6c2ac'], 'hr' => ['action' => '8a74db3e2f9d37bccab9fba18&amp;id=bd8d70ac75',
            'name' => 'b_8a74db3e2f9d37bccab9fba18_bd8d70ac75']];

        return $mail_chimp_data_array[$locale];
    }

    //get user home page route
    public function getUserHomePageRoute()
    {
        //set default route
        $route = 'HomePage';

        //get user
        $user = Auth::user();

        if ($user->hasRole('SuperAdmin'))
        {
            $route = 'GetCategories';
        }
        elseif ($user->hasRole('Renter'))
        {
            $route = 'GetRenterNewBookings';
        }

        return $route;
    }

    //get cities
    public function getCities()
    {
        try
        {
            $cities = Villa::select('city')->distinct()->get();

            return ['status' => 1, 'data' => $cities];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get countries - select
    public function getCountriesSelect()
    {
        //set countries array
        $countries_array = [];

        $countries = Country::get();

        //loop through all countries
        foreach ($countries as $country)
        {
            //add country to countries array
            $countries_array[$country->code] = $country->name;
        }

        return $countries_array;
    }

    //get statuses - select
    public function getStatusesSelect($default_option = false)
    {
        try
        {
            //set statuses array
            $statuses_array = [];

            $statuses = Status::where('id', '!=', 1)->get();

            if ($default_option)
            {
                //add default option to statuses array
                $statuses_array[0] = trans('main.choose_status');
            }

            //loop through all statuses
            foreach ($statuses as $status)
            {
                //add status to statuses array
                $statuses_array[$status->id] = trans_choice('main.'.$status->code, 2);
            }

            return ['status' => 1, 'data' => $statuses_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get payment types
    public function getPaymentTypes()
    {
        try
        {
            $types = PaymentType::orderBy('id', 'asc')->get();

            return ['status' => 1, 'data' => $types];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get homepage slider
    public function getHomepageSlider()
    {
        try
        {
            $slider = VillaFeaturedImage::with(['villa' => function($query) {
                    $query->select('id', 'name', 'url_name', 'city');
                }])
                ->whereHas('villa', function($query2) {
                    $query2->where('active', '=', 'T');
                })
                ->groupBy('villa_id')->get();

            foreach ($slider as $image)
            {
                //call setVillaFeaturedAttributes method from VillaRepository to set villa featured attributes
                $repo = new VillaRepository;
                $repo->setVillaFeaturedAttributes($image->villa);
            }

            return ['status' => 1, 'data' => $slider];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get filtered attributes
    public function getFilteredAttributes($search_attributes)
    {
        try
        {
            //set attributes array
            $attributes_array = [];

            //call formatColumnName method to format attribute column name
            $attribute_column_name = $this->formatColumnName('name');

            //call getAttributes method to get attributes
            $attributes = $this->getAttributes(null, 1);

            foreach ($attributes['data'] as $attribute)
            {
                //set default checked variable
                $checked = false;

                //if current attribute is filter attribute set checked variable to 'true'
                if (isset($search_attributes[$attribute->id]))
                {
                    $checked = true;
                }

                //add attribute to attributes array
                $attributes_array[] = ['id' => $attribute->id, 'name' => $attribute->$attribute_column_name, 'checked' => $checked];
            }

            return ['status' => 1, 'data' => $attributes_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get currency ratio - pbz xml
    public function getCurrencyRatio($code = false)
    {
        //if currency code is not set get session currency code
        if (!$code)
        {
            $code = Session::get('currency');
        }

        //set currencies array and default currency ratio
        $currencies_array = ['EUR' => ['value' => 0, 'sign' => '€'], 'USD' => ['value' => 0, 'sign' => '$']];
        $currency_ratio = 1;

        //get currencies from pbz xml
        $pbz_XML = file_get_contents('https://www.pbz.hr/Downloads/HNBteclist.xml');
        $XML = new \SimpleXMLElement($pbz_XML);
        $node = $XML->ExchRate;

        foreach($node->Currency as $key)
        {
            $currency = str_replace(',', '.', $key->MeanRate);
            $currency = (float)$currency;

            switch ($key->Name)
            {
                case 'EUR':
                    $currencies_array['EUR']['value'] = $currency;
                    break;
                case 'USD':
                    $currencies_array['USD']['value'] = $currency;
                    break;
            }
        }

        //if currency is 'USD' calculate currency ratio
        if ($code == 'USD')
        {
            $currency_ratio = $currencies_array['EUR']['value'] / $currencies_array[$code]['value'];

            $currency_ratio = number_format($currency_ratio, 5);
        }

        return ['ratio' => $currency_ratio, 'sign' => $currencies_array[$code]['sign']];
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute categories
    |--------------------------------------------------------------------------
    */

    //get categories
    public function getCategories()
    {
        try
        {
            $categories = AttributeCategory::select('*')->get();

            return ['status' => 1, 'data' => $categories];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert category
    public function insertCategory($en_name, $hr_name, $de_name, $it_name, $fr_name, $ru_name, $dk_name, $no_name, $sv_name)
    {
        try
        {
            $category = new AttributeCategory;
            $category->en_name = $en_name;
            $category->hr_name = $hr_name;
            $category->de_name = $de_name;
            $category->it_name = $it_name;
            $category->fr_name = $fr_name;
            $category->ru_name = $ru_name;
            $category->dk_name = $dk_name;
            $category->no_name = $no_name;
            $category->sv_name = $sv_name;
            $category->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get category details
    public function getCategoryDetails($id)
    {
        try
        {
            $category = AttributeCategory::where('id', '=', $id)->first();

            //if category doesn't exist return error status
            if (!$category)
            {
                return ['status' => 0];
            }

            return ['status' => 1, 'data' => $category];
        }
        catch (Exception $exp)
        {
            return ['status' => 0];
        }
    }

    //update category
    public function updateCategory($id, $en_name, $hr_name, $de_name, $it_name, $fr_name, $ru_name, $dk_name, $no_name, $sv_name)
    {
        try
        {
            $category = AttributeCategory::find($id);
            $category->en_name = $en_name;
            $category->hr_name = $hr_name;
            $category->de_name = $de_name;
            $category->it_name = $it_name;
            $category->fr_name = $fr_name;
            $category->ru_name = $ru_name;
            $category->dk_name = $dk_name;
            $category->no_name = $no_name;
            $category->sv_name = $sv_name;
            $category->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //delete category
    public function deleteCategory($id)
    {
        try
        {
            $category = AttributeCategory::where('id', '=', $id)->first();

            //if category doesn't exist return error status
            if (!$category)
            {
                return ['status' => 0];
            }

            $category->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get categories - select
    public function getCategoriesSelect()
    {
        try
        {
            //set categories array
            $categories_array = [];

            $categories = AttributeCategory::select('id', 'hr_name')->get();

            //loop through all categories
            foreach ($categories as $category)
            {
                //add villa to categories array
                $categories_array[$category->id] = $category->hr_name;
            }

            return ['status' => 1, 'data' => $categories_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    //get attributes
    public function getAttributes($is_villa = false, $is_search = false)
    {
        try
        {
            $attributes = Attribute::select('*');

            if ($is_villa)
            {
                $attributes = $attributes->get();
            }
            elseif ($is_search)
            {
                //filter non featured attributes
                $attributes = $attributes->where('featured', '=', 'F')->where('searchable', '=', 'T');

                $attributes = $attributes->get();
            }
            else
            {
                $attributes = $attributes->paginate(30);
            }

            return ['status' => 1, 'data' => $attributes];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert attribute
    public function insertAttribute($category, $featured, $searchable, $is_input, $icon, $en_name, $hr_name, $de_name, $it_name, $fr_name,
        $ru_name, $dk_name, $no_name, $sv_name)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            $attribute = new Attribute;
            $attribute->category_id = $category;
            $attribute->featured = $featured;
            $attribute->searchable = $searchable;
            $attribute->is_input = $is_input;
            $attribute->en_name = $en_name;
            $attribute->hr_name = $hr_name;
            $attribute->de_name = $de_name;
            $attribute->it_name = $it_name;
            $attribute->fr_name = $fr_name;
            $attribute->ru_name = $ru_name;
            $attribute->dk_name = $dk_name;
            $attribute->no_name = $no_name;
            $attribute->sv_name = $sv_name;

            if ($icon)
            {
                //call uploadIcon method from ImageRepository to upload icon
                $repo = new ImageRepository;
                $response = $repo->uploadIcon($icon);

                //if response status = 0 return error message
                if ($response['status'] == 0)
                {
                    return ['status' => 0];
                }

                $attribute->icon = $response['data'];
            }

            $attribute->save();

            //commit transaction
            DB::commit();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get attribute details
    public function getAttributeDetails($id)
    {
        try
        {
            $attribute = Attribute::where('id', '=', $id)->first();

            //if attribute doesn't exist return error status
            if (!$attribute)
            {
                return ['status' => 0];
            }

            return ['status' => 1, 'data' => $attribute];
        }
        catch (Exception $exp)
        {
            return ['status' => 0];
        }
    }

    //update attribute
    public function updateAttribute($id, $category, $featured, $searchable, $is_input, $icon, $en_name, $hr_name, $de_name, $it_name,
        $fr_name, $ru_name, $dk_name, $no_name, $sv_name)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            $attribute = Attribute::find($id);
            $attribute->category_id = $category;
            $attribute->featured = $featured;
            $attribute->searchable = $searchable;
            $attribute->is_input = $is_input;
            $attribute->en_name = $en_name;
            $attribute->hr_name = $hr_name;
            $attribute->de_name = $de_name;
            $attribute->it_name = $it_name;
            $attribute->fr_name = $fr_name;
            $attribute->ru_name = $ru_name;
            $attribute->dk_name = $dk_name;
            $attribute->no_name = $no_name;
            $attribute->sv_name = $sv_name;

            if ($icon)
            {
                //call uploadIcon method from ImageRepository to upload icon
                $repo = new ImageRepository;
                $response = $repo->uploadIcon($icon);

                //if response status = 0 return error message
                if ($response['status'] == 0)
                {
                    return ['status' => 0];
                }

                //call deleteIcon method from ImageRepository to delete icon
                $repo = new ImageRepository;
                $delete = $repo->deleteIcon($attribute->icon);

                //if response status = 0 return error message
                if ($delete['status'] == 0)
                {
                    return ['status' => 0];
                }

                $attribute->icon = $response['data'];
            }

            $attribute->save();

            //commit transaction
            DB::commit();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //delete attribute
    public function deleteAttribute($id)
    {
        try
        {
            $attribute = Attribute::where('id', '=', $id)->first();

            //if attribute doesn't exist return error status
            if (!$attribute)
            {
                return ['status' => 0];
            }

            $attribute->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}