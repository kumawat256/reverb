<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WebSocket\Client;
use App\Events\getLiveData;

class TestController extends Controller
{
    public function index(){
        while(true){
            sleep(3);
            $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJkaGFuIiwicGFydG5lcklkIjoiIiwiZXhwIjoxNzM4MDQ5OTE5LCJ0b2tlbkNvbnN1bWVyVHlwZSI6IlNFTEYiLCJ3ZWJob29rVXJsIjoiIiwiZGhhbkNsaWVudElkIjoiMTEwMTE5MzI2NSJ9.I-8S0LPd-1CQ7D8iKSyBZ2XF9SeC9HkLqJhsbihL7VP4-PTtv2bkF-wZmtNc27Z5uz-xWIxG0nySvnpbUZ5AAQ";
            $clientID = "update your client id";
            $url = "wss://api-feed.dhan.co?version=2&token=".$token."&clientId=".$clientID."&authType=2";
    
            try {
                // Connect to WebSocket
                $client = new Client($url);
    
                // Send the initial data payload
                $client->send(json_encode([
                    "RequestCode" => 15,
                    "InstrumentCount" => 2,
                    "InstrumentList" => [
                        [
                            "ExchangeSegment" => "NSE_EQ",
                            "SecurityId" => "1333"
                        ],
                        [
                            "ExchangeSegment" => "BSE_EQ",
                            "SecurityId" => "532540"
                        ]
                    ]
                ]));
    
                // Listen for incoming messages
                while (true) {
                    $response = $client->receive(); // Receive messages
                    $data = unpack('VfirstInt/VsecondInt/ffloat', $response);
    
                    // echo "<pre>"; print_r($data); die;
          
                    broadcast(new getLiveData($data));
                }
            } catch (\Exception $e) {
                \Log::error('WebSocket Error: ' . $e->getMessage());
            }
        }
    }
}
