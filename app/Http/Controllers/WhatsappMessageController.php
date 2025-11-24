<?php

namespace App\Http\Controllers;

use App\Models\WhatsappMessage;
use App\Models\WhatsappMessageStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\WhatsappMessageResource;
use Inertia\Inertia;
use Illuminate\Support\Carbon;

class WhatsappMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = WhatsappMessage::latest()->paginate((new AppController())->paginate);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(WhatsappMessageResource::collection($messages));
        else {
            //Web Response
            return Inertia::render('Whatsapp/Index', [
                'messages' => WhatsappMessageResource::collection($messages),
            ]);
        }
    }

    public function callback(Request $request)
    {
        $request->validate([
            'value' => 'required'
        ]);

        $data = $request->value;

        //it is a status update
        if (isset($data['statuses'])) {
            //find existing whatsapp message
            $wamid =  $data['statuses'][0]['id'];
            $status = $data['statuses'][0]['status'];
            $timestamp = $data['statuses'][0]['timestamp'];
            $date = Carbon::createFromTimestamp($timestamp,'Africa/Lusaka')->format('M d, Y');
            $time = Carbon::createFromTimestamp($timestamp,'Africa/Lusaka')->format('H:i');

            $whatsapp_message = WhatsappMessage::where('wamid', $wamid)->first();

            if (!is_object($whatsapp_message)) {
                return response()->json(['message' => "Whatsapp Message [$wamid]: 404 - Not Found."], 404);
            }

            //add new status
            WhatsappMessageStatus::create([
                "status" => $status,
                "payload" => json_encode($data),
                "wamid" => $wamid,
                "whatsapp_message_id" => $whatsapp_message->id,
            ]);

            $suffix = '';
            if ($status == 'failed') {
                $reason = $data['statuses'][0]['errors'][0]['message'];
                $suffix = " Reason: $reason";
            }

            $status = ucfirst($status);
            $message = "Whatsapp message sent to {$whatsapp_message->name} status update: $status at $time on $date. $suffix";
            $log = "Whatsapp Message [$wamid]: Name: {$whatsapp_message->name}, Status: $status at $time on $date. $suffix";
            Log::info($log);
            (new NotificationController())->notifyWhatsappMessage($whatsapp_message, $message, true);
            return response()->json(['message' => $log], 200);
        }
        //it is a message from a client
        else {
            $name = $data['contacts'][0]['profile']['name'];
            $message = $data['messages'][0];
            $wamid = $message['id'];
            $phone_number = $message['from'];
            $type = $message['type'];
            $timestamp = $message['timestamp'];

            $date = Carbon::createFromTimestamp($timestamp,'Africa/Lusaka')->format('M d, Y');
            $time = Carbon::createFromTimestamp($timestamp,'Africa/Lusaka')->format('H:i');

            $client = (new ClientController())->getOrCreate($name, $phone_number);

            if ($type == 'unsupported') {
                return response()->json(['message' => "Whatsapp Message [$wamid]: 400 - Unsupported Type."], 400);
            }

            $whatsapp_message = WhatsappMessage::create([
                //ESSENTIALS
                "type" => 1, //client
                "phone_number" => $phone_number,
                "message_type" => $type,
                "wamid" => $wamid,
                "payload" => json_encode($data),

                //OPTIONAL
                'client_id' => $client?->id,

            ]);

            $prefix = $type == 'image' || $type == 'audio' ? 'an' : 'a';
            $message = "Received $prefix $type message from $name at $time on $date";
            $log = "Whatsapp Message [$wamid]: $message";

            Log::info($log);
            (new NotificationController())->notifyWhatsappMessage($whatsapp_message, $message);
            return response()->json(['message' => $log], 201);
        }
    }

    public function addStatus(Request $request)
    {
        Log::info($request->all());
        return response()->json(['message' => 'Returned status']);
    }
}
