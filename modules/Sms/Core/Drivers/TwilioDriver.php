<?php

namespace Modules\Sms\Core\Drivers;

use Modules\Sms\Core\Exceptions\SmsException;

class TwilioDriver extends Driver
{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function send()
    {
        // $data = [
        //     'To' => $this->recipient,
        //     'From' => $this->config['from'],
        //     'Body' => $this->message
        // ];
        $senderId = $this->config['sid'];
        $authKey = $this->config['from'];
        $token = $this->config['token'];
        $data = "{
            \"Text\": \"$this->message\",
            \"Number\": \".$this->recipient.\",
            \"SenderId\": \"$senderId\",
            \"DRNotifyUrl\": \"https://travent.ae/notifyurl\",
            \"DRNotifyHttpMethod\": \"POST\",
            \"Tool\": \"API\"
        }";
        // $url = $this->config['url'] . '/2010-04-01/Accounts/' . $this->config['sid'] . '/Messages.json';
        $url = "https://private-anon-c32c686ff3-smscountryapi.apiary-proxy.com/v0.1/Accounts/".$authKey."/SMSes/";
        $authTo = base64_encode($authKey.":".$token);
        $array = array(
            "Content-Type: application/json",
            "Authorization: Basic ".$authTo
        );
        $curl = $this->curl($data, $url, $array);
        $result = json_decode($curl, true);
        if (!empty($result['error_code'])) {
            throw new SmsException($result['error_message']);
        }
        if (!empty($result['code']) and is_numeric($result['code'])) {
            throw new SmsException($result['message']);
        }
        return $result;
    }

    // public function curl($url,$data){
    // 	$ch = curl_init();
    // 	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    // 	curl_setopt($ch, CURLOPT_HEADER, 0);
    // 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 	curl_setopt($ch, CURLOPT_URL, $url);
    // 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    // 	curl_setopt($ch, CURLOPT_USERPWD, $this->config['sid'].':'.$this->config['token']);
    // 	curl_setopt($ch, CURLOPT_POST, count($data));
    // 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    // 	$data = curl_exec($ch);
    // 	curl_close($ch);
    // 	return $data;
    // }

    public function curl($data, $url, $array)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $array);
        $data = curl_exec($ch);
        curl_close($ch);
        // var_dump($data);
        return $data;
    }

}
