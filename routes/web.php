<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/

Route::group(['middleware'=>['auth:sanctum', 'verified','roles']],function (){

    Route::get('/', [
        "uses"  => "App\Http\Controllers\AppController@dashboard",
    ])->name('dashboard');

    Route::get('/all-requests', [
        "uses"  => "App\Http\Controllers\RequestFormController@index",
        'roles' =>['employee','management']
    ])->name('index');

    Route::get('/approved-requests', [
        "uses"  => "App\Http\Controllers\RequestFormController@approved",
        'roles' =>['employee','management']
    ])->name('approved');

    Route::get('/finance', [
        "uses"  => "App\Http\Controllers\RequestFormController@finance",
        'roles' =>['accountant','management']
    ])->name('finance');


    Route::group(['prefix'=>'users'],function() {
        Route::get('/', [
            "uses" => "App\Http\Controllers\UserController@index",
            'roles' =>['administrator','management']
        ])->name('users');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\UserController@show",
            'roles' =>['administrator','management']
        ])->name('users.show');

        Route::post('/verify/{id}', [
            "uses" => "App\Http\Controllers\UserController@verify",
            'roles' =>['management']
        ])->name('users.verify');

        Route::post('/disable/{id}', [
            "uses" => "App\Http\Controllers\UserController@disable",
            'roles' =>['management']
        ])->name('users.disable');

        Route::post('/discard/{id}', [
            "uses" => "App\Http\Controllers\UserController@discard",
            'roles' =>['management']
        ])->name('users.discard');

        Route::post('/give-role/{id}/{role}', [
            "uses" => "App\Http\Controllers\UserController@giveRole",
            'roles' =>['management']
        ])->name('users.give-role');

        Route::post('/revoke-role/{id}/{role}', [
            "uses" => "App\Http\Controllers\UserController@revokeRole",
            'roles' =>['management']
        ])->name('users.revoke-role');

    });

    Route::group(['prefix'=>'projects'],function(){
        Route::get('/', [
            "uses"  => "App\Http\Controllers\ProjectController@index",
            'roles' => ['administrator','management']
        ])->name('projects');

        Route::get('/view/{id}', [
            "uses"  => "App\Http\Controllers\ProjectController@show",
            'roles' => ['administrator','management']
        ])->name('projects.show');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\ProjectController@create",
            'roles' =>['administrator']
        ])->name('projects.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\ProjectController@store",
            'roles' =>['administrator']
        ])->name('projects.store');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ProjectController@edit",
            'roles' =>['administrator']
        ])->name('projects.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ProjectController@update",
            'roles' =>['administrator']
        ])->name('projects.update');

        Route::post('/verify/{id}', [
            "uses"  => "App\Http\Controllers\ProjectController@verify",
            'roles' =>['management']
        ])->name('projects.verify');

        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\ProjectController@destroy",
            'roles' => ['administrator','management']
        ])->name('projects.delete');

        Route::post('/close/{id}', [
            "uses"  => "App\Http\Controllers\ProjectController@close",
            'roles' => ['administrator','management']
        ])->name('projects.close');
    });

    Route::group(['prefix'=>'vehicles'],function(){
        Route::get('/', [
            "uses"  => "App\Http\Controllers\VehicleController@index",
            'roles' => ['administrator','management']
        ])->name('vehicles');

        Route::get('/view/{id}', [
            "uses"  => "App\Http\Controllers\VehicleController@show",
            'roles' => ['employee','management']
        ])->name('vehicles.show');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\VehicleController@create",
            'roles' =>['administrator']
        ])->name('vehicles.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\VehicleController@store",
            'roles' =>['administrator']
        ])->name('vehicles.store');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\VehicleController@edit",
            'roles' =>['administrator']
        ])->name('vehicles.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\VehicleController@update",
            'roles' =>['administrator']
        ])->name('vehicles.update');

        Route::post('/verify/{id}', [
            "uses"  => "App\Http\Controllers\VehicleController@verify",
            'roles' =>['management']
        ])->name('vehicles.verify');

        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\VehicleController@destroy",
            'roles' => ['administrator','management']
        ])->name('vehicles.delete');

        Route::post('/close/{id}', [
            "uses"  => "App\Http\Controllers\VehicleController@close",
            'roles' => ['administrator','management']
        ])->name('vehicles.close');

        Route::get('/gases', [
            "uses"  => "App\Http\Controllers\GasController@edit",
            'roles' =>['administrator']
        ])->name('gases.edit');

        Route::post('/gases', [
            "uses"  => "App\Http\Controllers\GasController@update",
            'roles' =>['administrator']
        ])->name('gases.update');
    });

    Route::group(['prefix'=>'request-forms'],function() {

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\RequestFormController@create",
            'roles' =>['employee','management']
        ])->name('request-forms.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\RequestFormController@store",
            'roles' =>['employee','management']
        ])->name('request-forms.store');

        Route::post('/store/delivery/{summary_id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@storeFromDelivery",
            'roles' =>['employee','management']
        ])->name('request-forms.store.delivery');

        Route::post('/store/payable', [
            "uses"  => "App\Http\Controllers\RequestFormController@storeFromPayable",
            'roles' =>['accountant','management']
        ])->name('request-forms.store.payable');

        Route::get('/view/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@show",
            'roles' =>['employee','management']
        ])->name('request-forms.show');

        Route::post('/find/{code}', [
            "uses"  => "App\Http\Controllers\RequestFormController@findRequestForm",
            'roles' =>['employee','management']
        ])->name('request-forms.find');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@edit",
            'roles' =>['employee','management']
        ])->name('request-forms.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@update",
            'roles' =>['employee','management']
        ])->name('request-forms.update');

        Route::post('/approve/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@approve",
            'roles' =>['employee','management']
        ])->name('request-forms.approve');

        Route::post('/deny/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@deny",
            'roles' =>['employee','management']
        ])->name('request-forms.deny');

        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@destroy",
            'roles' =>['employee','management']
        ])->name('request-forms.delete');

        Route::post('/discard/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@discard",
            'roles' =>['employee','management']
        ])->name('request-forms.discard');

        Route::post('/add-remarks/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@appendRemarks",
            'roles' =>['employee','management']
        ])->name('request-forms.add-remarks');

        Route::post('/initiate/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@initiate",
             'roles' =>['accountant']
        ])->name('request-forms.initiate');

        Route::post('/reconcile/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@reconcile",
             'roles' =>['accountant']
        ])->name('request-forms.reconcile');

        Route::get('/print/{id}', [
            "uses"  => "App\Http\Controllers\RequestFormController@print",
            'roles' =>['employee','management']
        ])->name('request-forms.print');

    });

    Route::group(['prefix'=>'quotations'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\QuotationController@index",
            'roles' =>['employee','management']
        ])->name('quotations.index');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\QuotationController@create",
            'roles' =>['employee','management']
        ])->name('quotations.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\QuotationController@store",
            'roles' =>['employee','management']
        ])->name('quotations.store');

        Route::get('/view/{id}', [
            "uses"  => "App\Http\Controllers\QuotationController@show",
            'roles' =>['employee','management']
        ])->name('quotations.show');

        Route::get('/print/{id}', [
            "uses"  => "App\Http\Controllers\QuotationController@print",
            'roles' =>['employee','management']
        ])->name('quotations.print');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\QuotationController@edit",
            'roles' =>['employee','management']
        ])->name('quotations.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\QuotationController@update",
            'roles' =>['employee','management']
        ])->name('quotations.update');

        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\QuotationController@destroy",
            'roles' =>['employee','management']
        ])->name('quotations.delete');

    });

    Route::group(['prefix'=>'invoices'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\InvoiceController@index",
            'roles' =>['employee','management']
        ])->name('invoices.index');

