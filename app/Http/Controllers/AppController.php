<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Collection;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Receipt;
use App\Models\RequestForm;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SiteSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Intervention\Image\ImageManager;

//use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Driver;

class AppController extends Controller
{
    public $paginate = 20;
    public $OTHER_PRODUCT_ID = 7;
    public $SERVICES_PRODUCT_ID = 8;

    public function getAuthUser(Request $request)
    {
        if ($this->isApi($request)) {
            //API User
            $requestToken = substr($request->server('HTTP_AUTHORIZATION'), 7);

            if ($requestToken) {
                $token = PersonalAccessToken::findToken($requestToken);
                return $token->tokenable;
            } else
                return null;
        } else {
            return User::find(Auth::id());
        }

    }

    public function uploadFile(Request $request)
    {

        $request->validate([
            'file' => 'required',
            'type' => 'required',
        ]);

        $file = $request->file;
        $type = $request->type;

        //upload new picture and update database
        $explodedFile = explode(',', $file);
        //$decodedFile=base64_decode($explodedFile[1]);


        //develop name
        $ext = $this->getExtension($explodedFile);

        switch ($type) {
            case 'VEHICLE':
                $filename = "files/vehicles/" . $type . "-" . uniqid() . "." . $ext;
                break;
            case 'QUOTE':
                $filename = "files/quotes/" . $type . "-" . uniqid() . "." . $ext;
                break;
            case 'RECEIPT':
                $filename = "files/receipts/" . $type . "-" . uniqid() . "." . $ext;
                break;
            case 'DELIVERY_NOTE':
                $filename = "files/deliveries/" . $type . "-" . uniqid() . "." . $ext;
                break;
            default:
                $filename = "files/other/" . $type . "-" . uniqid() . "." . $ext;
        }

        if ($type == 'VEHICLE') {
            if ($ext == 'jpg' || $ext == 'png') {
                try {
                    Storage::disk('public_uploads')->put(
                        $filename, file_get_contents($file)
                    );
                } catch (\RuntimeException $e) {
                    return response()->json([
                        'message' => "Failed to upload",
                    ], 501);
                }
            } else {
                return response()->json([
                    'message' => "Invalid extension",
                ], 415);
            }
        } else {
            if ($ext == 'jpg' || $ext == 'png' || $ext == 'pdf') {
                try {
//                    // create new manager instance with desired driver
//
//                    $manager = ImageManager::withDriver(new Driver());
//                    $image = $manager
//                        ->read(file_get_contents($file))
//                        ->scale(height: 200);;
////                    $scaledDown = $image->scaleDown(height: 200);
////                    $encoded = $scaledDown->encodeByExtension();
//                    $image->save($filename);

                    Storage::disk('public_uploads')->put(
                        $filename, file_get_contents($file)
                    );
                } catch (\RuntimeException $e) {
                    return response()->json([
                        'message' => "Failed to upload $e",
                    ], 501);
                }
            } else {
                return response()->json([
                    'message' => "Invalid extension",
                ], 415);
            }
        }

        return response()->json([
            'file' => $filename,
            'ext' => $ext
        ]);
    }

    public function removeFile(Request $request)
    {
        if (file_exists($request->file)) {
            Storage::disk("public_uploads")->delete($request->file);
            return response()->json([
                'message' => "Successfully deleted",
            ]);
        } else {
            return response()->json([
                'message' => "File does not exist",
            ], 404);
        }
    }

    private function getExtension($explodedImage)
    {
        $imageExtensionDecode = explode('/', $explodedImage[0]);
        $imageExtension = explode(';', $imageExtensionDecode[1]);
        $lowercaseExt = strtolower($imageExtension[0]);
        if ($lowercaseExt == 'jpeg')
            return 'jpg';
        else
            return $lowercaseExt;
    }

    public function generateUniqueCode($type)
    {
        $code = '';

        if ($type === "SALE") {
            //If the code exists generate another one
            do {
                $code = $this->getNewCode();
            } while (Sale::where('serial', $code)->exists());
        }elseif ($type=="SITESALE"){
            do {
                $code = $this->getNewCode();
            } while (SiteSale::where('serial', $code)->exists());
        }elseif ($type=="REQUESTFORM"){
            do {
                $code = $this->getNewCode();
            } while (RequestForm::where('code', $code)->exists());
        }elseif ($type=="INVOICE"){
            do {
                $code = $this->getNewCode();
            } while (Invoice::where('serial', $code)->exists());
        }elseif ($type=="QUOTATION"){
            do {
                $code = $this->getNewCode();
            } while (Quotation::where('serial', $code)->exists());
        }elseif ($type=="RECEIPT"){
            do {
                $code = $this->getNewCode();
            } while (Receipt::where('serial', $code)->exists());
        }elseif ($type=="DELIVERY"){
            do {
                $code = $this->getNewCode();
            } while (Delivery::where('serial', $code)->exists());
        }elseif ($type=="COLLECTION"){
            do {
                $code = $this->getNewCode();
            } while (Collection::where('serial', $code)->exists());
        }elseif ($type=="CLIENT"){
            do {
                $code = $this->getNewCode();
            } while (Client::where('serial', $code)->exists());
        }else{
            return null;
        }

        //return unique code
        return $code;
    }

    private function getNewCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 20;

        //initialise code to an empty string
        $code = '';

        //generate a code according to the length
        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code .= $character;
        }

        return $code;
    }

    public function getZeroedNumber($number, $revision = 0)
    {
        $zeroed_number = '';
        switch ($number) {
            case $number < 10:
                $zeroed_number = "0000$number";
                break;
            case $number < 100:
                $zeroed_number = "000$number";
                break;
            case $number < 1000:
                $zeroed_number = "00$number";
                break;
            case $number < 10000:
                $zeroed_number = "0$number";
                break;
            default:
                $zeroed_number = $number;
        }

//        if($revision > 0){
//            $zeroed_number = $zeroed_number."REV$revision";
//        }

        return $zeroed_number;
    }

    public function isApi(Request $request)
    {
        //get cookie object
        $CSRF_TOKEN = $request->cookie();
        return count($CSRF_TOKEN) == 0;
    }

    public function getRoleUsers($name)
    {
        $role = Role::where('name', $name)->first();
        return $role->users;
    }

    public function generateCompound($models)
    {
        $sorted = [];
        if (!$models->isEmpty()) {
            $current_month = date('F', $models[0]->date);
            $current_year = date('Y', $models[0]->date);

            $item = 0;
            $index = 0;

            foreach ($models as $model) {
                if ($item == 0) {
                    $sorted[0] = [
                        'month' => $current_month,
                        'year' => $current_year,
                        'data' => [$model]
                    ];
                } else {
                    $month = date('F', $models[$item]->date);
                    $year = date('Y', $models[$item]->date);

                    if ($current_month === $month && $current_year === $year) {
                        $sorted[$index]['data'][] = $model;
                    } else {
                        $index += 1;
                        $current_month = date('F', $models[$item]->date);
                        $current_year = date('Y', $models[$item]->date);

                        $sorted[$index] = [
                            'month' => $current_month,
                            'year' => $current_year,
                            'data' => [$model]
                        ];
                    }
                }

                dump($sorted);
                $item += 1;
            }
        }
        return $sorted;
    }
}
