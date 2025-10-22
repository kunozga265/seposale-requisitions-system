<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequestFormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '1.0.0'], function () {

    //Unauthenticated Routes
    Route::post("/users/login", [UserController::class, 'login']);
    Route::post("/users/register", [UserController::class, 'register']);
    Route::get("/projects/create", [
        ProjectController::class,
        'create'
    ]);
    Route::post('reports/generate', [
        "uses" => "App\Http\Controllers\ReportController@generate",
        'roles' => ['accountant', 'management', 'administrator']
    ]);


    Route::post("/upload", [
        "uses" => "App\Http\Controllers\AppController@uploadFile",
        'roles' => ['employee', 'administrator']
    ]);

    Route::post("/upload/delete", [
        "uses" => "App\Http\Controllers\AppController@removeFile",
        'roles' => ['employee', 'administrator']
    ]);

    //Authenticated Routes
    Route::group(["middleware" => ["auth:sanctum", "roles"]], function () {

        Route::get("/dashboard", [
            "uses" => "App\Http\Controllers\RequestFormController@dashboard",
        ]);

        Route::group(['prefix' => 'positions'], function () {
            Route::get("/", [PositionController::class, 'index']);
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get("/", [UserController::class, 'index']);

            Route::get("/view/{id}", [
                UserController::class,
                'show'
            ]);

            Route::post("/verify/{id}", [
                "uses" => "App\Http\Controllers\UserController@verify",
                'roles' => ['management']
            ]);

            Route::post("/disable/{id}", [
                "uses" => "App\Http\Controllers\UserController@disable",
                'roles' => ['management']
            ]);

            Route::post("/disable/{id}", [
                "uses" => "App\Http\Controllers\UserController@discard",
                'roles' => ['management']
            ]);
        });



        Route::get("/dashboard", [
            "uses" => "App\Http\Controllers\API\AppController@dashboard",
            'roles' => ['employee', 'management']
        ]);

        Route::get("/initialise", [
            "uses" => "App\Http\Controllers\API\AppController@initialise",

        ]);


        Route::group(['prefix' => 'request-forms'], function () {

            Route::get("/", [
                "uses" => "App\Http\Controllers\API\RequestFormController@index",
                'roles' => ['employee', 'management']
            ]);

            Route::get("/approved", [
                "uses" => "App\Http\Controllers\RequestFormController@approved",
                'roles' => ['employee', 'management']
            ]);

            Route::get("/finance", [
                "uses" => "App\Http\Controllers\RequestFormController@finance",
                'roles' => ['employee', 'management']
            ]);

            Route::get("/pending", [
                "uses" => "App\Http\Controllers\RequestFormController@dashboard",
                'roles' => ['employee', 'management']
            ]);

            Route::get("/view/{id}", [
                "uses" => "App\Http\Controllers\RequestFormController@show",
                'roles' => ['employee', 'management']
            ]);

            Route::post("/", [
                "uses" => "App\Http\Controllers\RequestFormController@store",
                'roles' => ['employee', 'management']
            ]);

            Route::post("/approve/{id}", [
                "uses" => "App\Http\Controllers\RequestFormController@approve",
                'roles' => ['employee', 'management']
            ]);

            Route::post("/deny/{id}", [
                "uses" => "App\Http\Controllers\RequestFormController@deny",
                'roles' => ['employee', 'management']
            ]);

            Route::post("/edit/{id}", [
                "uses" => "App\Http\Controllers\RequestFormController@update",
                'roles' => ['employee', 'management']
            ]);

            Route::post("/initiate/{id}", [
                "uses" => "App\Http\Controllers\RequestFormController@initiate",
                'roles' => ['accountant']
            ]);

            Route::post("/reconcile/{id}", [
                "uses" => "App\Http\Controllers\RequestFormController@reconcile",
                'roles' => ['accountant']
            ]);

            Route::delete('/delete/{id}', [
                "uses"  => "App\Http\Controllers\RequestFormController@destroy",
                'roles' => ['employee', 'management']
            ]);

            Route::delete('/discard/{id}', [
                "uses"  => "App\Http\Controllers\RequestFormController@discard",
                'roles' => ['employee', 'management']
            ]);

            Route::post('/add-remarks/{id}', [
                "uses"  => "App\Http\Controllers\RequestFormController@appendRemarks",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/print/{id}', [
                "uses"  => "App\Http\Controllers\RequestFormController@print",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/find/{code}', [
                "uses"  => "App\Http\Controllers\RequestFormController@findRequestForm",
                'roles' => ['employee', 'management']
            ]);

            Route::post('/from-delivery/{id}', [
                "uses" => "App\Http\Controllers\API\RequestFormController@storeFromDelivery",
                'roles' => ['employee', 'management']
            ]);
        });



        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\NotificationController@index",
                'roles' => ['employee', 'management']
            ]);

            Route::post('/whatsapp', [
                "uses" => "App\Http\Controllers\NotificationController@sendWhatsappMessage",
                'roles' => ['employee', 'management']
            ]);
        });

        Route::group(['prefix' => 'quotations'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\QuotationController@index",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/store', [
                "uses" => "App\Http\Controllers\QuotationController@store",
                'roles' => ['employee', 'management']
            ]);
            Route::delete('/delete/{id}', [
                "uses" => "App\Http\Controllers\QuotationController@destroy",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/print/{id}', [
                "uses"  => "App\Http\Controllers\QuotationController@print",
                'roles' => ['employee', 'management']
            ]);

            Route::post('/generate-sale/{id}', [
                "uses"  => "App\Http\Controllers\SaleController@storeFromQuotation",
                'roles' => ['employee', 'management']
            ]);
        });

        Route::group(['prefix' => 'invoices'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\InvoiceController@index",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/print/{id}', [
                "uses"  => "App\Http\Controllers\InvoiceController@print",
                'roles' => ['employee', 'management']
            ])->name('invoices.print');
        });

        Route::group(['prefix' => 'receipts'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\API\ReceiptController@index",
                'roles' => ['employee', 'management']
            ]);
            Route::get('/create', [
                "uses" => "App\Http\Controllers\API\ReceiptController@create",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/sale/{id}', [
                "uses" => "App\Http\Controllers\API\ReceiptController@store",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/print/{id}', [
                "uses"  => "App\Http\Controllers\ReceiptController@print",
                'roles' => ['employee', 'management']
            ])->name('receipts.print');
        });

        Route::group(['prefix' => 'accounts'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\API\AccountingController@index",
                'roles' => ['employee', 'management']
            ]);
        });

        Route::group(['prefix' => 'sales'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\API\SaleController@index",
                'roles' => ['employee', 'management']
            ]);
            Route::get('/view/{id}', [
                "uses" => "App\Http\Controllers\API\SaleController@show",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/store', [
                "uses" => "App\Http\Controllers\SaleController@store",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/store/make-site-sale', [
                "uses"  => "App\Http\Controllers\SaleController@makeSiteSale",
                'roles' => ['employee', 'management']
            ])->name('sales.make-site-sale');

            Route::delete('/delete/{id}', [
                "uses" => "App\Http\Controllers\SaleController@destroy",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/print/{id}', [
                "uses"  => "App\Http\Controllers\SaleController@print",
                'roles' => ['employee', 'management']
            ])->name('sales.print');

            Route::post('/proof-of-payment/{id}', [
                "uses"  => "App\Http\Controllers\SaleController@addPoP",
                'roles' => ['employee', 'management']
            ]);
        });

        Route::group(['prefix' => 'deliveries'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\API\DeliveryController@index",
                'roles' => ['employee', 'management']
            ]);
            Route::get('/show/{id}', [
                "uses" => "App\Http\Controllers\API\DeliveryController@show",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/update/{id}', [
                "uses" => "App\Http\Controllers\DeliveryController@update",
                'roles' => ['employee', 'management']
            ]);
            Route::get('/request-forms/{id}', [
                "uses" => "App\Http\Controllers\API\DeliveryController@getRequisitions",
                'roles' => ['employee', 'management']
            ]);
        });
        Route::group(['prefix' => 'deliveries'], function () {
            Route::get('/', [
                "uses" => "App\Http\Controllers\API\DeliveryController@index",
                'roles' => ['employee', 'management']
            ]);
            Route::get('/show/{id}', [
                "uses" => "App\Http\Controllers\API\DeliveryController@show",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/update/{id}', [
                "uses" => "App\Http\Controllers\DeliveryController@update",
                'roles' => ['employee', 'management']
            ]);
            Route::get('/request-forms/{id}', [
                "uses" => "App\Http\Controllers\API\DeliveryController@getRequisitions",
                'roles' => ['employee', 'management']
            ]);
        });
        Route::group(['prefix' => 'operations'], function () {
            Route::post('/transporters/store', [
                "uses"  => "App\Http\Controllers\TransporterController@store",
                'roles' => ['employee', 'management']
            ]);
            Route::post('/suppliers/store', [
                "uses"  => "App\Http\Controllers\SupplierController@store",
                'roles' => ['employee', 'management']
            ]);
        });

        Route::group(['prefix' => 'collections'], function () {
            Route::post('/store/{id}', [
                "uses" => "App\Http\Controllers\CollectionController@store",
                'roles' => ['employee', 'management']
            ]);
        });

        Route::group(['prefix' => 'sites'], function () {
            Route::get('/{code}/daily-reports/latest', [
                "uses"  => "App\Http\Controllers\API\SiteController@printReport",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/{code}/daily-reports/print/{id}', [
                "uses"  => "App\Http\Controllers\InventorySummaryController@print",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/{code}/sales', [
                "uses"  => "App\Http\Controllers\API\SiteController@sales",
                'roles' => ['employee', 'management']
            ]);

            Route::get('/{code}/collections', [
                "uses"  => "App\Http\Controllers\API\SiteController@collections",
                'roles' => ['employee', 'management']
            ]);

            Route::post('/{code}/collections/{id}/cancel', [
                "uses" => "App\Http\Controllers\CollectionController@cancel",
                'roles' => ['employee', 'management']
            ]);

            Route::post('/{code}/add-stock', [
                "uses"  => "App\Http\Controllers\InventoryController@update",
                'roles' => ['employee', 'management']
            ]);
        });
    });
});