//        Route::get('/create', [
//            "uses"  => "App\Http\Controllers\InvoiceController@create",
//            'roles' =>['employee','management']
//        ])->name('invoices.create');
//
//        Route::post('/store', [
//            "uses"  => "App\Http\Controllers\InvoiceController@store",
//            'roles' =>['employee','management']
//        ])->name('invoices.store');

        Route::get('/view/{id}', [
            "uses"  => "App\Http\Controllers\InvoiceController@show",
            'roles' =>['employee','management']
        ])->name('invoices.show');

        Route::post('/generate/{id}', [
            "uses"  => "App\Http\Controllers\InvoiceController@storeFromSale",
            'roles' =>['employee','management']
        ])->name('invoices.generate');

        Route::get('/print/{id}', [
            "uses"  => "App\Http\Controllers\InvoiceController@print",
            'roles' =>['employee','management']
        ])->name('invoices.print');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\InvoiceController@edit",
            'roles' =>['employee','management']
        ])->name('invoices.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\InvoiceController@update",
            'roles' =>['employee','management']
        ])->name('invoices.update');

        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\InvoiceController@destroy",
            'roles' =>['employee','management']
        ])->name('invoices.delete');

    });

    Route::group(['prefix'=>'sales'],function() {

        Route::get('/{section}', [
            "uses"  => "App\Http\Controllers\SaleController@index",
            'roles' =>['employee','management']
        ])->name('sales.index');

        Route::get('/create/new', [
            "uses"  => "App\Http\Controllers\SaleController@create",
            'roles' =>['employee','management']
        ])->name('sales.create');

        Route::get('/generate-from-quotation/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@storeFromQuotation",
            'roles' =>['employee','management']
        ])->name('sales.generate-from-quotation');

        Route::post('/store/new', [
            "uses"  => "App\Http\Controllers\SaleController@store",
            'roles' =>['employee','management']
        ])->name('sales.store');

        Route::post('/store/make-site-sale', [
            "uses"  => "App\Http\Controllers\SaleController@makeSiteSale",
            'roles' =>['employee','management']
        ])->name('sales.make-site-sale');

        Route::get('/view/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@show",
            'roles' =>['employee','management']
        ])->name('sales.show');

        Route::get('/print/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@print",
            'roles' =>['employee','management']
        ])->name('sales.print');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@edit",
            'roles' =>['employee','management']
        ])->name('sales.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@update",
            'roles' =>['employee','management']
        ])->name('sales.update');

        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@destroy",
            'roles' =>['employee','management']
        ])->name('sales.delete');

        Route::post('/close/{id}', [
            "uses"  => "App\Http\Controllers\SaleController@close",
            'roles' =>['employee','management']
        ])->name('sales.close');

        



    });
    Route::group(['prefix'=>'sites'],function() {

        Route::get('/{code}/overview', [
            "uses"  => "App\Http\Controllers\SiteController@overview",
            'roles' =>['employee','management']
        ])->name('sites.overview');

        Route::get('/{code}/sales/create', [
            "uses"  => "App\Http\Controllers\SiteSaleController@create",
            'roles' =>['employee','management']
        ])->name('sites.sales.create');

        Route::get('/{code}/inventories/{id}', [
            "uses"  => "App\Http\Controllers\InventoryController@show",
            'roles' =>['employee','management']
        ])->name('sites.inventories.show');

        Route::post('/{code}/inventories', [
            "uses"  => "App\Http\Controllers\InventoryController@store",
            'roles' =>['employee','management']
        ])->name('sites.inventories.store');

        Route::post('/{code}/inventories/{id}', [
            "uses"  => "App\Http\Controllers\InventoryController@edit",
            'roles' =>['employee','management']
        ])->name('sites.inventories.edit');

        Route::get('/{code}/daily-reports/{id}', [
            "uses"  => "App\Http\Controllers\InventorySummaryController@show",
            'roles' =>['employee','management']
        ])->name('sites.summaries.show');

        Route::get('/daily-reports/print/{id}', [
            "uses"  => "App\Http\Controllers\InventorySummaryController@print",
            'roles' =>['employee','management']
        ])->name('sites.summaries.print');

        Route::post('/{code}/sales/store', [
            "uses"  => "App\Http\Controllers\SiteSaleController@store",
            'roles' =>['employee','management']
        ])->name('sites.sales.store');
        
        Route::post('/sites/sales/delete/{id}', [
            "uses"  => "App\Http\Controllers\SiteSaleController@destroy",
            'roles' =>['employee','management']
        ])->name('sites.sales.delete');

        Route::get('/{code}/sales/view/{id}', [
            "uses"  => "App\Http\Controllers\SiteSaleController@show",
            'roles' =>['employee','management']
        ])->name('sites.sales.show');

        Route::get('/{code}/production', [
            "uses"  => "App\Http\Controllers\ProductionController@index",
            'roles' =>['employee','management']
        ])->name('production.index');

        Route::get('/{code}/production/show', [
            "uses"  => "App\Http\Controllers\ProductionController@show",
            'roles' =>['employee','management']
        ])->name('production.show');
        
        Route::post('/{code}/production/store', [
            "uses"  => "App\Http\Controllers\ProductionController@store",
            'roles' =>['employee','management']
        ])->name('production.store');
        
        Route::post('/{code}/production/delete', [
            "uses"  => "App\Http\Controllers\ProductionController@destroy",
            'roles' =>['employee','management']
        ])->name('production.delete');

           Route::get('/{code}/production/print', [
            "uses"  => "App\Http\Controllers\ProductionController@print",
            'roles' =>['employee','management']
        ])->name('production.print');

        Route::post('/materials', [
            "uses"  => "App\Http\Controllers\MaterialController@store",
            'roles' =>['employee','management']
        ])->name('materials.store');

        Route::post('/materials/update-stock', [
            "uses"  => "App\Http\Controllers\MaterialController@update",
            'roles' =>['employee','management']
        ])->name('materials.update');



    });

    Route::group(['prefix'=>'collections'],function() {


       Route::get('/{code}', [
           "uses"  => "App\Http\Controllers\CollectionController@index",
           'roles' =>['employee','management']
       ])->name('sites.collections');

       Route::get('/{code}/show/{collection_code}', [
           "uses"  => "App\Http\Controllers\CollectionController@show",
           'roles' =>['employee','management']
       ])->name('sites.collections.show');

       Route::post('/{code}/delete/{collection_code}', [
           "uses"  => "App\Http\Controllers\CollectionController@destroy",
           'roles' =>['employee','management']
       ])->name('sites.collections.delete');

        Route::post('store/{id}', [
            "uses" => "App\Http\Controllers\CollectionController@store",
            'roles' => ['employee', 'management']
        ])->name('collections.store');

        Route::post('cancel/{id}', [
            "uses" => "App\Http\Controllers\CollectionController@cancel",
            'roles' => ['employee', 'management']
        ])->name('collections.cancel');

        Route::post('trash/{code}', [
            "uses" => "App\Http\Controllers\CollectionController@trash",
            'roles' => ['employee', 'management']
        ])->name('collections.trash');
    });

    Route::group(['prefix'=>'inventories'],function() {

        Route::post('/inventories/update', [
            "uses"  => "App\Http\Controllers\InventoryController@update",
            'roles' =>['employee','management']
        ])->name('inventories.update');
    });


    Route::group(['prefix'=>'notifications'],function() {

        Route::get('/', [
            "uses" => "App\Http\Controllers\NotificationController@index",
            'roles' => ['employee', 'management']
        ])->name('notifications');

        Route::post('/whatsapp', [
            "uses" => "App\Http\Controllers\NotificationController@sendWhatsappMessage",
            'roles' => ['employee', 'management']
        ])->name('notifications.whatsapp');
    });

    Route::group(['prefix'=>'receipts'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\ReceiptController@index",
            'roles' =>['employee','management']
        ])->name('receipts.index');

        Route::post('/sale/{id}', [
            "uses"  => "App\Http\Controllers\ReceiptController@store",
            'roles' =>['employee','management']
        ])->name('receipts.store');

        Route::post('/sale/attach/{id}', [
            "uses"  => "App\Http\Controllers\ReceiptController@attachReceipt",
            'roles' =>['employee','management']
        ])->name('receipts.attach');

        Route::get('/print/{id}', [
            "uses"  => "App\Http\Controllers\ReceiptController@print",
            'roles' =>['employee','management']
        ])->name('receipts.print');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\ReceiptController@show",
            'roles' => ['employee', 'management']
        ])->name('receipts.show');
    });

    Route::group(['prefix'=>'clients'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\ClientController@index",
            'roles' =>['employee','management']
        ])->name('clients.index');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\ClientController@create",
            'roles' =>['employee','management']
        ])->name('clients.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\ClientController@store",
            'roles' =>['employee','management']
        ])->name('clients.store');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\ClientController@show",
            'roles' => ['employee', 'management']
        ])->name('clients.show');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ClientController@edit",
            'roles' =>['employee','management']
        ])->name('clients.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ClientController@update",
            'roles' =>['employee','management']
        ])->name('clients.update');

    });

    Route::group(['prefix'=>'payables'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\PayableController@index",
            'roles' =>['accountant','management']
        ])->name('payables.index');

