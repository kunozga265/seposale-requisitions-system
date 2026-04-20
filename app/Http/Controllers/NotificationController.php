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
use App\Models\WhatsappMessage;
use App\Models\WhatsappMessageStatus;
use App\Models\Collection;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Position;
use App\Models\Quotation;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\Sale;
use App\Models\CreditVoucher;
use App\Models\SupplierVoucher;
use App\Models\RequestFormItem;
use App\Models\WhatsappMessageTemplate;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Google;

class NotificationController extends Controller
{
    private $credentials = [

        "type" => "service_account",
        "project_id" => "seposale-fdbdc",
        "private_key_id" => "4c6cd87321cf6f967a997ccc371173ac43bc958b",
        "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDdE91LOMrxByCL\ngJQI/wcLk7oPKyXdx66YPXNhtIi2eWYBRJzE/HCm21cC+0538EIOrXGDCXxIXJOy\nKAP8dOLLub3KS1efKXl6AF71Ing9kxj7lOo4mRxrz01/poqHnJpzNh2ayW9CXcm+\nn6C59IVJwsdj6ktTgNeurCg6uH0NLo5yddcurEU9aJ6CGd6sf9N+vM6hI/8+ZGBh\nN6Kd6aj6CbRRtHYt2sXrF0KJ9kpfs4QaYFJKkBgut0+FhG7JK8CZo+KT9bg/H+se\nhY4TYv8JrIkJlWCC0uuywx99IBuLqaq2eCBXIHAZ2nXym2oY4WfP1ChQYbYgcGw6\nZbomi515AgMBAAECggEAV+U17lo+FWYIAm60bH84hdnN94noHCzvtYd5ADeOwz2w\n2IA28/qJr001YvIXWIglO0CqCLcUupBUCFjwfMbcBLNsSal6xMTwjxjmp/90XsbF\nAAFMvgPh0NsyrLXCDfitT3EMhCXiHji7pRZCCKy28YSHUaotGJ35InE/7Vi8HyjO\nEPOTzlKqyjdETxD9zf98eEL86LGfDFwJtosO1/B0SeokNqOpOaqVQTu0x4ElDjec\npzQpQsJ4jVuwOk/+lHR/Urwjp1WQwfUi+54Eh6z3UGzg3JGaIyi+7iy8yq2JIgUq\n4Pz5DysRmUlHurytDUu4k88vMC5Uz6COCuFwxQ62oQKBgQD4bQntmodJJRCL5X2o\n5qJGXrwvivBQV56J0MhziHLccrL/oKhU+DW934toHp04/NqNtTKcpz54hfuawD7H\nLfao+0UHjn1QIenGjl0+8U4AgjYsm47YQ6H+HadZq8p1oKuSGYGtkGt7xoAIww/P\nSYQc1f8L+0GvtLmOTTjbuE2CDwKBgQDj0V9KKMq9t6VZ1vU3RrekUIEAwBIfojQN\n34meglT1nPgXAy+AQ1y65qxpwQrQ8MDN2Wt8COlyOuMv4LOHViqZ8eNkEMbZOV73\nXFmrHiMm9ISTMyFE1wSTwNpFE6ZpUl5BHDeqip2uaE+l+VjArQI3aL1D8//8YUlS\nTJnFw9fP9wKBgQDrVE9tV08EiSntfA3Xa/MY+jEGUHVphjbWkoLwfrdgAP9zjDsp\nkp9GKHckwKtrCov2ZUl2gC97eGBpredKQ04/sRcpG1+2AwozXzURpQChFrg+9XUR\nhK/1yx12onf9iaA9nA/t3LsU22r54d4eHKQbtNQQA4mr6mVEWf6clTBHHQKBgEr/\nl1YrnardNVMhH/MFldlDI2Ti1lSRqn9SstR65YtCFK5GvzGDe5iP7fyWr3/fcimS\nRP89U9TmK/qMB64rILUzW3+KwluqtmfKgD6EGmBtqONotrAZ9QjLOU/6SxNrKVpX\n9r9vCL/s2SOztZMgoZUqJvyi7Afi4ydzzj+73GU7AoGAHHUo/ccpb6pAKpqxT99r\n9QvEtVfXx1Bpx8EutBTjJvXPwJxUxz+4TuwVo69iFO9m9KMu/z3+JCl/wuUyewQ2\nhOPSlYgcE1tFQc2cf+E+2oseUypHqOXMhuMa8Botb9sMZitXxnXmKPqKy465JWPY\nu0OozMt7Wt4KuGO9tRV7xEM=\n-----END PRIVATE KEY-----\n",
        "client_email" => "firebase-adminsdk-fbsvc@seposale-fdbdc.iam.gserviceaccount.com",
        "client_id" => "107446959849143819633",
        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
        "token_uri" => "https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40seposale-fdbdc.iam.gserviceaccount.com",
        "universe_domain" => "googleapis.com"

    ];

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
                // Send a push notification to the app for the manager
                $this->pushNotification("USER-$manager->id", $subject, $message);
            }

            //Send email to managers
            //Mail::to($managers)->send(new UserNewMail($object, $subject));
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
            // $this->pushNotification($positionTitle, $subject, $message);

            //Send email to managers
            //Mail::to($managers)->send(new ProjectNewMail($subject, $object));
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
            //Mail::to($managers)->send(new VehicleNewMail($subject, $object));
        } elseif ($type == "REQUEST_FORM_PENDING") {
            //object is the request
            $name = $object->user->firstName . " " . $object->user->lastName;
            $position = $object->user->position;
            $message = "$name ({$position->title}) has submitted a request. May you please attend to it as soon as possible.";
            $subject = $object->getFullName() . " Pending Approval";

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
                //Mail::to($manager)->send(new RequestFormPendingApprovalMail($manager, $message, $subject));

                //Send a push notification to the app for the manager
                $this->pushNotification("USER-{$manager->id}", $subject, $message);
            }
        } elseif ($type == "REQUEST_FORM_RESUBMITTED") {
            $name = $object->user->firstName . " " . $object->user->lastName;
            $position = $object->user->position;
            $message = "$name ({$position->title}) has edited and resubmitted their request. May you please attend to it as soon as possible.";
            $subject = $object->getFullName() . " Pending Approval - Resubmitted";

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
                //Mail::to($manager)->send(new RequestFormPendingApprovalMail($manager, $message, $subject));

                //Send a push notification to the app for the manager
                $this->pushNotification("USER-{$manager->id}", $subject, $message);
            }
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
            $this->pushNotification("USER-{$object->id}", "Account Verified", $message);

            //Mail::to($object)->send(new UserVerifiedMail());
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
            $this->pushNotification("USER-{$object->id}", "Account Disabled", $message);

            //Mail::to($object)->send(new UserDisabledMail());
        } elseif ($type == "REQUEST_FORM_PENDING") {
            //Find the next person(s) to approve
            $position = Position::find($object->stagesApprovalPosition);
            $employees = $position->users;

            $name = $object->user->firstName . " " . $object->user->lastName;
            $position = $object->user->position;
            $message = "$name ({$position->title}) has submitted a request. May you please attend to it as soon as possible.";
            $subject = $object->getFullName() . " Pending Approval";

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
                //Mail::to($employee)->send(new RequestFormPendingApprovalMail($employee, $message, $subject));

                //Send a push notification to the app for the user
                $this->pushNotification("USER-{$employee->id}", $subject, $message);
            }
        } elseif ($type == "REQUEST_FORM_RESUBMITTED") {
            //Find the next person(s) to approve
            $position = Position::find($object->stagesApprovalPosition);
            $employees = $position->users;

            $name = $object->user->firstName . " " . $object->user->lastName;
            $position = $object->user->position;
            $message = "$name ({$position->title}) has edited and resubmitted their request. May you please attend to it as soon as possible.";
            $subject = $object->getFullName() . " Pending Approval - Resubmitted";

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
                //Mail::to($employee)->send(new RequestFormPendingApprovalMail($employee, $message, $subject));
            }

            //Send a push notification to the app for the user
            $this->pushNotification("POSITION-{$position->id}", $subject, $message);
        } elseif ($type == "INITIATED") {
            $message = "The request has been initiated by the Accounts Department.";
            $subject = $object->getFullName() . " Initiated";

            Notification::create([
                'contents' => json_encode([
                    'message' => $message,
                    'type' => $object->type,
                    'requestId' => $object->id,
                ]),
                'type' => $type,
                'user_id' => $object->user->id,
            ]);

            //Send a push notification to the app for the user
            $this->pushNotification("USER-{$object->user->id}", $subject, $message);

            $name = $object->firstName . " " . $object->lastName;
            //Mail::to($object->user)->send(new RequestFormInitiatedMail($name, $subject));
        } elseif ($type == "RECONCILED") {
            $message = "The request has been reconciled by the Accounts Department.";
            $subject = $object->getFullName() . " Reconciled";

            Notification::create([
                'contents' => json_encode([
                    'message' => $message,
                    'type' => $object->type,
                    'requestId' => $object->id,
                ]),
                'type' => $type,
                'user_id' => $object->user->id,
            ]);

            //Send a push notification to the app for the user
            $this->pushNotification("USER-{$object->user->id}", $subject, $message);

            $name = $object->firstName . " " . $object->lastName;
            //Mail::to($object->user)->send(new RequestFormReconciledMail($name, $subject));
        }
    }

    public function notifyApproval($requestForm, $approvedBy)
    {
        $approvedByName = $approvedBy->firstName . " " . $approvedBy->lastName;
        $positionTitle = $approvedBy->position->title;
        $message = "$approvedByName ($positionTitle) has approved your request. Your request has gone to the next stage.";
        $subject = $requestForm->getFullName() . " Approved";
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
        $this->pushNotification("USER-{$requestForm->user->id}", $subject, $message);

        //Send email
        //Mail::to($requestForm->user)->send(new RequestFormApprovedMail($requestForm, $approvedBy, $subject));
    }

    public function notifyDenial($requestForm, $deniedBy)
    {
        $deniedByName = $deniedBy->firstName . " " . $deniedBy->lastName;
        $positionTitle = $deniedBy->position->title;
        $message = "The request has been denied by $deniedByName ($positionTitle). View the request to see the reason why.";
        $subject = $requestForm->getFullName() . " Denied";

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
        $this->pushNotification("USER-{$requestForm->user->id}", $subject, $message);

        //Send email
        //Mail::to($requestForm->user)->send(new RequestFormDeniedMail($requestForm->user, $message, $subject));
    }

    public function notifyFinance($requestForm, $type)
    {
        $role = Role::where('name', 'accountant')->first();
        $accountants = $role->users;
        $positionId = 0;
        $positionTitle = $requestForm->user->position->title;

        if ($type == "WAITING_INITIATE") {
            $name = $requestForm->user->firstName . " " . $requestForm->user->lastName;
            $message = "$name ($positionTitle) has submitted a request and it has been approved. May you please attend to it as soon as possible.";
            $subject = $requestForm->getFullName() . " Waiting Initiation";

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
                //Mail::to($accountant)->send(new RequestFormWaitingInitiationMail($accountant, $message, $subject));

                //Send a push notification to the app for the accountant
                $this->pushNotification("USER-{$accountant->id}", $subject, $message);
            }
        } elseif ($type == "WAITING_RECONCILE") {
            $title = $requestForm->getFullName();
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
                //Mail::to($accountant)->send(new RequestFormWaitingReconciliationMail($accountant, $title, $subject));

                //Send a push notification to the app for the accountant
                $this->pushNotification("USER-{$accountant->id}", $subject, $message);
            }
        }
    }

    public function notifyAccounts($object, $type, $amount = null)
    {
        $role = Role::where('name', 'accountant')->first();
        $accountants = $role->users;


        if ($type == "proof_of_payment") {
            $sale = $object;


            // $name = $requestForm->user->firstName . " " . $requestForm->user->lastName;
            $message = "A payment of MK{$amount} has been received from {$sale->client->name}. \nPlease generate a receipt for this order.";
            $subject = "Proof of Payment";

            foreach ($accountants as $user) {
                error_log($user->id);

                //Send email to accountants
                //Mail::to($accountant)->send(new RequestFormWaitingInitiationMail($accountant, $message, $subject));

                //Send a push notification to the app for the accountant
                $this->pushNotification("USER-{$user->id}", $subject, $message);
            }

            // $this->processWhatsappMessage("proof_of_payment", $sale->serial, phone_number: "265992478402", amount: $amount);
        } else if ($type == "payables") {
            $sale = $object;

            $list = "";

            $payables = $sale->payables()->where('paid', 0)->get();
            for ($i = 0; $i < $payables->count(); $i++) {
                $total = number_format($payables[$i]->total, 2);
                if ($i < ($payables->count() - 1)) {
                    $list .= $payables[$i]->getName() . " (MK" . $total . ") " . ", ";
                } else {
                    $list .= $payables[$i]->getName() .  " (MK" . $total . ") ";
                }
            }

            // $name = $requestForm->user->firstName . " " . $requestForm->user->lastName;
            $subject = "Payables under Sales Order #" . $sale->formattedCode();
            $message = "The following creditors need to be paid: $list ";

            $managers = Role::where('name', 'management')->first()->users;
            $all = $managers->merge($accountants);

            foreach ($all as $user) {
                error_log($user->id);

                //Send email to accountants
                //Mail::to($accountant)->send(new RequestFormWaitingInitiationMail($accountant, $message, $subject));

                //Send a push notification to the app for the accountant
                $this->pushNotification("USER-{$user->id}", $subject, $message);
            }

            // $this->processWhatsappMessage("proof_of_payment", $sale->serial, phone_number: "265992478402", amount: $amount);
        }
    }

    public function notifySales($object, $type)
    {
        $role = Role::where('name', 'sales')->first();
        $users = $role->users;


        if ($type == "waiver") {
            $summary = $object;


            // $name = $requestForm->user->firstName . " " . $requestForm->user->lastName;
            $message = "{$summary->name} under Sales Order #{$summary->sale->formattedCode()} has been waivered. \nPlease proceed to process the sale.";
            $subject = "Waiver Alert";

            foreach ($users as $user) {
                error_log($user->id);

                //Send email to accountants
                //Mail::to($accountant)->send(new RequestFormWaitingInitiationMail($accountant, $message, $subject));

                //Send a push notification to the app for the accountant
                $this->pushNotification("USER-{$user->id}", $subject, $message);
            }

            // $this->processWhatsappMessage("proof_of_payment", $sale->serial, phone_number: "265992478402", amount: $amount);
        }
    }

    public function notifyCreditors($requestForm)
    {
        $role = Role::where('name', 'accountant')->first();
        $accountants = $role->users;

        $credit_vouchers = $requestForm->creditVouchers;

        foreach ($credit_vouchers as $credit_voucher) {

            $this->processWhatsappMessage("credit_voucher", $credit_voucher->serial);

            $message = "{$credit_voucher->contact->name} has been notified of the credit $credit_voucher->type.";
            $subject = "Credit Voucher Notice";

            foreach ($accountants as $accountant) {
                $this->pushNotification("USER-{$accountant->id}", $subject, $message);
            }
        }
    }

    public function notifySupplier($requestForm)
    {
        $role = Role::where('name', 'accountant')->first();
        $accountants = $role->users;

        $supplier_vouchers = $requestForm->supplierVouchers;

        foreach ($supplier_vouchers as $supplier_voucher) {

            $this->processWhatsappMessage("supplier_voucher", $supplier_voucher->serial);

            $message = "{$supplier_voucher->contact->name} has been notified of the credit $supplier_voucher->type.";
            $subject = "Supplier Voucher Notice";

            foreach ($accountants as $accountant) {
                $this->pushNotification("USER-{$accountant->id}", $subject, $message);
            }
        }
    }

    public function notifyDeliveryTeam($requestForm)
    {
        $role = Role::where('name', 'delivery')->first();
        $delivery_team = $role->users;

        foreach ($requestForm->items as $item) {

            if ($item->meta != null) {
                $meta = json_decode($item->meta, true);
            } else {
                $meta = ["notify" => "TEAM"];
            }


            if ($meta['notify'] == 'PROVIDER') {
                $this->processWhatsappMessage("delivery_order", $item->id, $item->contact->name, 0, $item->contact->phone_number);
            } else {
                $message = "We have an order for {$item->product_name} to be delivered at {$requestForm->delivery->location}. Please confirm delivery with the operations team";
                $subject = "Delivery Order #" . $requestForm->delivery->formattedCode();

                foreach ($delivery_team as $user) {
                    $this->pushNotification("USER-{$user->id}", $subject, $message);
                    // $this->processWhatsappMessage("delivery_order", $item->serial, $user->fullName(), 0, $user->phone_number);
                }
            }
        }
    }
    public function notifyWhatsappMessage(WhatsappMessage $whatsapp_message, $message, $status_update = false)
    {

        // $subject = "Forwaded Response - " . $whatsapp_message->name;
        $subject = "Forwarded Response";

        if ($status_update) {
            if ($whatsapp_message->user != null) {
                Log::info($subject);
                Log::info($message);
                $this->pushNotification("USER-{$whatsapp_message->user->id}", $subject, $message);
            }
        } else {
            $role = Role::where('name', 'sales')->first();
            $sales_reps = $role->users;

            foreach ($sales_reps as $rep) {
                Log::info($subject);
                Log::info($message);
                $this->pushNotification("USER-{$rep->id}", $subject, $message);
            }
        }
    }

    public function requestFormNotifications($requestForm, $type)
    {
        // Check if the stages have been approved
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

    private function pushNotification($to, $subject, $message)
    {
        // error_log($to);
        // $to = "POSITION-2";

        if (env('WHATSAPP_DEBUG')) {
            $to = "USER-1";
        }



        //notification
        // create the Google client
        $client = new Google\Client();
        $client->setAuthConfig($this->credentials);
        $client->addScope(Google\Service\FirebaseCloudMessaging::FIREBASE_MESSAGING);
        $httpClient = $client->authorize();
        $token1 = $client->getRefreshToken();
        $token = $client->getAccessToken();
        //        $token = $client->();




        $res = $httpClient->request('post', 'https://fcm.googleapis.com/v1/projects/seposale-fdbdc/messages:send', [
            //            'headers' => [
            //                'Authorization' => 'Bearer AAAAQdj1ZOU:APA91bHbQ6JbhcEoHTyQthEp1j8QjlDUM7ftsFmcMRUvgKuZJBy5-IQQ_6eZZAfJ5fUM1qP60dATN-DiOzM3LcUnjcjR7-vGzE02iC7jCEuJU3GC_qrLXcxyY6P7zy57joaqbytyWj59',
            //                'Content-Type' => 'application/json',
            //            ],
            'json' => [
                "message" => [
                    "topic" => $to,
                    "notification" => [
                        "title" => $subject,
                        "body" => $message
                    ],
                    // "data" => [
                    //     "type" => "sermon",
                    //     "slug" => $slug,
                    // ]
                ]
            ]
        ]);
    }


    public function sendWhatsappMessage(Request $request)
    {
        $request->validate([
            "template" => "required",
            "serial" => "required",
        ]);
        $check = $this->processWhatsappMessage($request->template, $request->serial);
        if ($check) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Notification successfully sent!"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('success', 'Notification successfully sent!');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Error! Failed to send the notification."], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Error! Failed to send the notification.');
            }
        }
    }


    private function pushWhatsappMessage($body, $data)
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

                $res_body = json_decode($response->getBody(), true);

                $client = \App\Models\Client::where('phone_number', $res_body['contacts'][0]['input'])
                    ->orWhere('phone_number_other', $res_body['contacts'][0]['input'])
                    ->first();

                $whatsapp_message = WhatsappMessage::create([
                    //ESSENTIALS
                    "type" => 0, //system
                    "phone_number" => $res_body['contacts'][0]['input'],
                    "message_type" => $body['template']['name'],
                    "wamid" => $res_body['messages'][0]['id'],
                    "user_id" => Auth::id(),

                    //OPTIONAL
                    'client_id' => $client?->id,
                    'sale_id' => isset($data['sale_id']) ? $data['sale_id'] : null,
                    'quotation_id' => isset($data['quotation_id']) ? $data['quotation_id'] : null,
                    'invoice_id' => isset($data['invoice_id']) ? $data['invoice_id'] : null,
                    'receipt_id' => isset($data['receipt_id']) ? $data['receipt_id'] : null,
                    'delivery_id' => isset($data['delivery_id']) ? $data['delivery_id'] : null,
                    'collection_id' => isset($data['collection_id']) ? $data['collection_id'] : null,
                    'credit_voucher_id' => isset($data['credit_voucher_id']) ? $data['credit_voucher_id'] : null,
                    'supplier_voucher_id' => isset($data['supplier_voucher_id']) ? $data['supplier_voucher_id'] : null,
                    'request_form_item_id' => isset($data['request_form_item_id']) ? $data['request_form_item_id'] : null,
                ]);


                WhatsappMessageStatus::create([
                    "status" => $res_body['messages'][0]['message_status'],
                    "payload" => $response->getBody(),
                    "wamid" => $res_body['messages'][0]['id'],
                    "whatsapp_message_id" => $whatsapp_message->id,
                ]);


                // dd($response);
                $res = true;
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            //Log information
            Log::error($e);
            $res = false;
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
    public function processWhatsappMessage(string $template, string $serial, string $notify = "", $balance = 0, $phone_number = null, $amount = 0): bool
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
                                        "text" => number_format($sale->total, 2)
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
                $data['sale_id'] = $sale->id;
                $check = $this->pushWhatsappMessage($body, $data);
                if ($check) {
                    $sale->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "proof_of_payment":
                $sale = Sale::where('serial', $serial)->first();
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            // [
                            //     "type" => "header",
                            //     "parameters" => [
                            //         [
                            //             "type" => "text",
                            //             //Code
                            //             "text" => $sale->formattedCode()
                            //         ]
                            //     ]
                            // ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Total
                                        "text" => number_format($amount, 2)
                                    ],
                                    [
                                        "type" => "text",
                                        //Client Name
                                        "text" => $sale->client->getName()
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
                                        "text" => "{$sale->id}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $data['sale_id'] = $sale->id;
                $check = $this->pushWhatsappMessage($body, $data);
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
                $data['quotation_id'] = $quotation->id;
                $check = $this->pushWhatsappMessage($body, $data);
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
                                        "text" => number_format($invoice->sale->total, 2)
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

                $data['invoice_id'] = $invoice->id;
                $check = $this->pushWhatsappMessage($body, $data);
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
                                        "text" => number_format($receipt->amount, 2)
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
                $data['receipt_id'] = $receipt->id;
                $check = $this->pushWhatsappMessage($body, $data);
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
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $delivery->summary->sale->client->phone_number,
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

                $data['delivery_id'] = $delivery->id;
                $check = $this->pushWhatsappMessage($body, $data);
                if ($check) {
                    $delivery->update([
                        "whatsapp" => true
                    ]);
                }
                break;
            case "delivery_order":
                $item = RequestFormItem::find($serial);
                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $phone_number,
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
                                        "text" => $item->requestForm->delivery->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Name
                                        "text" => $notify
                                    ],
                                    [
                                        "type" => "text",
                                        //Product Name
                                        "text" => $item->requestForm->delivery->summary->fullName()
                                    ],
                                    [
                                        "type" => "text",
                                        //Quantity
                                        "text" => $item->requestForm->delivery->summary->quantityWithUnits
                                    ],
                                    [
                                        "type" => "text",
                                        //Location
                                        "text" => $item->requestForm->delivery->location
                                    ],
                                    [
                                        "type" => "text",
                                        //Due Date
                                        "text" => Carbon::createFromTimestamp($item->requestForm->delivery->due_date, 'Africa/Lusaka')->format('F j, Y')
                                    ],
                                ]
                            ],

                        ]
                    ]
                ];

                $data['request_form_item_id'] = $item->id;
                $check = $this->pushWhatsappMessage($body, $data);

                break;

            case "collection":
                $collection = Collection::where('serial', $serial)->withTrashed()->first();

                if ($notify == "team") {
                    $phone_number = env('WHATSAPP_SALES_NUMBER');
                } else {
                    $phone_number = $collection->client->phone_number;
                }

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $phone_number,
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
                $data['collection_id'] = $collection->id;
                $check = $this->pushWhatsappMessage($body, $data);
                if ($check) {
                    $collection->update([
                        "whatsapp" => true
                    ]);
                }
                break;

            case "collection_reversal":
                $collection = Collection::where('serial', $serial)->withTrashed()->first();

                if ($notify == "team") {
                    $phone_number = env('WHATSAPP_SALES_NUMBER');
                } else {
                    $phone_number = $collection->client->phone_number;
                }

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $phone_number,
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
                                        //Product Name
                                        "text" => $collection->inventory->name
                                    ],
                                    [
                                        "type" => "text",
                                        //Quantity Remaining
                                        "text" => $balance
                                    ],
                                ]
                            ],
                        ]
                    ]
                ];
                $data['collection_id'] = $collection->id;
                $check = $this->pushWhatsappMessage($body, $data);
                if ($check) {
                    $collection->update([
                        "whatsapp" => true
                    ]);
                }
                break;
            case "credit_voucher":
                $credit_voucher = CreditVoucher::where('serial', $serial)->first();

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $credit_voucher->phone_number,
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
                                        "text" => $credit_voucher->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Transporter/Supplier Name
                                        "text" => $credit_voucher->contact->name
                                    ],
                                    [
                                        "type" => "text",
                                        //delivery or supply
                                        "text" => $credit_voucher->transporter != null ? "delivery" : "supply"
                                    ],
                                    [
                                        "type" => "text",
                                        //product name
                                        "text" => $credit_voucher->requestFormItem->product_name
                                    ],
                                    [
                                        "type" => "text",
                                        //location
                                        "text" => ucwords($credit_voucher->requestForm->delivery?->summary->sale->location) ?? "Unspecified"
                                    ],
                                    [
                                        "type" => "text",
                                        //Item Total
                                        "text" => number_format($credit_voucher->amount, 2)
                                    ],
                                    [
                                        "type" => "text",
                                        //requisition code
                                        "text" => $credit_voucher->requestForm->formattedCode()
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
                                        "text" => "{$credit_voucher->contact->serial}/credit-vouchers/{$credit_voucher->serial}"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $data['credit_voucher_id'] = $credit_voucher->id;
                $check = $this->pushWhatsappMessage($body, $data);

                break;
            case "supplier_voucher":
                $supplier_voucher = SupplierVoucher::where('serial', $serial)->first();

                error_log("{$supplier_voucher->contact->serial}/supplier-vouchers/{$supplier_voucher->serial}");

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $supplier_voucher->phone_number,
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
                                        "text" => $supplier_voucher->formattedCode()
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Transporter/Supplier Name
                                        "text" => $supplier_voucher->contact->name
                                    ],
                                    [
                                        "type" => "text",
                                        //site name
                                        "text" => $supplier_voucher->site?->name ?? "Njewa"
                                    ],
                                    [
                                        "type" => "text",
                                        //item name
                                        "text" => $supplier_voucher->details
                                    ],
                                    [
                                        "type" => "text",
                                        //Item Total
                                        "text" => number_format($supplier_voucher->amount, 2)
                                    ],
                                    [
                                        "type" => "text",
                                        //requisition code
                                        "text" => $supplier_voucher->requestForm->formattedCode()
                                    ],
                                ]
                            ],
                            // [
                            //     "type" => "button",
                            //     "sub_type" => "url",
                            //     "index" => "0",
                            //     "parameters" => [
                            //         [
                            //             "type" => "text",
                            //             "text" => "{$supplier_voucher->contact->serial}/supplier-vouchers/{$supplier_voucher->serial}"
                            //         ]
                            //     ]
                            // ]
                        ]
                    ]
                ];
                $data['supplier_voucher_id'] = $supplier_voucher->id;
                $check = $this->pushWhatsappMessage($body, $data);

                break;
            case "pricelist":
                $client = \App\Models\Client::where('serial', $serial)->first();

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Transporter/Supplier Name
                                        "text" => $client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //site name
                                        "text" => Carbon::now()->format('F j, Y')
                                    ],
                                    [
                                        "type" => "text",
                                        //item name
                                        "text" => $notify
                                    ],
                                ]
                            ],
                        ]
                    ]
                ];
                $data = [];
                $check = $this->pushWhatsappMessage($body, $data);

                break;
            case "pricelist_referred":
                $client = \App\Models\Client::where('serial', $serial)->first();

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $client->phone_number,
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
                                        "type" => "document",
                                        "document" => [
                                            "link" => "https://sis.seposale.com/files/seposale_pricelist.pdf",
                                            "filename" => "Seposale Pricelist " . date("Y-m-d")
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Transporter/Supplier Name
                                        "text" => $client->getName()
                                    ],
                                    [
                                        "type" => "text",
                                        //site name
                                        "text" => Carbon::now()->format('F j, Y')
                                    ],
                                    [
                                        "type" => "text",
                                        //item name
                                        "text" => $notify
                                    ],
                                ]
                            ],
                        ]
                    ]
                ];
                $data = [];
                $check = $this->pushWhatsappMessage($body, $data);

                break;
            case "otp":
                $client = \App\Models\Client::where('serial', $serial)->first();

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $client->phone_number ?? $client->phone_number_other,
                    "type" => "template",
                    "template" => [
                        "name" => $template,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //otp
                                        "text" => $notify
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
                                        "text" => $notify
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                $data['client_id'] = $client->id;
                $this->pushWhatsappMessage($body, $data);

                break;

            default:
        }
        return $check;
    }

    /**
     * @param WhatsappMessageTemplate $template
     * @param string $serial
     * @param string $file
     * @param string $message
     * @return bool
     */
    public function processWhatsappTemplateMessage(WhatsappMessageTemplate $template, string $serial, string $message = "", $file = null): bool
    {
        $check = false;

        switch ($template->code) {

            case "introductory_01":
            case "introductory_02":
                $client = \App\Models\Client::where('serial', $serial)->first();

                $body = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => env('WHATSAPP_DEBUG') ? env('WHATSAPP_TEST_NUMBER') : $client->phone_number,
                    "type" => "template",
                    "template" => [
                        "name" => $template->code,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "document",
                                        "document" => [
                                            "link" => env('APP_URL') . $file,
                                            "filename" => $template->name . date(" Y-m-d")
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        //Transporter/Supplier Name
                                        "text" => $client->getName()
                                    ],
                                    // [
                                    //     "type" => "text",
                                    //     //site name
                                    //     "text" => Carbon::now()->format('F j, Y')
                                    // ],
                                    // [
                                    //     "type" => "text",
                                    //     //item name
                                    //     "text" => $message
                                    // ],
                                ]
                            ],
                        ]
                    ]
                ];

                // dd($body);

                $data = [];
                $check = $this->pushWhatsappMessage($body, $data);
                break;

            default:
        }
        return $check;
    }
}
