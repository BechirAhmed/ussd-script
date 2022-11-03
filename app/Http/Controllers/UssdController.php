<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SmsBuilder;
use PhpSmpp\Transport\SocketTransport;

class UssdController extends Controller
{

    public function index()
    {
        SocketTransport::$forceIpv4=true;
        SocketTransport::$defaultDebug=true;
        $service = new \PhpSmpp\Service\Listener(['192.168.2.12:5000'], 'ibmbankuser', 'IB27M922', 'transceiver', true);
        $service->listen(function (\PhpSmpp\Pdu\Pdu $pdu) {
            var_dump($pdu->id);
            var_dump($pdu->sequence);
            if ($pdu instanceof \PhpSmpp\Pdu\Ussd) {
                var_dump($pdu->status);
                var_dump($pdu->source->value);
                var_dump($pdu->destination->value);
                var_dump($pdu->message);
                logger("=======================");
                logger($pdu->status);
                logger($pdu->source->value);
                logger($pdu->destination->value);
                logger($pdu->message);
                // do some job with ussd
            }
        });
    }

    public function sendSms(Request $request)
    {
        $service = new \PhpSmpp\Service\Sender(['192.168.2.12:5000'], 'ibmbankuser', 'IB27M922', 'transmitter');

        $smsId = $service->send(36565623, 'Hello world!', 'IBM');
        logger(json_encode($service));
        // $service->client->setTransport(new \PhpSmpp\Transport\FakeTransport());
        // $smsId = $service->sendUSSD(36565623, 'Hello world!', 'IBM', []);
        var_dump($smsId);
    }
}
