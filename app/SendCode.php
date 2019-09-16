<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendCode extends Model
{
    public static function sendCode($phone){
    	$code = rand(1111,9999);
    	// $nexmo = app('Nexmo/Client');
    	$basic  = new \Nexmo\Client\Credentials\Basic('376342bc', 'WYS5UIsX6afFI0QN');
        $client = new \Nexmo\Client($basic);
    	$client->message()->send([
    		'to' => '+254'.(int) $phone,
    		'from' => '+254725212418',
    		'text' => 'Verify Code: '. $code,
    	]);
    	return $code;
    }
}