//        Route::get('/create', [
//            "uses"  => "App\Http\Controllers\ClientController@create",
//            'roles' =>['employee','management']
//        ])->name('clients.create');
//
//        Route::post('/store', [
//            "uses"  => "App\Http\Controllers\ClientController@store",
//            'roles' =>['employee','management']
//        ])->name('clients.store');
//
//        Route::get('/view/{id}', [
//            "uses" => "App\Http\Controllers\ClientController@show",
//            'roles' => ['employee', 'management']
//        ])->name('clients.show');
//
//        Route::get('/edit/{id}', [
//            "uses"  => "App\Http\Controllers\ClientController@edit",
//            'roles' =>['employee','management']
//        ])->name('clients.edit');
//
//        Route::post('/edit/{id}', [
//            "uses"  => "App\Http\Controllers\ClientController@update",
//            'roles' =>['employee','management']
//        ])->name('clients.update');

    });

    Route::group(['prefix'=>'expenses'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\ExpenseController@index",
            'roles' =>['accountant','management']
        ])->name('expenses.index');

//        Route::get('/create', [
//            "uses"  => "App\Http\Controllers\ExpenseController@create",
//            'roles' =>['accountant','management']
//        ])->name('expenses.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\ExpenseController@store",
            'roles' =>['employee','management']
        ])->name('expenses.store');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\ExpenseController@show",
            'roles' => ['employee', 'management']
        ])->name('expenses.show');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ExpenseController@edit",
            'roles' =>['employee','management']
        ])->name('expenses.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ExpenseController@update",
            'roles' =>['employee','management']
        ])->name('expenses.update');

    });

    Route::group(['prefix'=>'transactions'],function() {

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\TransactionController@store",
            'roles' =>['accountant','management']
        ])->name('transactions.store');
      
        Route::post('/update/{id}', [
            "uses"  => "App\Http\Controllers\TransactionController@update",
            'roles' =>['accountant','management']
        ])->name('transactions.update');
      
        Route::post('/delete/{id}', [
            "uses"  => "App\Http\Controllers\TransactionController@destroy",
            'roles' =>['accountant','management']
        ])->name('transactions.delete');

    });

    Route::group(['prefix'=>'accounts'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\AccountingController@index",
            'roles' =>['accountant','management']
        ])->name('accounts.index');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\AccountController@create",
            'roles' =>['accountant','management']
        ])->name('accounts.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\AccountController@store",
            'roles' =>['accountant','management']
        ])->name('accounts.store');

        Route::post('/transfer', [
            "uses"  => "App\Http\Controllers\AccountingController@transfer",
            'roles' =>['accountant','management']
        ])->name('accounts.transfer');

        Route::post('/add-transaction', [
            "uses"  => "App\Http\Controllers\AccountingController@addTransaction",
            'roles' =>['accountant','management']
        ])->name('accounts.add-transaction');

        Route::get('/view/{code}', [
            "uses" => "App\Http\Controllers\AccountingController@show",
            'roles' => ['accountant', 'management']
        ])->name('accounts.show');

        Route::get('/transaction/{serial}', [
            "uses" => "App\Http\Controllers\AccountingController@showRecord",
            'roles' => ['accountant', 'management']
        ])->name('accounts.transaction');

        Route::post('/transaction/{serial}/edit', [
            "uses" => "App\Http\Controllers\AccountingRecordController@update",
            'roles' => ['accountant', 'management']
        ])->name('accounts.transaction.edit');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\AccountController@edit",
            'roles' =>['accountant','management']
        ])->name('accounts.edit');

        Route::post('/edit/{id}', [
            "uses"  => "App\Http\Controllers\AccountController@update",
            'roles' =>['accountant','management']
        ])->name('accounts.update');

    });


    Route::group(['prefix'=>'deliveries'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\DeliveryController@index",
            'roles' =>['employee','management']
        ])->name('deliveries.index');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\DeliveryController@show",
            'roles' => ['employee', 'management']
        ])->name('deliveries.show');

        Route::post('/update/{id}', [
            "uses"  => "App\Http\Controllers\DeliveryController@update",
            'roles' => ['employee','management']
        ])->name('deliveries.update');

        Route::post('/cancel/{id}', [
            "uses"  => "App\Http\Controllers\DeliveryController@cancel",
            'roles' => ['employee','management']
        ])->name('deliveries.cancel');

        Route::post('/complete/{id}', [
            "uses"  => "App\Http\Controllers\DeliveryController@complete",
            'roles' => ['employee','management']
        ])->name('deliveries.complete');

        Route::get('/print/{id}', [
            "uses"  => "App\Http\Controllers\DeliveryController@print",
            'roles' =>['employee','management']
        ])->name('deliveries.print');

    });

    Route::group(['prefix'=>'reports'],function() {

        Route::get('/', [
            "uses" => "App\Http\Controllers\ReportController@index",
            'roles' => ['accountant','administrator', 'management']
//            'roles' => ['accountant', 'management', 'administrator']
        ])->name('reports.requisitions');

        Route::get('/statements', [
            "uses" => "App\Http\Controllers\StatementController@index",
            'roles' => ['accountant','administrator', 'management']
        ])->name('reports.statements');

        Route::get('/statements/{serial}', [
            "uses" => "App\Http\Controllers\StatementController@show",
            'roles' => ['accountant','administrator', 'management']
        ])->name('reports.statements.show');

        Route::get('/generate', [
            "uses" => "App\Http\Controllers\ReportController@generate",
            'roles' => ['accountant','administrator', 'management']
//            'roles' => ['accountant', 'management', 'administrator']
        ])->name('reports.generate');

    });

    Route::group(['prefix'=>'products'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\ProductController@index",
            'roles' =>['employee','management']
        ])->name('products.index');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\ProductController@show",
            'roles' => ['employee', 'management']
        ])->name('products.show');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\ProductController@create",
            'roles' => ['employee','management']
        ])->name('products.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\ProductController@store",
            'roles' => ['employee','management']
        ])->name('products.store');

        Route::post('/add-variant', [
            "uses"  => "App\Http\Controllers\ProductController@addVariant",
            'roles' => ['employee','management']
        ])->name('products.add-variant');

        Route::post('/edit-variant', [
            "uses"  => "App\Http\Controllers\ProductController@editVariant",
            'roles' => ['employee','management']
        ])->name('products.edit-price');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\ProductController@edit",
            'roles' => ['employee','management']
        ])->name('products.edit');

        Route::post('/update/{id}', [
            "uses"  => "App\Http\Controllers\DeliveryController@update",
            'roles' => ['employee','management']
        ])->name('products.update');

    });

    Route::group(['prefix'=>'transporters'],function() {

        Route::get('/', [
            "uses"  => "App\Http\Controllers\TransporterController@index",
            'roles' =>['employee','management']
        ])->name('transporters.index');

        Route::get('/view/{id}', [
            "uses" => "App\Http\Controllers\TransporterController@show",
            'roles' => ['employee', 'management']
        ])->name('transporters.show');

        Route::get('/create', [
            "uses"  => "App\Http\Controllers\TransporterController@create",
            'roles' => ['employee','management']
        ])->name('transporters.create');

        Route::post('/store', [
            "uses"  => "App\Http\Controllers\TransporterController@store",
            'roles' => ['employee','management']
        ])->name('transporters.store');

        Route::get('/edit/{id}', [
            "uses"  => "App\Http\Controllers\TransporterController@edit",
            'roles' => ['employee','management']
        ])->name('transporters.edit');

        Route::post('/update/{id}', [
            "uses"  => "App\Http\Controllers\DeliveryController@update",
            'roles' => ['employee','management']
        ])->name('transporters.update');

    });

    Route::group(['prefix'=>'settings'],function() {

        Route::get('/accounting-centre', [
            "uses"  => "App\Http\Controllers\SettingsController@accountingCentre",
            'roles' =>['accountant','management']
        ])->name('settings.accounting-centre');
       
        Route::get('/accounting-centre/balance-sheet', [
            "uses"  => "App\Http\Controllers\SettingsController@balanceSheet",
            'roles' =>['accountant','management']
        ])->name('settings.balance-sheet');

        Route::get('/accounting-centre/income-statement', [
            "uses"  => "App\Http\Controllers\SettingsController@incomeStatement",
            'roles' =>['accountant','management']
        ])->name('settings.income-statement');

        Route::get('/accounting-centre/sites/{code}', [
            "uses"  => "App\Http\Controllers\SettingsController@site",
            'roles' =>['accountant','management']
        ])->name('settings.site');

        Route::post('/accounting-centre/sites/{code}', [
            "uses"  => "App\Http\Controllers\SettingsController@siteAttachAccount",
            'roles' =>['accountant','management']
        ])->name('settings.site.attach-account');

        Route::post('/accounting-centre/inventories/{id}', [
            "uses"  => "App\Http\Controllers\SettingsController@inventoryAttachAccount",
            'roles' =>['accountant','management']
        ])->name('settings.inventory.attach-account');
       
    });



});
