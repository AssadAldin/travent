<?php

namespace App\Listeners;

use App\Models\ChatsappAuth;
use App\Events\SendWhatsappEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Propaganistas\LaravelPhone\PhoneNumber;

class SendWhatsappMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    const CODE = [
        'id' => '[booking_id]',
        'total' => '[price]',
        'service_name' => '[service_name]',
        'start_date' => '[start_date]',
        'total_guests' => '[total_guests]',
    ];
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendWhatsappEvent  $event
     * @return void
     */

    public function handle(SendWhatsappEvent $event)
    {
        //message content
        $messageContent = ChatsappAuth::find(1);
        // store old locale
        $old = app()->getLocale();
        if ($messageContent->open) {
            $booking = $event->booking;
            $vendor = $booking->vendor;
            $adminPhone = setting_item('admin_phone_has_booking');
            $adminCountry = setting_item('admin_country_has_booking');

            if (!empty($booking->phone) and !empty($booking->country)) {
                if ($old == 'ar') {
                    $message = $this->replaceMessage($booking, $messageContent->g_ar);
                } else {
                    $message = $this->replaceMessage($booking, $messageContent->g_en);
                }
                try {
                    $to = (string) PhoneNumber::make($booking->phone)->ofCountry($booking->country);
                    // $to = '971566628637';
                    $this->whatsapp($to, $message);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            // Check if user have notification language

            // if (Auth::user()->user_notifi_lang) {
            //     app()->setLocale(Auth::user()->user_notifi_lang);
            // }
            if (!empty($vendor->phone) and !empty($vendor->country)) {
                if (app()->getLocale() == 'ar') {
                    $message = $this->replaceMessage($booking, $messageContent->h_ar);
                } else {
                    $message = $this->replaceMessage($booking, $messageContent->h_en);
                }
                try {
                    $to = (string) PhoneNumber::make($vendor->phone)->ofCountry($vendor->country);
                    // $to = '971566628637';
                    $this->whatsapp($to, $message);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
            app()->setLocale($old);

            if (!empty($adminPhone) and !empty($adminCountry)) {
                if ($old == 'ar') {
                    $message = $this->replaceMessage($booking, $messageContent->a_ar);
                } else {
                    $message = $this->replaceMessage($booking, $messageContent->a_en);
                }
                try {
                    $to = (string) PhoneNumber::make($adminPhone)->ofCountry($adminCountry);
                    // $to = '971566628637';
                    $this->whatsapp($to, $message);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
    public function whatsapp($to, $message)
    {
        $whatsapp = ChatsappAuth::find(1);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://chat.travent.ae/api/create-message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'appkey' => $whatsapp->app_key,
                    'authkey' => $whatsapp->auth_key,
                    'to' => $to,
                    'message' => $message,
                    'sandbox' => 'false'
                ),
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
    }
    public function replaceMessage($booking, $content)
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {
                if ($value == '[service_name]') {
                    if (!empty($booking->service->title)) {
                        $content = str_replace('[service_name]', $booking->service->title, $content);
                    }
                } elseif ($value == '[start_date]') {
                    if (!empty($booking->start_date)) {
                        $date = strtotime($booking->start_date);
                        $newformat = date('Y-m-d', $date);
                        $content = str_replace('[start_date]', $newformat, $content);
                    }
                } else {
                    $content = str_replace($value, @$booking->$item, $content);
                }
            }

        } else {
            return $this->replaceMessage($booking, $this->defaultContent());
        }
        return $content;
    }

    public function defaultContent()
    {
        return 'Service Name: [service_name]
					Price: [price]
					Date: [start_date]
					Total: [total_guests]';
    }
}
