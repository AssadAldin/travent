<?php

namespace Modules\Sms\Admin;

use App\Models\ChatsappAuth;
use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Sms\Core\Facade\Sms;
use Propaganistas\LaravelPhone\PhoneNumber;

class SmsController extends AdminController
{
    public function testSms(Request $request)
    {

        $to = $request->to;
        $message = $request->message;
        $this->validate($request, [
            'to' => 'required',
            'message' => 'required',
            'country' => 'required',
        ]);
        try {
            $to = (string) PhoneNumber::make($to)->ofCountry($request->country);
            Sms::to($to)->content($message)->send();
            //Whatsapp test
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
            //end Whatsapp
            return response()->json(['error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'messages' => $e->getMessage()], 200);
        }
    }
}
