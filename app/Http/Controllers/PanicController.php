<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PanicController extends Controller
{

    const MAKER_KEY = "A1tB2vvaFu20Aj0g5pSTyb85ZlQAye2lrOft9-yrE7";
    const DEVICE_ID = "8394935169b8cea3b9495998de0f8071";
    const STREAM = "alert";
    const API_KEY = "ace59ecc3eec57c843e0afcc1eb4bfc2";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        echo "PANIC ?";
        //error_log("GET " . $request , 3, "../storage/logs/myerror.log");
        //var_dump($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendPanicAlert(Request $request)
    {
        echo "POST PANIC ALERT !";
        //$trigger_name = $request->get('trigger');
        //$location = $request->get('location'); // latitude, longitude, name, elevation, timestamp
        //$device = $request->get('device'); // id, name, serial
        //$values = $request->get('values'); // custom_data
        //var_dump($device);
        //var_dump($request);

        $location['latitude'] = $request->get('latitude');
        $location['longitude'] = $request->get('longitude');
        $location['elevation'] = $request->get('elevation');
        $message = $request->get('message');

       // $this->updateEventM2X('2');
        $this->sendLocationM2X($location);
        $this->sendTrigger($location, $message);

        error_log("POST " . $request , 3, "../storage/logs/myerror.log");
        //$this->sendTrigger();
    }

    public function sendTrigger($location, $message){
        $url = 'https://maker.ifttt.com/trigger/panic-alert/with/key/' . SELF::MAKER_KEY;
        $data = array('value1' => $message, 'value2' => $location['latitude'] ,'value3' => $location['longitude']);
        $json = json_encode($data);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => $json
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        error_log("Send Trigger Succesful " . $result , 3, "../storage/logs/myerror.log");
    }

    public function updateEventM2X($alert_code){
        $url = 'http://api-m2x.att.com/v2/devices/' . SELF::DEVICE_ID . '/streams/' . SELF::STREAM . '/value';
        $data = http_build_query(array('value' => $alert_code));

        $options = array(
            'http' => array(
                'header'  => "X-M2X-KEY: ". SELF::API_KEY . ",
                              Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'PUT',
                'content' => $data
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        error_log("Update Event M2X Succesful " . $result , 3, "../storage/logs/myerror.log");
    }

    public function sendLocationM2X($location){
        $url = "http://api-m2x.att.com/v2/devices/8394935169b8cea3b9495998de0f8071/location";
        $data = array('name' => "Location", 'latitude' => $location['latitude'] ,'longitude' => $location['longitude'], 'elevation' => $location['elevation']);
        $json = json_encode($data);

        $options = array(
            'http' => array(
                'header'  => "X-M2X-KEY: ace59ecc3eec57c843e0afcc1eb4bfc2,
                              Content-type: application/json",
                'method'  => 'PUT',
                'content' => $json
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        error_log("Update Event M2X Succesful " . $result , 3, "../storage/logs/myerror.log");
    }


}
