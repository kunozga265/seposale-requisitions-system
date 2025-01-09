<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Mail\ProjectNewMail;
use App\Mail\RequestFormApprovedMail;
use App\Mail\RequestFormDeniedMail;
use App\Mail\RequestFormInitiatedMail;
use App\Mail\RequestFormPendingApprovalMail;
use App\Mail\RequestFormReconciledMail;
use App\Mail\RequestFormWaitingInitiationMail;
use App\Mail\RequestFormWaitingReconciliationMail;
use App\Mail\UserDisabledMail;
use App\Mail\UserNewMail;
use App\Mail\UserVerifiedMail;
use App\Mail\VehicleNewMail;
use App\Models\Collection;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Position;
use App\Models\Quotation;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\Sale;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        $unsorted = $user->userNotifications()->latest()->get();
        $sorted = [];

        if (!$unsorted->isEmpty()) {
            $currentMonth = date('F', $unsorted[0]->created_at->getTimestamp());
            $currentYear = date('Y', $unsorted[0]->created_at->getTimestamp());

            $item = 0;
            $index = 0;
            foreach ($unsorted as $notification) {
                //Mark as read
                if ($notification->read == 0)
                    $notification->update(['read' => 1]);

                if ($item == 0) {
                    $sorted[0] = [
                        'month' => $currentMonth,
                        'year' => $currentYear,
                        'notifications' => [new NotificationResource($notification)]
                    ];
                } else {
                    $month = date('F', $unsorted[$item]->created_at->getTimestamp());
                    $year = date('Y', $unsorted[$item]->created_at->getTimestamp());

                    if ($currentMonth === $month && $currentYear === $year) {
                        $sorted[$index]['notifications'][] = new NotificationResource($notification);
                    } else {
                        $index += 1;
                        $currentMonth = date('F', $unsorted[$item]->created_at->getTimestamp());
                        $currentYear = date('Y', $unsorted[$item]->created_at->getTimestamp());

                        $sorted[$index] = [
                            'month' => $currentMonth,
                            'year' => $currentYear,
                            'notifications' => [new NotificationResource($notification)]
                        ];
                    }
                }
                $item += 1;
            }
        }

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json($sorted);
        else {
            //Web Response
            return Inertia::render('Notifications', [
                'notificationContainer' => $sorted
            ]);
        }
    }


    public function notifyManagement($object, $type)
    {
        $role = Role::where('name', 'management')->first();
        $managers = $role->users;
        $positionTitle = "Managing Director";

        if ($type == "USER_NEW") {
            //object is user
            $message = "$object->firstName $object->lastName has registered into the system. Ensure you confirm their details and verify their account to be able to use the system.";
            $subject = "New User: $object->firstName $object->lastName";

            //Create a notification for managers
            foreach ($managers as $manager) {
                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'userId' => $object->id
                    ]),
                    'type' => $type,
                    'user_id' => $manager->id,
                ]);
            }
            //Send a push notification to the app for the manager
            $this->pushNotification($positionTitle, $subject, $message);

            //Send email to managers
            Mail::to($managers)->send(new UserNewMail($object, $subject));

        } elseif ($type == "PROJECT_NEW") {
            //project is the object
            $message = "A new project ($object->name) has been registered into the system. Please confirm its details and verify it.";
            $subject = "New Project: " . $object->name;
            //Create a notification for managers
            foreach ($managers as $manager) {
                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'projectId' => $object->id
                    ]),
                    'type' => $type,
                    'user_id' => $manager->id,
                ]);
            }

            //Send a push notification to the app for the manager
            $this->pushNotification($positionTitle, $subject, $message);

            //Send email to managers
            Mail::to($managers)->send(new ProjectNewMail($subject, $object));

        } elseif ($type == "VEHICLE_NEW") {
            $message = "A new vehicle with registration number: $object->vehicleRegistrationNumber has been registered into the system. Please confirm its details and verify it.";
            $subject = "New Vehicle: " . $object->vehicleRegistrationNumber;
            //Create a notification for managers
            foreach ($managers as $manager) {
                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'vehicleId' => $object->id
                    ]),
                    'type' => $type,
                    'user_id' => $manager->id,
                ]);
            }
            //Send a push notification to the app for the manager
            $this->pushNotification($positionTitle, $subject, $message);

            //Send email to managers
            Mail::to($managers)->send(new VehicleNewMail($subject, $object));

        } elseif ($type == "REQUEST_FORM_PENDING") {
            //object is the request
            $name = $object->user->firstName . " " . $object->user->lastName;
            $positionTitle = $object->user->position->title;
            $message = "$name ($positionTitle) has submitted a request. May you please attend to it as soon as possible.";
            $subject = $this->getRequestTitle($object->type, $object->code) . " Pending Approval";

            //Create a notification for managers
            foreach ($managers as $manager) {
                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'type' => $object->type,
                        'requestId' => $object->id
                    ]),
                    'type' => $type,
                    'user_id' => $manager->id,
                ]);

                //Send email to manager
                Mail::to($manager)->send(new RequestFormPendingApprovalMail($manager, $message, $subject));
            }

            //Send a push notification to the app for the manager
            $this->pushNotification($positionTitle, $subject, $message);

        } elseif ($type == "REQUEST_FORM_RESUBMITTED") {
            $name = $object->user->firstName . " " . $object->user->lastName;
            $positionTitle = $object->user->position->title;
            $message = "$name ($positionTitle) has edited and resubmitted their request. May you please attend to it as soon as possible.";
            $subject = $this->getRequestTitle($object->type, $object->code) . " Pending Approval - Resubmitted";

            //Create a notification for managers
            foreach ($managers as $manager) {
                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'type' => $object->type,
                        'requestId' => $object->id
                    ]),
                    'type' => $type,
                    'user_id' => $manager->id,
                ]);

                //Send email to manager
                Mail::to($manager)->send(new RequestFormPendingApprovalMail($manager, $message, $subject));
            }

            //Send a push notification to the app for the manager
            $this->pushNotification($positionTitle, $subject, $message);
        }
    }

    public function notifyUser($object, $type)
    {
        if ($type == "USER_VERIFIED") {
            //object is user
            $message = "Your account has been verified. You are now able to use the system.";
            Notification::create([
                'contents' => json_encode([
                    'message' => $message
                ]),
                'type' => $type,
                'user_id' => $object->id,
            ]);

            //Send a push notification to the app for the user
            $this->pushNotification($object->id, "Account Verified", $message);

            Mail::to($object)->send(new UserVerifiedMail());


        } elseif ($type == "USER_DISABLED") {
            $message = "Your account has been disabled. You are no longer able to use the system. If you have any queries, see the system administrator.";
            Notification::create([
                'contents' => json_encode([
                    'message' => $message
                ]),
                'type' => $type,
                'user_id' => $object->id,
            ]);

            //Send a push notification to the app for the user
            $this->pushNotification($object->id, "Account Disabled", $message);

            Mail::to($object)->send(new UserDisabledMail());

        } elseif ($type == "REQUEST_FORM_PENDING") {
            //Find the next person(s) to approve
            $position = Position::find($object->stagesApprovalPosition);
            $employees = $position->users;

            $name = $object->user->firstName . " " . $object->user->lastName;
            $positionTitle = $object->user->position->title;
            $message = "$name ($positionTitle) has submitted a request. May you please attend to it as soon as possible.";
            $subject = $this->getRequestTitle($object->type, $object->code) . " Pending Approval";

            foreach ($employees as $employee) {

                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'type' => $object->type,
                        'requestId' => $object->id,
                    ]),
                    'type' => $type,
                    'user_id' => $employee->id,
                ]);

                //Send email to employees who can approve
                Mail::to($employee)->send(new RequestFormPendingApprovalMail($employee, $message, $subject));
            }

            //Send a push notification to the app for the user
            $this->pushNotification($position->title, $subject, $message);

        } elseif ($type == "REQUEST_FORM_RESUBMITTED") {
            //Find the next person(s) to approve
            $position = Position::find($object->stagesApprovalPosition);
            $employees = $position->users;

            $name = $object->user->firstName . " " . $object->user->lastName;
            $positionTitle = $object->user->position->title;
            $message = "$name ($positionTitle) has edited and resubmitted their request. May you please attend to it as soon as possible.";
            $subject = $this->getRequestTitle($object->type, $object->code) . " Pending Approval - Resubmitted";

            foreach ($employees as $employee) {

                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'type' => $object->type,
                        'requestId' => $object->id,
                    ]),
                    'type' => $type,
                    'user_id' => $employee->id,
                ]);

                //Send email to managers
                Mail::to($employee)->send(new RequestFormPendingApprovalMail($employee, $message, $subject));
            }

            //Send a push notification to the app for the user
            $this->pushNotification($position->title, $subject, $message);


        } elseif ($type == "INITIATED") {
            $message = "The request has been initiated by the Accounts Department.";
            $subject = $this->getRequestTitle($object->type, $object->code) . " Initiated";

            Notification::create([
                'contents' => json_encode([
                    'message' => $message,
                    'type' => $object->type,
                    'requestId' => $object->id,
                ]),
                'type' => $type,
                'user_id' => $object->id,
            ]);

            //Send a push notification to the app for the user
            $this->pushNotification($object->id, $subject, $message);

            $name = $object->firstName . " " . $object->lastName;
            Mail::to($object->user)->send(new RequestFormInitiatedMail($name, $subject));

        } elseif ($type == "RECONCILED") {
            $message = "The request has been reconciled by the Accounts Department.";
            $subject = $this->getRequestTitle($object->type, $object->code) . " Reconciled";

            Notification::create([
                'contents' => json_encode([
                    'message' => $message,
                    'type' => $object->type,
                    'requestId' => $object->id,
                ]),
                'type' => $type,
                'user_id' => $object->id,
            ]);

            //Send a push notification to the app for the user
            $this->pushNotification($object->id, $subject, $message);

            $name = $object->firstName . " " . $object->lastName;
            Mail::to($object->user)->send(new RequestFormReconciledMail($name, $subject));

        }
    }

    public function notifyApproval($requestForm, $approvedBy)
    {
        $approvedByName = $approvedBy->firstName . " " . $approvedBy->lastName;
        $positionTitle = $approvedBy->position->title;
        $message = "$approvedByName ($positionTitle) has approved your request. Your request has gone to the next stage.";
        $subject = $this->getRequestTitle($requestForm->type, $requestForm->code) . " Approved";
        Notification::create([
            'contents' => json_encode([
                'message' => $message,
                'type' => $requestForm->type,
                'requestId' => $requestForm->id,
            ]),
            'type' => "REQUEST_FORM_APPROVED",
            'user_id' => $requestForm->user->id,
        ]);

        //Send a push notification to the app for the user
        $this->pushNotification($requestForm->user->id, $subject, $message);

        //Send email
        Mail::to($requestForm->user)->send(new RequestFormApprovedMail($requestForm, $approvedBy, $subject));
    }

    public function notifyDenial($requestForm, $deniedBy)
    {
        $deniedByName = $deniedBy->firstName . " " . $deniedBy->lastName;
        $positionTitle = $deniedBy->position->title;
        $message = "The request has been denied by $deniedByName ($positionTitle). View the request to see the reason why.";
        $subject = $this->getRequestTitle($requestForm->type, $requestForm->code) . " Denied";

        Notification::create([
            'contents' => json_encode([
                'message' => $message,
                'type' => $requestForm->type,
                'requestId' => $requestForm->id,
            ]),
            'type' => "REQUEST_FORM_DENIED",
            'user_id' => $requestForm->user->id,
        ]);

        //Send a push notification to the app for the user
        $this->pushNotification($requestForm->user->id, $subject, $message);

        //Send email
        Mail::to($requestForm->user)->send(new RequestFormDeniedMail($requestForm->user, $message, $subject));
    }

    public function notifyFinance($requestForm, $type)
    {
        $role = Role::where('name', 'accountant')->first();
        $accountants = $role->users;
        $positionTitle = $requestForm->user->position->title;

        if ($type == "WAITING_INITIATE") {
            $name = $requestForm->user->firstName . " " . $requestForm->user->lastName;
            $message = "$name ($positionTitle) has submitted a request and it has been approved. May you please attend to it as soon as possible.";
            $subject = $this->getRequestTitle($requestForm->type, $requestForm->code) . " Waiting Initiation";

            foreach ($accountants as $accountant) {

                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'type' => $requestForm->type,
                        'requestId' => $requestForm->id,
                    ]),
                    'type' => $type,
                    'user_id' => $accountant->id,
                ]);

                //Send email to accountants
                Mail::to($accountant)->send(new RequestFormWaitingInitiationMail($accountant, $message, $subject));
            }
            //Send a push notification to the app for the accountant
            $this->pushNotification($positionTitle, $subject, $message);

        } elseif ($type == "WAITING_RECONCILE") {
            $title = $this->getRequestTitle($requestForm->type, $requestForm->code);
            $message = "$title has been initiated. Please ensure all required information has been submitted to reconcile this request.";
            $subject = $title . " Waiting Reconciliation";

            foreach ($accountants as $accountant) {

                Notification::create([
                    'contents' => json_encode([
                        'message' => $message,
                        'type' => $requestForm->type,
                        'requestId' => $requestForm->id,
                    ]),
                    'type' => $type,
                    'user_id' => $accountant->id,
                ]);

                //Send email to accountants
                Mail::to($accountant)->send(new RequestFormWaitingReconciliationMail($accountant, $title, $subject));
            }
            //Send a push notification to the app for the accountant
            $this->pushNotification($positionTitle, $subject, $message);
        }
    }

    public function requestFormNotifications($requestForm, $type)
    {
        //Check if the stages have been approved
        if ($requestForm->stagesApprovalStatus) {
            //Notify Management
            $this->notifyManagement($requestForm, $type);

        } else {
            //Notify a user
            $this->notifyUser($requestForm, $type);
        }
    }

    public function getRequestTitle($type, $code): string
    {
        switch ($type) {
            case "PETTY_CASH":
                return "Petty Cash Request [$code]";
            default:
                return "Requisition [$code]";

        }
    }

    private function pushNotification($title, $subject, $message)
    {
        //notification
        /* try{
             $client=new Client();
             $to=str_replace(' ','',$title);
             $notificationRequest=$client->request('POST','https://fcm.googleapis.com/fcm/send',[
                 'headers'=>[
                     'Authorization' => 'key=AAAAFXyrcvQ:APA91bGV3qVuwe94RAhjmH2HcNuGTUqkAqtd9dtoopn1h6Qp55T8m9Plnb9iGbPbZZ7h1uM0i2ryQhEtgk6Tj3XMY7qC1qPEIFFl_zxS798I6_O8HfAfRrJHCitTYdhgRSraN5ZI9Fh9',
                     'Content-Type'   =>  'application/json',
                 ],
                 'json'=>[
                     "priority"=>"high",
                     "content_available"=>true,
                     "to"=>"/topics/$to",
                     "notification"=>[
                         "title"=>$subject,
                         "body"=>$message
                     ]
                 ]
             ]);

             // Develop a use for this
              if ($notificationRequest->getStatusCode()==200){}


         }catch (\GuzzleHttp\Exception\GuzzleException $e){
             //Log information
         }*/
    }


    public function sendWhatsappMessage(Request $request)
    {
        $request->validate([
            "template" => "required",
            "serial" => "required",
        ]);
        $check = $this->processWhatsappMessage($request->template, $request->serial);
        if($check) {
            return Redirect::back()->with('success', 'Notification successfully sent!');
        }else{
            return Redirect::back()->with('error', 'Error! Failed to send the notification.');
        }

    }


    private function pushWhatsappMessage($body)
    {
        $res = false;
        //push notification
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://graph.facebook.com/v20.0/566046569917446/messages', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('WHATSAPP_API_TOKEN'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $body
            ]);

            if ($response->getStatusCode() == 200) {
                Log::info($response->getBody());
                $res = true;
            }


        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            //Log information
            Log::error($e);
        }

        return $res;
    }

    /**
     * @param Request $request
     * @param bool $test
     * @param string $test_phone_number
     * @param bool $check
     * @return bool
     */
    public function processWhatsappMessage(string $template, string $serial): bool
    {
        $check = false;

        switch ($template) {

            case "sales_order":
                $sale = Sale::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $sale->client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Code
                                        "text" => $sale->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $sale->client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Receipt Total
                                        "text" => number_format($sale->total,2)
                                    ],
                                    [
                                        "type" => "text",
                                        //Date
                                        "text" => Carbon::createFromTimestamp($sale->date, 'Africa/Lusaka')->format('F j, Y')
                                    ],
                                ]
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => "{$sale->client->serial}/sales/{$sale->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $check = $this->pushWhatsappMessage($body);
                if ($check) {
                    $sale->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "quotation":
                $quotation = Quotation::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $quotation->client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Code
                                        "text" => $quotation->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $quotation->client->getName()
                                    ]
                                ]
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => "{$quotation->client->serial}/quotations/{$quotation->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $check = $this->pushWhatsappMessage($body);
                if ($check) {
                    $quotation->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "invoice":
                $invoice = Invoice::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $invoice->client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en_us"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Code
                                        "text" => $invoice->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $invoice->client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Sales Order
                                        "text" => "LL{$invoice->sale->formattedCode()}"
                                    ],
                                    [
                                        "type" => "text",
                                        //Sales Total
                                        "text" => number_format($invoice->sale->total,2)
                                    ],
                                    [
                                        "type" => "text",
                                        //Date
                                        "text" => Carbon::createFromTimestamp($invoice->sale->date, 'Africa/Lusaka')->format('F j, Y')
                                    ],
                                ]
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => "{$invoice->client->serial}/invoices/{$invoice->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                $check = $this->pushWhatsappMessage($body);
                if ($check) {
                    $invoice->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "receipt":
                $receipt = Receipt::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $receipt->client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en_us"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Code
                                        "text" => $receipt->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $receipt->client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Products
                                        "text" => $receipt->listOfProducts()
                                    ],
                                    [
                                        "type" => "text",
                                        //Payment Method
                                        "text" => $receipt->paymentMethod->name
                                    ],
                                    [
                                        "type" => "text",
                                        //Receipt Total
                                        "text" => number_format($receipt->amount,2)
                                    ],
                                    [
                                        "type" => "text",
                                        //Date
                                        "text" => Carbon::createFromTimestamp($receipt->date, 'Africa/Lusaka')->format('F j, Y')
                                    ],
                                ]
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => "{$receipt->client->serial}/receipts/{$receipt->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $check = $this->pushWhatsappMessage($body);
                if ($check) {
                    $receipt->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "delivery":
                $delivery = Delivery::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $delivery->client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Code
                                        "text" => $delivery->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $delivery->summary->sale->client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Product Name
                                        "text" => $delivery->summary->fullName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Quantity and units
                                        "text" => "{$delivery->quantity_delivered} {$delivery->summary->units}(s)"
                                    ],
                                    [
                                        "type" => "text",
                                        //Location
                                        "text" => $delivery->summary->sale->location ?? ""
                                    ],
                                ]
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => "{$delivery->summary->sale->client->serial}/deliveries/{$delivery->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                $check = $this->pushWhatsappMessage($body);
                if ($check) {
                    $delivery->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "collection":
                $collection = Collection::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $collection->client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Code
                                        "text" => $collection->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $collection->client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Date
                                        "text" => Carbon::createFromTimestamp($collection->date, 'Africa/Lusaka')->format('F j, Y')
                                    ],
                                    [
                                        "type" => "text",
                                        //Product Name
                                        "text" => $collection->inventory->name
                                    ],
                                    [
                                        "type" => "text",
                                        //Collected By
                                        "text" => $collection->collected_by ?? "Self"
                                    ],
                                    [
                                        "type" => "text",
                                        //Quantity Collected
                                        "text" => $collection->quantity
                                    ],
                                    [
                                        "type" => "text",
                                        //Quantity Remaining
                                        "text" => $collection->balance
                                    ]
                                ]
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => "{$collection->client->serial}/collections/{$collection->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $check = $this->pushWhatsappMessage($body);
                if ($check) {
                    $collection->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            default:

        }
        return $check;
    }
}
