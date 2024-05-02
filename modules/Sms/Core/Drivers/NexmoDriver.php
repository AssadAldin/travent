<?php

namespace Modules\Sms\Core\Drivers;
use Modules\Sms\Core\Exceptions\SmsException;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class NexmoDriver extends Driver
{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function send()
    {
    	$data = [
//    		'from'=>$this->config['from'],
//		    'text'=>$this->message,
//		    'to'=>$this->recipient,
//		    'api_key'=>$this->config['key'],
//		    'api_secret'=>$this->config['secret'],


            'from'=>$this->config['from'],
            'text'=>$this->message,
            'to'=>$this->recipient,
            'user'=>$this->config['key'],
            'password'=>$this->config['secret'],
            'action'=>'sendsms',
	    ];

	    $curl = $this->nexmoCurl($data);
	    $result = json_decode($curl,true);
	    if(!empty($result['messages'][0]['error-text'])){
	    	throw  new SmsException($result['messages'][0]['error-text']);
	    }
	    return $result;
    }
    public function nexmoCurl($data){



        $from = $data['from'];
        $to = $data['to'];
        $text = $data['text'];
        $user = $data['user'];
        $password = $data['password'];


        $url = 'https://api.smsglobal.com/http-api.php';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = "action=sendsms&text=$text&to=$to&user=$user&password=$password&from=$from";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);


        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

	}


}
