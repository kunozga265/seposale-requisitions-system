<?php

namespace App\Http\Controllers;

use App\Models\WhatsappMessageTemplate;
use App\Models\Client;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Resources\ClientResource;
use App\Http\Resources\UserResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\CustomJob;
use App\Imports\ClientsCheckImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ClientType;
use Illuminate\Support\Carbon;
use App\Models\Referral;

class WhatsappMessageTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = WhatsappMessageTemplate::orderBy("name", "asc")->get();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json($templates);
        else {
            //Web Response
            return Inertia::render('WhatsappTemplates/Index', [
                'templates' => $templates,
            ]);
        }
    }
    public function send(Request $request)
    {
        $templates = WhatsappMessageTemplate::orderBy("name", "asc")->get();
        $clients = Client::orderBy("name", 'asc')->get();
        $users = User::orderBy("firstName", 'asc')->get();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json($templates);
        else {
            //Web Response
            return Inertia::render('WhatsappTemplates/Send', [
                'templates' => $templates,
                "clients" => ClientResource::collection($clients),
                "users" => UserResource::collection($users),
            ]);
        }
    }

    private function getFileData($file)
    {
        $base64String = $file;

        // Remove the "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," part if it exists
        if (strpos($base64String, ',') !== false) {
            $exploded = explode(',', $base64String);
            $base64String = $exploded[1];
            $ext = (new AppController())->getExtension($exploded);
        }

        // Decode base64 to binary
        return [
            "data" => base64_decode($base64String),
            "ext" => $ext
        ];
    }

    public function sendMessages(Request $request, $id)
    {
        $template = WhatsappMessageTemplate::find($id);


        if (is_object($template)) {



            if ($template->has_file) {

                $request->validate([
                    'template_file' => ['required'],
                ]);

                $template_filename = $request->template_file;

                // $template_file_data = $this->getFileData($request->template_file);

                // //Upload File
                // $template_filename = "files/template-messages/{$template->code}/" . uniqid() . $template_file_data['ext'];
                // Storage::disk('public_uploads')->put($template_filename, $template_file_data['data']);
            } else {
                $template_filename = null;
            }

            if ($request->type == 'upload') {

                $request->validate([
                    'file' => ['required'],
                ]);

                // Generate a temporary filename
                $tempFile = 'temp_excel_' . time() .  '.xlsx';

                try {
                    $fileData = $this->getFileData($request->file);

                    // $tempFile = 'temp_excel_' . time() .  $fileData['ext'];

                    // Store the file temporarily in storage/app
                    Storage::disk('temp')->put($tempFile, $fileData['data']);

                    // Import using Maatwebsite
                    Excel::import(new ClientsCheckImport(), storage_path('app/temp/' . $tempFile));

                    // Delete temp file after import
                    Storage::disk('temp')->delete($tempFile);

                    //Upload File
                    $filename = 'files/list-of-clients/' . uniqid() . '.xlsx';
                    // $filename = 'files/list-of-clients/' . uniqid() . $fileData['ext'];
                    Storage::disk('public_uploads')->put($filename, $fileData['data']);

                    CustomJob::create([
                        "status" => 0,
                        "type" => "BATCH_SEND",
                        "content" => json_encode([
                            'file' => $filename,
                            'referred_by_id' => $request->user_id,
                            'user_id' => Auth::id(),
                            'force_send' => $request->force_send,
                            'template' => $template,
                            'template_file' => $template_filename,
                        ]),
                    ]);
                } catch (\Exception $e) {
                    Storage::disk('temp')->delete($tempFile);
                    // dd($e);

                    Log::error($e);
                    return Redirect::back()->with('error', "An error occurred: {$e->getMessage()}");
                }

                if ((new AppController())->isApi($request))
                    //API Response
                    return response()->json(['message' => 'Clients uploaded and pricelists will be sent.'], 201);
                else {
                    //Web Response
                    return Redirect::back()->with('success', 'Clients uploaded and pricelists will be sent.');
                }
            } else {

                //get client info
                if (isset($request->client_id)) {
                    $request->validate([
                        'client_id' => ['required'],
                        'referred' => ['required'],
                    ]);

                    $client = Client::find($request->client_id);
                    if (!is_object($client)) {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => "Client not found"], 404);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'Client not found');
                        }
                    }
                } else {
                    $request->validate([
                        'name' => ['required'],
                        // 'client_type_id' => ['required'],
                        'phoneNumber' => ['required'],
                    ]);

                    $client_type_id = 7;

                    if (isset($request->client_type_id)) {
                        if ($request->client_type_id == 0) {
                            $request->validate([
                                'client_type' => ['required'],
                            ]);
                            $client_type_id = ClientType::create([
                                "name" => ucwords($request->client_type)
                            ])->id;
                        } else {
                            $client_type_id = $request->client_type_id;
                        }
                    }

                    if (Client::where("phone_number", $request->phoneNumber)->exists()) {
                        $existing_client = Client::where("phone_number", $request->phoneNumber)->first();
                        if ((new AppController())->isApi($request))
                            //API Response
                            return response()->json(["message" => "Client with that phone number exists: {$existing_client->getName()}"], 400);
                        else {
                            //Web Response
                            return Redirect::back()->with('error', "Client with that phone number exists: {$existing_client->getName()}");
                        }
                    }

                    $client = Client::create([
                        'serial' => (new AppController())->generateUniqueCode("CLIENT"),
                        'name' => ucwords($request->name),
                        'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                        'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                        'email' => $request->email,
                        'address' => $request->address,
                        'organisation' => $request->organisation,
                        'alias' => $request->alias,
                        'client_type_id' => $client_type_id,
                    ]);
                }

                //send the pricelist
                if ($request->referred) {

                    $request->validate([
                        'user_id' => ['required'],
                    ]);
                    $name = User::find($request->user_id)->fullName();
                    $message = "You have been referred to us by {$name}.";

                    Referral::create([
                        'date' => Carbon::now()->getTimestamp(),
                        'referred_by_id' => $request->user_id,
                        'client_id' => $client->id,
                        'user_id' => Auth::id(),
                    ]);
                } else {
                    $message = "Quality products and services are guaranteed.";
                }

                $res = (new NotificationController())->processWhatsappTemplateMessage($template, $client->serial, $message, $template_filename);


                if ((new AppController())->isApi($request))
                    //API Response
                    return response()->json(new ClientResource($client), 201);
                else {
                    //Web Response
                    if ($res) {
                        return Redirect::back()->with('success', 'Successfully sent!');
                    } else
                        return Redirect::back()->with('error', 'Could not send. An error occurred.!');
                }
            }
        } else {
            return Redirect::back()->with('error', 'Template not found');
        }
    }
    public function create(Request $request)
    {

        return Inertia::render('WhatsappTemplates/Create', []);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'code' => ['required'],
            'has_file' => ['required'],
        ]);

        WhatsappMessageTemplate::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'has_file' => $request->has_file,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json([], 201);
        else {
            //Web Response
            return Redirect::back()->with('success', 'Template created!');
        }
    }

    public function edit(Request $request, $id)
    {
        $template = WhatsappMessageTemplate::find($id);

        if (is_object($template)) {

            return Inertia::render('WhatsappTemplates/Edit', [
                'template' => $template
            ]);
        } else {
            return Redirect::back()->with('error', 'Template not found');
        }
    }

    public function update(Request $request, $id)
    {

        $template = WhatsappMessageTemplate::find($id);

        if (is_object($template)) {

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'code' => ['required'],
                'has_file' => ['required'],
            ]);

            $template->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'has_file' => $request->has_file,
            ]);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json([], 201);
            else {
                //Web Response
                return Redirect::back()->with('success', 'Template updated!');
            }
        } else {
            return Redirect::back()->with('error', 'Template not found');
        }
    }

    public function destroy(Request $request, $id)
    {

        $template = WhatsappMessageTemplate::find($id);

        if (is_object($template)) {

            $template->delete();

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json($template, 201);
            else {
                //Web Response
                return Redirect::route('whatsapp.templates.index')->with('success', 'Template deleted!');
            }
        } else {
            return Redirect::back()->with('error', 'Template not found');
        }
    }
}
