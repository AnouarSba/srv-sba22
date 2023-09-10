<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KabidController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\PaygetController;
use App\Http\Controllers\ServerController;

Route::get('/srvlogin',[App\Http\Controllers\PaygetController::class, 'login']);

Route::post('/forgpass',[App\Http\Controllers\ClientController::class, 'forgpass']);

Route::get('/link',[App\Http\Controllers\ClientController::class, 'link']);

Route::post('/ftoken',[App\Http\Controllers\ClientController::class, 'ftoken']);
Route::post('/clients_signup',[App\Http\Controllers\ClientController::class, 'register']);
Route::post('/clients_login',[App\Http\Controllers\ClientController::class, 'login']);
Route::post('/clients_register', [App\Http\Controllers\ClientController::class, 'register']);
Route::get('/not',[App\Http\Controllers\ClientController::class, 'notification']);

Route::group(['middleware' => ['auth:sanctum','auth:clients']], function () {

    
    Route::post('/resendpin',[App\Http\Controllers\ClientController::class, 'resendpin']);
    Route::post('/pinchapass',[App\Http\Controllers\ClientController::class, 'pinchapass']);
    
    Route::get('/resend',[App\Http\Controllers\ClientController::class, 'resend']);
    Route::post('/clients_active',[App\Http\Controllers\ClientController::class, 'clients_active']);
    Route::get('/getbalance',[App\Http\Controllers\ClientController::class, 'getbalance']); 
    Route::get('/client_logout',[App\Http\Controllers\ClientController::class, 'logout']);
    Route::get('/checktoken', [App\Http\Controllers\ClientController::class, 'checktoken']);
    Route::get('/gettrans', [App\Http\Controllers\ClientController::class, 'gettrans']);
    Route::get('/lastticket', [App\Http\Controllers\ClientController::class, 'lastticket']);
    Route::post('/add_fund',[App\Http\Controllers\ClientController::class, 'add_fund']);
    Route::post('/changepass',[App\Http\Controllers\ClientController::class, 'changepass']);
    
});



//////////////////////////////////////////////////////////////////////////////////////
Route::post('/rc_login',[KabidController::class, 'login']);
Route::post('/rc_cartlogin',[KabidController::class, 'cartlogin']);




Route::group(['middleware' => ['auth:sanctum','auth:kabids']], function () {
    Route::get('/getblack1', [App\Http\Controllers\kabidController::class, 'getblack']);
    Route::post('/sync_ticket',[KabidController::class, 'sync_ticket']);
    Route::get('/histo',[KabidController::class, 'histo']);
    Route::post('/validation',[KabidController::class, 'validation']);
    Route::post('/transfer',[kabidController::class, 'transfer']);
    Route::get('/getlines',[kabidController::class, 'getlines']);
    Route::get('/getarrets',[kabidController::class, 'getarrets']);
    Route::get('/rc_getbalance',[kabidController::class, 'getbalance']);
    Route::get('/rc_logout',[kabidController::class, 'logout']);
    Route::get('/rc_checktoken', [kabidController::class, 'checktoken']);
    Route::get('/rc_gettrans', [kabidController::class, 'gettrans']);
    Route::get('/rc_gettickets', [kabidController::class, 'gettickets']);
    Route::post('/rc_contro',[kabidController::class, 'contro']);
});

/////////////////////////////////////////////////////////////////////////////////////

Route::post('/co_login',[ControlController::class, 'login']);
Route::post('/co_cartlogin',[ControlController::class, 'cartlogin']);




Route::group(['middleware' => ['auth:sanctum','auth:controls']], function () {
    
    Route::post('/activeabn',[ControlController::class, 'activeabn']);
    Route::get('/getblack', [ControlController::class, 'getblack']);
    Route::post('/contro',[ControlController::class, 'contro']);
    Route::post('/co_vent',[ControlController::class, 'vent']);
    Route::post('/co_transfer',[ControlController::class, 'transfer']);
    Route::get('/co_getbalance',[ControlController::class, 'getbalance']);
    Route::get('/co_logout',[ControlController::class, 'logout']);
    Route::get('/co_checktoken', [ControlController::class, 'checktoken']);
    Route::get('/co_gettrans', [ControlController::class, 'gettrans']);

});

/////////////////////////////////////////////////////////////////////////////////////


Route::group(['middleware' => ['auth:sanctum','auth:paygets']], function () {
    Route::get('/pay_checktoken', [PaygetController::class, 'checktoken']);
    Route::get('/checkcl', [PaygetController::class, 'checkcl']);
    Route::get('/satimcharge', [PaygetController::class, 'satimcharge']);
   
});

/////////////////////////////////////////////////////////////////////////////////////


Route::get('/server_login',[ServerController::class, 'login']);
/////////////////////////////////////////////////////////////////////////////////////


Route::group(['middleware' => ['auth:sanctum','auth:servers']], function () {
    Route::get('/server_checktoken', [ServerController::class, 'checktoken']);   
    Route::get('/server_getbalance', [ServerController::class, 'getbalance']);
    Route::get('/server_gettrans', [ServerController::class, 'gettrans']);
    Route::post('/server_validation',[ServerController::class, 'server_validation']);
});

/////////////////////////////////////////////////////////////////////////////////////
Route::get('/getline',[App\Http\Controllers\LigneController::class, 'getline']);
Route::get('/getarret',[App\Http\Controllers\LigneController::class, 'getarret']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
