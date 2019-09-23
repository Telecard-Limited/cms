<?php

namespace App\Listeners;

use App\Complain;
use App\Events\SendSMSEvent;
use App\Setting;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;

class SendSMSListener
{
    private $statuses = ['Open', 'Closed', 'Pending', 'Discarded', 'Follow-Up'];
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
        $status = $event->complain->ticket_status->name;
        if(in_array($status, $this->statuses)) {
            if($status == "Pending" || $status == "Follow-Up") {
                $this->sendMessage2($event->complain);
            } else {
                $this->sendMessage($event->complain);
            }
        }
    }

    public function sendMessage(Complain $complain)
    {
        $template = "Outlet: " . $complain->outlet->name . "\nComplaint Type: " . $complain->issues()->first()->category->name . "\nIssue: " . implode(",", $complain->issues()->pluck('name')->toArray()) . "\nCustomer Name: " . $complain->customer->name . "\nOrder ID: " . $complain->order_id . "\nOrder Date & Time: " . $complain->order_datetime . "\nCreation Time: " . $complain->created_at . ($complain->issues()->get(['name'])->contains('name','Late Delivery') ? "\nPromised Time: " . $complain->promised_time : "") . "\nInformed To: " . $complain->informed_to . "\nInformed By: " . $complain->informed_by . "\nStatus: " . $complain->ticket_status->name . "\nTicket#: " . $complain->getComplainNumber()  . ($complain->desc ? "\nDescription: $complain->desc" : "") . ($complain->remarks ? "\nRemarks: $complain->remarks" : "");
        $url = Setting::where("key", "=", "url")->first()->value;
        $username = Setting::where("key", "=", "username")->first()->value;
        $password = Setting::where("key", "=", "password")->first()->value;


        if($complain->message_recipients()->exists()) {
            $recipients = [];
            foreach ($complain->message_recipients as $message_recipient) {
                $recipients[] = explode(",", $message_recipient->numbers);
            }
            $recipients = collect(Arr::flatten($recipients))->unique()->all();
            $client = new Client();
            $response = $client->get($url, ['query' => [
                'username' => $username,
                'password' => $password,
                'receiver' => implode(",", $recipients),
                'msgdata' => $template
            ]]);
            $complain->message_responses()->create([
                'receiver' => implode(",", $recipients),
                'response' => $response->getReasonPhrase(),
                'code' => $response->getStatusCode(),
                'status' => $complain->ticket_status->name,
                'message' => $response->getBody()
            ]);
        }
    }

    public function sendMessage2(Complain $complain)
    {
        $template = "Outlet: " . $complain->outlet->name . "\nComplaint Type: " . $complain->issues()->first()->category . "\nIssue: " . implode(",", $complain->issues()->pluck('name')->toArray()) . "\nOrder ID: " . $complain->order_id . "\nOrder Date & Time: " . $complain->order_datetime . "\nInformed To: " . $complain->informed_to . "\nTicket#: " . $complain->getComplainNumber() . ($complain->remarks ? "\nRemarks: " . $complain->remarks : "");
        $url = Setting::where("key", "=", "url")->first()->value;
        $username = Setting::where("key", "=", "username")->first()->value;
        $password = Setting::where("key", "=", "password")->first()->value;

        if($complain->message_recipients()->exists()) {
            $recipients = [];
            foreach ($complain->message_recipients as $message_recipient) {
                $recipients[] = explode(",", $message_recipient->numbers);
            }
            $recipients = collect(Arr::flatten($recipients))->unique()->all();
            $client = new Client();
            $response = $client->get($url, ['query' => [
                'username' => $username,
                'password' => $password,
                'receiver' => implode(",", $recipients),
                'msgdata' => $template
            ]]);
            $complain->message_responses()->create([
                'receiver' => implode(",", $recipients),
                'response' => $response->getReasonPhrase(),
                'code' => $response->getStatusCode(),
                'status' => $complain->ticket_status->name,
                'message' => $response->getBody()
            ]);
        }
    }
}
