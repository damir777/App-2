<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\User;
use App\Country;

class PaymentRepository extends UserRepository
{
    private $secretKey;

    private $allowedLanguages;

    //get payment data
    public function getPaymentData()
    {
        try
        {
            //set credentials array
            $credentials_array = ['EUR' => ['shopID' => 'xx', 'secret_key' => 'xx'],
                'USD' => ['shopID' => 'xx', 'secret_key' => 'xx']];

            //get currency
            $currency = Session::get('currency');

            //set shopID and secret key
            $shopID = $credentials_array[$currency]['shopID'];
            $this->secretKey = $credentials_array[$currency]['secret_key'];

            //call getTempBookingDetails method from BookingRepository to get temp booking details
            $repo = new BookingRepository;
            $booking = $repo->getTempBookingDetails();

            $prod = 'https://form.wspay.biz/Authorization.aspx';
            $demo = 'https://formtest.wspay.biz/Authorization.aspx';
            $url = $demo;

            $shoppingCartID = $booking['id'];
            $totalAmount = number_format($booking['downpayment'], 0, '', '');

            if (Auth::user())
            {
                $user = User::find($this->getUserId());

                $name = explode(' ', $user->full_name);
                $first_name = $name[0];
                $last_name = $name[1];
                $country_code = $user->country;
                $phone = $user->phone;
                $email = $user->email;
            }
            else
            {
                $name = explode(' ', $booking['full_name']);
                $first_name = $name[0];
                $last_name = $name[1];
                $country_code = $booking['country'];
                $phone = $booking['phone'];
                $email = $booking['email'];
            }

            $country = Country::select('name')->where('code', '=', $country_code)->first();

            $streetAddress = '';
            $city = '';
            $postCode = '';

            $this->allowedLanguages = ['HR','EN','DE','IT','FR','NL','HU','RU','SK','CZ','PL','PT','ES','SL'];

            $data = [];

            $data['ShopID'] = $shopID;
            $data['ShoppingCartID'] = $shoppingCartID;
            $data['TotalAmount'] = $this->formatPrice($totalAmount);

            $data['Signature'] = $this->calculateRedirectSignature(
                $data['ShopID'],
                $data['ShoppingCartID'],
                $data['TotalAmount']
            );

            $data['ReturnUrl'] = route('WSPayResponse');
            $data['CancelUrl'] = Session::get('booking_url');
            $data['ReturnErrorURL'] = route('WSPayResponse');

            $data['Lang'] = $this->getShortLocaleCode();

            $data['CustomerFirstname'] = $this->replaceCroatianChars($first_name);
            $data['CustomerLastName'] = $this->replaceCroatianChars($last_name);
            $data['CustomerAddress'] = $this->replaceCroatianChars($streetAddress);
            $data['CustomerCity'] = $this->replaceCroatianChars($city);
            $data['CustomerZIP'] = $this->replaceCroatianChars($postCode);
            $data['CustomerCountry'] = $this->replaceCroatianChars($country->name);
            $data['CustomerPhone'] = $this->replaceCroatianChars($phone);
            $data['CustomerEmail'] = $this->replaceCroatianChars($email);

            return ['status' => 1, 'data' => $data, 'url' => $url];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    private function calculateRedirectSignature($shopId, $shoppingCartId, $totalAmount)
    {
        $cleanTotalAmount = str_replace(',', '', $totalAmount);
        $signature = $shopId.$this->secretKey.$shoppingCartId.$this->secretKey.$cleanTotalAmount.$this->secretKey;

        $signature = md5($signature);

        return $signature;
    }

    private function formatPrice($price)
    {
        $pricef = floatval($price);
        $result = number_format($pricef, 2 , ',' , '');

        return $result;
    }

    private function replaceCroatianChars($text)
    {
        $hr_chars = ['č', 'ć', 'đ', 'š', 'ž', 'Č', 'Ć', 'Đ', 'Š', 'Ž'];
        $ascii_chars = ['c', 'c', 'dj', 's', 'z', 'C', 'C', 'Dj', 'S', 'Z'];
        $result = str_replace($hr_chars, $ascii_chars, $text);

        return $result;
    }

    private function getShortLocaleCode()
    {
        $storeLocale = App::getLocale();

        $storeLocale = strtoupper(substr($storeLocale, 0, 2));

        if (!in_array($storeLocale, $this->allowedLanguages))
        {
            $storeLocale = 'EN';
        }

        return $storeLocale;
    }
}
