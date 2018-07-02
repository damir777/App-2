<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Villa;
use App\AttributeCategory;
use App\VillaAttribute;
use App\VillaFeaturedImage;
use App\VillaImage;
use App\VillaPrice;

class VillaRepository extends UserRepository
{
    //get villas
    public function getVillas($is_renter, $is_featured, $search_string = false, $renter = false, $site_search = false,
        $page_number = false, $city = false, $start_date = false, $end_date = false, $guests = false, $attributes = [])
    {
        try
        {
            //set villas array
            $villas_array = [];

            //call formatColumnName method from GeneralRepository to format short description column name
            $short_description_column_name = GeneralRepository::formatColumnName('short_description');

            $villas = Villa::with('renter', 'attribute')->select('*');

            if (!$site_search)
            {
                if ($is_renter == 'T')
                {
                    $villas->where('renter_id', '=', $this->getUserId());
                }

                if ($is_featured == 'T')
                {
                    $villas->where('active', '=', 'T')->where('featured', '=', 'T');

                    $villas = $villas->paginate(9);

                    foreach ($villas as $villa)
                    {
                        //call setVillaFeaturedImages method to set villa featured image
                        $this->setVillaFeaturedImages($villa);

                        //call setVillaFeaturedAttributes method to set villa featured attributes
                        $this->setVillaFeaturedAttributes($villa);

                        //call setVillaPrice method to set villa price
                        $this->setVillaPrice($villa);

                        //call setVillaDiscount method to set villa discount
                        $this->setVillaDiscount($villa);
                    }
                }
                else
                {
                    if ($search_string)
                    {
                        $villas->where(function($query) use ($search_string) {
                            $query->whereRaw('name LIKE ?', ['%'.$search_string.'%'])
                                ->orWhereRaw('city LIKE ?', ['%'.$search_string.'%']);
                        });
                    }

                    if ($renter)
                    {
                        $villas->where('renter_id', '=', $renter);
                    }

                    $villas = $villas->paginate(30);
                }
            }
            else
            {
                //set villas counter and villas array counter
                $villas_counter = 0;
                $villas_array_counter = 0;

                $villas->where('active', '=', 'T');

                if ($city)
                {
                    $villas->where(function($query) use ($city) {
                        $query->whereRaw('city LIKE ?', ['%'.$city.'%']);
                    });
                }

                if ($guests)
                {
                    $villas->whereHas('attribute', function($query) use ($guests) {
                        $query->where('attribute_id', '=', 2)->where('value', '>=', $guests);
                    });
                }

                //if attributes array is not empty filter villas by attributes
                if (count($attributes) > 0)
                {
                    foreach ($attributes as $attribute)
                    {
                        $villas->whereHas('attribute', function($query) use ($attribute) {
                           $query->where('attribute_id', '=', $attribute);
                        });
                    }
                }

                $villas = $villas->skip(($page_number - 1) * 4)->take(10000)->get();

                //if booking date exists filter available villas
                if ($start_date)
                {
                    //set search dates session
                    Session::put('search_dates', $start_date.' - '.$end_date);

                    //format start and end date
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = date('Y-m-d', strtotime($end_date));

                    $filtered_villas = $villas->filter(function($villa) use ($start_date, $end_date) {

                        //call checkBookingTime method from BookingRepository to check booking time
                        $repo = new BookingRepository;
                        $is_available = $repo->checkBookingTime('F', $villa->id, $start_date, $end_date);

                        //if booking time is not available remove villa from villas collection
                        if ($is_available == 'F')
                        {
                            return false;
                        }
                        else
                        {
                            return true;
                        }
                    });
                }
                else
                {
                    $filtered_villas = $villas;
                }

                foreach ($filtered_villas as $villa)
                {
                    //increase villas counter
                    $villas_counter++;

                    //if villas array has less than 20 villas add villa to villas array
                    if ($villas_array_counter < 4)
                    {
                        //increase villas array counter
                        $villas_array_counter++;

                        //call setVillaFeaturedImages method to set villa featured image
                        $this->setVillaFeaturedImages($villa);

                        //call setVillaFeaturedAttributes method to set villa featured attributes
                        $this->setVillaFeaturedAttributes($villa);

                        //call setVillaPrice method to set villa price
                        $this->setVillaPrice($villa);

                        //call setVillaDiscount method to set villa discount
                        $this->setVillaDiscount($villa);

                        //add villa to villas array
                        $villas_array[] = ['id' => $villa->id, 'url' => route('GetVillaDetails', $villa->url_name),
                            'name' => $villa->name, 'image_url' => URL::to('/').'/images/thumbs/'.$villa->id.'/',
                            'images' => $villa->images, 'city' => $villa->city,
                            'short_description' => $villa->$short_description_column_name,
                            'beds_icon' => URL::to('/').'/icon/'.$villa->featured_attributes[0]->attribute->icon,
                            'beds' => $villa->featured_attributes[0]->value,
                            'persons_icon' => URL::to('/').'/icon/'.$villa->featured_attributes[1]->attribute->icon,
                            'persons' => $villa->featured_attributes[1]->value, 'price' => $villa->price, 'discount' => $villa->discount,
                            'discount_text' => $villa->discount_text, 'latitude' => $villa->latitude, 'longitude' => $villa->longitude];
                    }

                    //if there is more villas for current page break villas loop
                    if ($villas_counter > $villas_array_counter)
                    {
                        break;
                    }
                }

                //if villas counter greater than villas array counter increase page number, else set page number to '0'
                if ($villas_counter > $villas_array_counter)
                {
                    $page_number++;
                }
                else
                {
                    $page_number = 0;
                }
            }

            return ['status' => 1, 'data' => $villas, 'villas_array' => $villas_array, 'next_page' => $page_number];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //insert villa
    public function insertVilla($input)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            //get lang form array from GeneralRepository
            $lang_form_array = GeneralRepository::$lang_form_array;

            $villa = new Villa;
            $villa->renter_id = $input['renter'];
            $villa->name = $input['name'];
            $villa->url_name = $input['url_name'];
            $villa->address = $input['address'];
            $villa->city = $input['city'];
            $villa->zip_code = $input['zip_code'];
            $villa->deposit = $input['deposit'];
            $villa->pets_price = $input['pets_price'];

            foreach ($lang_form_array as $key => $language)
            {
                //set column names
                $short_description_column_name = $key.'_short_description';
                $description_column_name = $key.'_description';

                $villa->$short_description_column_name = $input[$key.'_short_description'];
                $villa->$description_column_name = $input[$key.'_description'];
            }

            $villa->cash_payment = $input['cash_payment'];
            $villa->featured = $input['featured'];
            $villa->start_month = $input['start_month'];
            $villa->end_month = $input['end_month'];
            $villa->season_start_month = $input['season_start_month'];
            $villa->season_end_month = $input['season_end_month'];
            $villa->discount_type = $input['discount_type'];
            $villa->discount = $input['discount'];
            $villa->latitude = $input['latitude'];
            $villa->longitude = $input['longitude'];
            $villa->active = $input['active'];
            $villa->save();

            //update url name
            $villa->url_name = $villa->url_name.'-'.$villa->id;

            //if gallery session is set rename images and update villa id
            if (Session::has('gallery'))
            {
                //call moveTempImages method from ImageRepository to move temp images
                $repo = new ImageRepository;
                $response = $repo->moveTempImages(Session::get('gallery'), $villa->id, $villa->url_name, $villa->city);

                //if response status = 0 return error status
                if ($response['status'] == 0)
                {
                    return ['status' => 0, 'error' => trans('errors.error')];
                }
            }

            //if attributes array exists insert villa attributes
            if ($input['attributes'])
            {
                foreach ($input['attributes'] as $attribute)
                {
                    //insert villa attribute
                    $villa_attribute = new VillaAttribute;
                    $villa_attribute->villa_id = $villa->id;
                    $villa_attribute->attribute_id = $attribute['id'];

                    //if attribute doesn't have translations insert attribute value, else insert translations
                    if (isset($input['attribute_translations'][$attribute['id']]))
                    {
                        foreach ($lang_form_array as $key => $language)
                        {
                            //set column name
                            $translation_column_name = $key.'_value';

                            $villa_attribute->$translation_column_name = $input['attribute_translations'][$attribute['id']][$key];
                        }
                    }
                    else
                    {
                        $villa_attribute->value = $attribute['value'];
                    }

                    $villa_attribute->save();
                }
            }

            //set default year
            $year = '2010';

            //insert villa prices
            foreach ($input['prices'] as $price)
            {
                //insert villa price
                $villa_price = new VillaPrice;
                $villa_price->villa_id = $villa->id;
                $villa_price->start_day = date('Y-m-d', strtotime($price['start_day'].$year.'.'));
                $villa_price->end_day = date('Y-m-d', strtotime($price['end_day'].$year.'.'));
                $villa_price->price = $price['price'];
                $villa_price->save();
            }

            //commit transaction
            DB::commit();

            //set insert villa flash
            Session::flash('success_message', trans('main.villa_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get villa details
    public function getVillaDetails($id)
    {
        try
        {
            $villa = Villa::where('id', '=', $id)->first();

            //if villa doesn't exist return error status
            if (!$villa)
            {
                return ['status' => 0];
            }

            //add image path to villa object
            $villa->image_path = URL::to('/').'/images/'.$id.'/';

            //get featured images
            $featured_images = VillaFeaturedImage::select('id', 'image')->where('villa_id', '=', $id)->get();

            //get images
            $images = VillaImage::select('id', 'image')->where('villa_id', '=', $id)->get();

            //add featured images and images to vila object
            $villa->featured_images = $featured_images;
            $villa->images = $images;

            /*
            |--------------------------------------------------------------------------
            | Attributes
            |--------------------------------------------------------------------------
            */

            //set villa attributes array
            $villa_attributes_array = [];

            //get villa attribute
            $villa_attributes = VillaAttribute::where('villa_id', '=', $id)->get();

            foreach ($villa_attributes as $villa_attribute)
            {
                //add villa attribute to villa attributes array
                $villa_attributes_array[$villa_attribute->attribute_id] = $villa_attribute;
            }

            //call getAttributes method from GeneralRepository to get attributes
            $repo = new GeneralRepository;
            $attributes = $repo->getAttributes(1);

            //if response status = 0 return error status
            if ($attributes['status'] == 0)
            {
                return ['status' => 0];
            }

            //set attributes array
            $attributes_array = [];

            foreach ($attributes['data'] as $attribute)
            {
                //set translations array
                $translations_array = [];

                $checked = false;
                $value = null;
                $va_id = null;

                if (array_key_exists($attribute->id, $villa_attributes_array))
                {
                    $checked = true;
                    $value = $villa_attributes_array[$attribute->id]->value;
                    $va_id = $villa_attributes_array[$attribute->id]->id;

                    //if attribute has translation set translations array
                    if ($villa_attributes_array[$attribute->id]->en_value)
                    {
                        //add translation to translations array
                        $translations_array[$attribute->id] = ['en' => $villa_attributes_array[$attribute->id]->en_value,
                            'hr' => $villa_attributes_array[$attribute->id]->hr_value,
                            'de' => $villa_attributes_array[$attribute->id]->de_value,
                            'fr' => $villa_attributes_array[$attribute->id]->fr_value,
                            'it' => $villa_attributes_array[$attribute->id]->it_value,
                            'ru' => $villa_attributes_array[$attribute->id]->ru_value,
                            'dk' => $villa_attributes_array[$attribute->id]->dk_value,
                            'no' => $villa_attributes_array[$attribute->id]->no_value,
                            'sv' => $villa_attributes_array[$attribute->id]->sv_value];
                    }
                }

                //add attribute to attributes array
                $attributes_array[] = ['id' => $attribute->id, 'name' => $attribute->hr_name, 'featured' => $attribute->featured,
                    'is_input' => $attribute->is_input, 'checked' => $checked, 'value' => $value, 'va_id' => $va_id,
                    'translations' => $translations_array];
            }

            //add villa attributes to villa object
            $villa->attributes = $attributes_array;

            //call getVillaPrices method to get villa prices
            $prices_array = $this->getVillaPrices('T', $id);

            //add villa prices to villa object
            $villa->prices = $prices_array;

            return ['status' => 1, 'data' => $villa];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update villa
    public function updateVilla($input)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            //get lang form array from GeneralRepository
            $lang_form_array = GeneralRepository::$lang_form_array;

            $villa = Villa::find($input['id']);
            $villa->renter_id = $input['renter'];
            $villa->name = $input['name'];
            $villa->url_name = $input['url_name'];
            $villa->address = $input['address'];
            $villa->city = $input['city'];
            $villa->zip_code = $input['zip_code'];
            $villa->deposit = $input['deposit'];
            $villa->pets_price = $input['pets_price'];

            foreach ($lang_form_array as $key => $language)
            {
                //set column names
                $short_description_column_name = $key.'_short_description';
                $description_column_name = $key.'_description';

                $villa->$short_description_column_name = $input[$key.'_short_description'];
                $villa->$description_column_name = $input[$key.'_description'];
            }

            $villa->cash_payment = $input['cash_payment'];
            $villa->featured = $input['featured'];
            $villa->start_month = $input['start_month'];
            $villa->discount_type = $input['discount_type'];
            $villa->discount = $input['discount'];
            $villa->end_month = $input['end_month'];
            $villa->season_start_month = $input['season_start_month'];
            $villa->season_end_month = $input['season_end_month'];
            $villa->latitude = $input['latitude'];
            $villa->longitude = $input['longitude'];
            $villa->active = $input['active'];
            $villa->save();

            //set exclude va ids array
            $exclude_va_ids_array = [];

            //if attributes array exists insert villa attributes
            if ($input['attributes'])
            {
                foreach ($input['attributes'] as $attribute)
                {
                    //set default attribute value
                    $value = null;

                    //if va id exists update attribute, else insert new attribute
                    if (isset($attribute['va_id']))
                    {
                        //update villa attribute
                        $villa_attribute = VillaAttribute::where('villa_id', '=', $input['id'])
                            ->where('id', '=', $attribute['va_id'])->first();
                    }
                    else
                    {
                        //insert villa attribute
                        $villa_attribute = new VillaAttribute;
                        $villa_attribute->villa_id = $input['id'];
                        $villa_attribute->attribute_id = $attribute['id'];
                    }

                    //if attribute doesn't have translations update attribute value and set translations to 'null', else update attribute
                    //value and set translations to 'null'
                    if (isset($input['attribute_translations'][$attribute['id']]))
                    {
                        foreach ($lang_form_array as $key => $language)
                        {
                            //set column name
                            $translation_column_name = $key.'_value';

                            $villa_attribute->$translation_column_name = $input['attribute_translations'][$attribute['id']][$key];
                        }
                    }
                    else
                    {
                        $value = $attribute['value'];

                        foreach ($lang_form_array as $key => $language)
                        {
                            //set column name
                            $translation_column_name = $key.'_value';

                            $villa_attribute->$translation_column_name = null;
                        }
                    }

                    $villa_attribute->value = $value;
                    $villa_attribute->save();

                    //add va id to exclude array
                    $exclude_va_ids_array[] = $villa_attribute->id;
                }
            }

            //delete all villa attributes which are not in exclude va id array
            VillaAttribute::where('villa_id', '=', $input['id'])->whereNotIn('id', $exclude_va_ids_array)->delete();

            //delete old villa prices
            VillaPrice::where('villa_id', '=', $input['id'])->delete();

            //set default year
            $year = '2000';

            //insert villa prices
            foreach ($input['prices'] as $price)
            {
                //insert villa price
                $villa_price = new VillaPrice;
                $villa_price->villa_id = $villa->id;
                $villa_price->start_day = date('Y-m-d', strtotime($price['start_day'].$year.'.'));
                $villa_price->end_day = date('Y-m-d', strtotime($price['end_day'].$year.'.'));
                $villa_price->price = $price['price'];
                $villa_price->save();
            }

            //commit transaction
            DB::commit();

            //set update villa flash
            Session::flash('success_message', trans('main.villa_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //delete villa
    public function deleteVilla($id)
    {
        try
        {
            $villa = Villa::where('id', '=', $id)->first();

            //if villa doesn't exist return error status
            if (!$villa)
            {
                return ['status' => 0];
            }

            $villa->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get villas - select
    public function getVillasSelect($is_renter, $default_option = false)
    {
        try
        {
            //set villas array
            $villas_array = [];

            $villas = Villa::select('id', 'name');

            if ($is_renter == 'T')
            {
                $villas->where('renter_id', '=', $this->getUserId());
            }

            $villas = $villas->get();

            if ($default_option)
            {
                //add default option to villas array
                $villas_array[0] = trans('main.choose_villa');
            }

            //loop through all villas
            foreach ($villas as $villa)
            {
                //add villa to villas array
                $villas_array[$villa->id] = $villa->name;
            }

            return ['status' => 1, 'data' => $villas_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get site villa details
    public function getSiteVillaDetails($url_name)
    {
        try
        {
            $villa = Villa::where('url_name', '=', $url_name)->first();

            //if villa doesn't exist return error status
            if (!$villa)
            {
                return ['status' => 0];
            }

            //call formatColumnName method from GeneralRepository to format description column name
            $description_column_name = GeneralRepository::formatColumnName('description');

            //call getCurrencyRatio method from GeneralRepository to get currency ratio
            $repo = new GeneralRepository;
            $currency_ratio = $repo->getCurrencyRatio();

            //call setVillaImages method to set villa images
            $this->setVillaImages($villa);

            //call setVillaAttributes method to set villa attributes
            $this->setVillaAttributes($villa);

            //call setVillaFeaturedAttributes method to set villa featured attributes
            $this->setVillaFeaturedAttributes($villa);

            //call setVillaPrice method to set villa price
            $this->setVillaPrice($villa);

            //call setVillaDiscount method to set villa discount
            $this->setVillaDiscount($villa);

            //call setBookingSelectMenu method to set booking select menu
            $this->setBookingSelectMenu($villa);

            //call setSimilarVillas to set similar villas
            $this->setSimilarVillas($villa);

            //set villa description
            $villa->description = $villa->$description_column_name;

            //set deposit, pets price and cash payment
            if ($villa->deposit)
            {
                $villa->deposit = number_format($villa->deposit * $currency_ratio['ratio'], 0, '', '').' '.$currency_ratio['sign'];
            }
            else
            {
                $villa->deposit = $villa->deposit.' '.$currency_ratio['sign'];
            }

            if ($villa->pets_price)
            {
                $villa->pets_price = number_format($villa->pets_price * $currency_ratio['ratio'], 0, '', '').' '.
                    $currency_ratio['sign'];
            }
            else
            {
                $villa->pets_price = $villa->pets_price.' '.$currency_ratio['sign'];
            }

            if ($villa->cash_payment == 'T')
            {
                $villa->cash_payment = trans('main.yes');
            }
            else
            {
                $villa->cash_payment = trans('main.no');
            }

            //call getVillaPrices method to get villa prices
            $prices_array = $this->getVillaPrices('F', $villa->id, 1);

            //add villa prices to villa object
            $villa->prices = $prices_array;

            return ['status' => 1, 'data' => $villa];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //set villa images
    private function setVillaImages($villa_object)
    {
        //set images array
        $images_array = [];

        //get villa featured images
        $featured_images = VillaFeaturedImage::select('image')->where('villa_id', '=', $villa_object->id)->get();

        foreach ($featured_images as $image)
        {
            //add image to images array
            $images_array[] = $image->image;
        }

        //get villa images
        $images = VillaImage::select('image')->where('villa_id', '=', $villa_object->id)->get();

        foreach ($images as $image)
        {
            //add image to images array
            $images_array[] = $image->image;
        }

        //add images to villa object
        $villa_object->images = $images_array;
    }

    //set villa featured images
    private function setVillaFeaturedImages($villa_object)
    {
        //get villa featured images
        $images = VillaFeaturedImage::select('image')->where('villa_id', '=', $villa_object->id)->get();

        //add featured images to villa object
        $villa_object->images = $images;
    }

    //set villa attributes
    private function setVillaAttributes($villa_object)
    {
        //set attributes array
        $attributes_array = [];

        //call formatColumnName method from GeneralRepository to format name column name
        $name_column_name = GeneralRepository::formatColumnName('name');

        //call formatColumnName method from GeneralRepository to format value column name
        $value_column_name = GeneralRepository::formatColumnName('value');

        //get attribute categories
        $categories = AttributeCategory::select('id', $name_column_name)->orderBy('id', 'asc')->get();

        foreach ($categories as $category)
        {
            //get villa first and second featured attribute
            $attributes = VillaAttribute::with(['attribute' => function($query) use ($name_column_name) {
                    $query->select('id', $name_column_name.' as name');
                }])
                ->select('attribute_id', 'value', 'en_value', $value_column_name.' as trans_value')
                ->whereHas('attribute', function($query2) use ($category) {
                    $query2->where('featured', '=', 'F')->where('category_id', '=', $category->id);
                })
                ->where('villa_id', '=', $villa_object->id)->get();

            //if villa has attributes from current category add attributes to attributes array
            if ($attributes->isNotEmpty())
            {
                $attributes_array[] = ['category' => $category->$name_column_name, 'attributes' => $attributes];
            }
        }

        //add non featured attributes to villa object
        $villa_object->attributes = $attributes_array;
    }

    //set villa featured attributes
    public function setVillaFeaturedAttributes($villa_object)
    {
        //get villa first and second featured attribute
        $attributes = VillaAttribute::with('attribute')
            ->whereHas('attribute', function($query) {
                $query->where('featured', '=', 'T');
            })
            ->where('villa_id', '=', $villa_object->id)->take(2)->get();

        //add featured attributes to villa object
        $villa_object->featured_attributes = $attributes;
    }

    //set villa price
    private function setVillaPrice($villa_object)
    {
        //call getCurrencyRatio method from GeneralRepository to get currency ratio
        $repo = new GeneralRepository;
        $currency_ratio = $repo->getCurrencyRatio();

        //get villa min price
        $villa_price = VillaPrice::with('villa')
            ->select('villa_id', 'price')->where('villa_id', '=', $villa_object->id)->orderBy('price', 'asc')->first();

        //set villa default price
        $price = $villa_price->price;

        //if villa has discount calculate new price
        if ($villa_price->villa->discount)
        {
            $price = $price - ($price / 100 * $villa_price->villa->discount);
        }

        //add price to villa object
        $villa_object->price = number_format($price * $currency_ratio['ratio'], 0, '', '.').' '. $currency_ratio['sign'];
    }

    //set villa discount
    private function setVillaDiscount($villa_object)
    {
        //set villa discount if exists
        if ($villa_object->discount_type)
        {
            //set default discount text
            $discount_text = trans('main.first_minute');

            if ($villa_object->discount_type == 2)
            {
                $discount_text = trans('main.last_minute');
            }

            //add discount text to villa object
            $villa_object->discount_text = $discount_text;
        }
    }

    //set booking select menu
    private function setBookingSelectMenu($villa_object)
    {
        //generate booking select menu
        for ($i = 0; $i <= $villa_object->featured_attributes[1]->value; $i++)
        {
            $booking_select_menu[$i] = $i;
        }

        $villa_object->booking_select_menu = $booking_select_menu;
    }

    //set similar villas
    private function setSimilarVillas($villa_object)
    {
        $guests = $villa_object->featured_attributes[1]->value;

        $villas = Villa::with('attribute')
            ->select('*', DB::raw('(ACOS(SIN(RADIANS("'.$villa_object->latitude.'")) * SIN(RADIANS(latitude)) +
                COS(RADIANS("'.$villa_object->latitude.'")) * COS(RADIANS(latitude)) * COS(RADIANS("'.$villa_object->longitude.
                '" - longitude))) * 6371.0090667) AS distance'))
            ->whereHas('attribute', function($query) use ($guests) {
                $query->where('attribute_id', '=', 2)->where('value', '>=', $guests - 2)->where('value', '<=', $guests + 2);
            })
            ->where('active', '=', 'T')->where('id', '!=', $villa_object->id)->orderBy('distance', 'asc')->paginate(3);

        foreach ($villas as $villa)
        {
            //call setVillaFeaturedImages method to set villa featured image
            $this->setVillaFeaturedImages($villa);

            //call setVillaFeaturedAttributes method to set villa featured attributes
            $this->setVillaFeaturedAttributes($villa);
        }

        $villa_object->similar_villas = $villas;
    }

    //get villa disabled months
    public function getVillaDisabledMonths($start_month, $end_month)
    {
        //set disabled months array
        $disabled_months_array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        //set start month and end month
        $start_month = date('Y-m', strtotime(date('Y').'-'.$start_month));
        $end_month = date('Y-m', strtotime(date('Y').'-'.$end_month));

        //if start month != end month get disabled months
        if ($start_month != $end_month)
        {
            //if start month > end month add one year to end date
            if ($start_month > $end_month)
            {
                $end_month = date('Y-m', strtotime('+12 months', strtotime($end_month)));
            }

            while ($start_month <= $end_month)
            {
                //get current loop month index
                $index = array_search(date('n', strtotime($start_month)), $disabled_months_array);

                //remove current loop month from disabled months array
                unset($disabled_months_array[$index]);

                //add one month to start month
                $start_month = date('Y-m', strtotime('+1 months', strtotime($start_month)));
            }
        }

        return $disabled_months_array;
    }

    //get villa disabled days
    public function getVillaDisabledDays($villa_id, $start_month, $end_month)
    {
        //set disabled dates array
        $disabled_dates_array = [];

        //get current year
        $year = date('Y');

        //get price start day
        $price_start = VillaPrice::select('start_day')->where('villa_id', '=', $villa_id)->orderBy('id', 'asc')->first();
        $price_start_day = $price_start->start_day;

        //get price end day
        $price_end = VillaPrice::select('end_day')->where('villa_id', '=', $villa_id)->orderBy('id', 'desc')->first();
        $price_end_day = $price_end->end_day;

        //set compare start day - disabled days before price start day
        $current_day = date('Y-m-d', strtotime('2000-'.$start_month.'-01'));

        while ($current_day < $price_start_day)
        {
            //set loop current day
            $loop_current_day = date('Y-m-d', strtotime($year.'-'.date('m', strtotime($current_day)).'-'.
                date('d', strtotime($current_day))));

            //add current day to disabled dates array
            $disabled_dates_array[] = $loop_current_day;

            //add one year to loop current day
            $loop_current_day = date('Y-m-d', strtotime('+12 months', strtotime($loop_current_day)));

            //add current day (+ one year) to disabled dates array
            $disabled_dates_array[] = $loop_current_day;

            //add one day to current day
            $current_day = date('Y-m-d', strtotime('+1 day', strtotime($current_day)));
        }

        //set compare start day - disabled days after price end day
        $current_day = date('Y-m-d', strtotime('+1 day', strtotime($price_end_day)));

        //set compare end day
        $end_day = date('Y-m-d', strtotime('2000-'.$end_month.'-31'));

        while ($current_day <= $end_day)
        {
            //set loop current day
            $loop_current_day = date('Y-m-d', strtotime($year.'-'.date('m', strtotime($current_day)).'-'.
                date('d', strtotime($current_day))));

            //add current day to disabled dates array
            $disabled_dates_array[] = $loop_current_day;

            //add one year to loop current day
            $loop_current_day = date('Y-m-d', strtotime('+12 months', strtotime($loop_current_day)));

            //add current day (+ one year) to disabled dates array
            $disabled_dates_array[] = $loop_current_day;

            //add one day to current day
            $current_day = date('Y-m-d', strtotime('+1 day', strtotime($current_day)));
        }

        return $disabled_dates_array;
    }

    //get villa prices
    public function getVillaPrices($is_admin, $villa_id, $format = false)
    {
        //set prices array
        $prices_array = [];

        //call getCurrencyRatio method from GeneralRepository to get currency ratio
        $repo = new GeneralRepository;
        $currency_ratio = $repo->getCurrencyRatio();

        $prices = VillaPrice::with('villa')
            ->select('villa_id', DB::raw('DATE_FORMAT(start_day, "%d.%m.") AS start_day, DATE_FORMAT(end_day, "%d.%m.") AS end_day'),
                'price')->where('villa_id', '=', $villa_id)->get();

        foreach ($prices as $villa_price)
        {
            //set villa default price
            $price = $villa_price->price;

            //if is admin parameter = 'F' calculate villa discount if exists
            if ($is_admin == 'F')
            {
                //if villa has discount calculate new price
                if ($villa_price->villa->discount)
                {
                    $price = $price - ($price / 100 * $villa_price->villa->discount);
                }
            }

            //if format parameter exists format price
            if ($format)
            {
                $price = number_format($price * $currency_ratio['ratio'], 0, '', '.').' '.
                    $currency_ratio['sign'];
            }

            //add price to prices array
            $prices_array[] = ['start_day' => $villa_price->start_day, 'end_day' => $villa_price->end_day, 'price' => $price];
        }

        return $prices_array;
    }

    //get blog villas
    public function getBlogVillas($has_location, $location, $radius)
    {
        try
        {
            //set villas array
            $villas_array = [];

            //set latitude and longitude
            $separator_position = strpos($location, ',');
            $latitude = substr($location, 0, $separator_position);
            $longitude = substr($location, $separator_position + 1);

            $villas = Villa::with('attribute')->select('*')->where('active', '=', 'T');

            if ($has_location == 'T')
            {
                $villas->whereRaw('(ACOS(SIN(RADIANS("'.$latitude.'")) * SIN(RADIANS(latitude)) +
                    COS(RADIANS("'.$latitude.'")) * COS(RADIANS(latitude)) * COS(RADIANS("'.$longitude.
                    '" - longitude))) * 6371.0090667) < ?', [$radius]);
            }
            else
            {
                $villas->orderByRaw("RAND()");
            }

            $villas = $villas->take(3)->get();


            foreach ($villas as $villa)
            {
                //call setVillaFeaturedImages method to set villa featured image
                $this->setVillaFeaturedImages($villa);

                //call setVillaFeaturedAttributes method to set villa featured attributes
                $this->setVillaFeaturedAttributes($villa);

                //call setVillaPrice method to set villa price
                $this->setVillaPrice($villa);

                //add villa to villas array
                $villas_array[] = ['id' => $villa->id, 'url' => route('GetVillaDetails', $villa->url_name),
                    'name' => $villa->name, 'image_url' => URL::to('/').'/images/thumbs/'.$villa->id.'/',
                    'images' => $villa->images, 'city' => $villa->city,
                    'beds_icon' => URL::to('/').'/icon/'.$villa->featured_attributes[0]->attribute->icon,
                    'beds' => $villa->featured_attributes[0]->value,
                    'persons_icon' => URL::to('/').'/icon/'.$villa->featured_attributes[1]->attribute->icon,
                    'persons' => $villa->featured_attributes[1]->value, 'from' => trans('main.from'), 'price' => $villa->price,
                    'latitude' => $villa->latitude, 'longitude' => $villa->longitude];
            }

            return ['status' => 1, 'villas' => $villas_array, 'latitude' => $latitude, 'longitude' => $longitude];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
