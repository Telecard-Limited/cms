<?php

namespace App\Listeners;

use App\Complain;
use App\Events\SendSMSEvent;
use App\Setting;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSMSListener
{
    private $statuses = ['Open', 'Closed'];
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendSMSEvent  $event
     * @return void
     */
    public function handle(SendSMSEvent $event)
    {
        $this->sendMessage($event->complain);
    }

    public function sendMessage(Complain $complain)
    {
        $template = "Outlet: " . $complain->outlet->name . "\nComplaint Type: " . implode(",", $complain->issues()->pluck('name')->toArray()) . "\nInformed To: " . $complain->informed_to . "\nInformed By: " . $complain->customer->name . "\nTicket#: " . $complain->getComplainNumber() . "\nStatus: " . $complain->ticket_status->name . "\nDetails: " . $complain->desc;
        $url = Setting::where("key", "=", "url")->first()->value;
        $username = Setting::where("key", "=", "username")->first()->value;
        $password = Setting::where("key", "=", "password")->first()->value;
        if(in_array($complain->ticket_status->name, $this->statuses))
        {
            $client = new Client();
            $response = $client->get($url, ['query' => [
                'username' => $username,
                'password' => $password,
                'receiver' => $complain->customer->number,
                'msgdata' => $template
            ]]);
            $complain->message_responses()->create([
                'receiver' => $complain->customer->number,
                'response' => $response->getReasonPhrase(),
                'code' => $response->getStatusCode(),
                'status' => $complain->ticket_status->name,
                'message' => $response->getBody()
            ]);

            if($complain->message_recipients()->exists()) {
                foreach ($complain->message_recipients as $message_recipient) {
                    $client = new Client();
                    $response = $client->get($url, ['query' => [
                        'username' => $username,
                        'password' => $password,
                        'receiver' => $message_recipient->numbers,
                        'msgdata' => $template
                    ]]);
                    $complain->message_responses()->create([
                        'receiver' => $message_recipient->numbers,
                        'response' => $response->getReasonPhrase(),
                        'code' => $response->getStatusCode(),
                        'status' => $complain->ticket_status->name,
                        'message' => $response->getBody()
                    ]);
                }
            }
        }
    }
}
