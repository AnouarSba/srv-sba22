<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::get('/privacy', function () {
    return view('privacy');
});

Route::redirect('/','login');
Auth::routes(['register' => false]);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/users', [App\Http\Controllers\HomeController::class, 'users'])->name('users')->middleware('permission:add_users');
    Route::get('/roles', [App\Http\Controllers\HomeController::class, 'roles'])->name('roles')->middleware('permission:add_role');
    Route::get('/permissions', [App\Http\Controllers\HomeController::class, 'permissions'])->name('permissions')->middleware('permission:add_permission');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/kabid', [App\Http\Controllers\KabidController::class, 'index'])->name('kabid')->middleware('permission:add_kabid');
    Route::get('/controle', [App\Http\Controllers\ControlController::class, 'index'])->name('controle')->middleware('permission:add_controle');
    Route::get('/vendeurs', [App\Http\Controllers\VendeurController::class, 'index'])->name('vendeurs')->middleware('permission:add_vendeurs');
    Route::get('/payget', [App\Http\Controllers\PaygetController::class, 'index'])->name('payget')->middleware('permission:add_payget');
    Route::get('/lignes', [App\Http\Controllers\LigneController::class, 'index'])->name('lignes')->middleware('permission:add_lignes');
    Route::get('/arrets', [App\Http\Controllers\ArretController::class, 'index'])->name('arrets')->middleware('permission:add_arrets');
    
    Route::get('/locate', [App\Http\Controllers\ArretController::class, 'locate'])->name('locate');
    Route::get('/Top_flexy', [App\Http\Controllers\ClientController::class, 'Top'])->name('Top');
    Route::post('/export', [App\Http\Controllers\ExportexcelController::class, 'exportData'])->name('exportData');
    Route::get('/List_handicape', [App\Http\Controllers\SpcartController::class, 'generatePdf'])->name('generatePdf');

    Route::get('/buses', [App\Http\Controllers\BusController::class, 'index'])->name('buses')->middleware('permission:add_buses');
    Route::get('/valideurs', [App\Http\Controllers\ValideurController::class, 'index'])->name('valideurs')->middleware('permission:add_valideurs');
    Route::get('/vtickets', [App\Http\Controllers\PVTicketController::class, 'index'])->name('vtickets')->middleware('permission:add_vtickets');
    Route::get('/pcarts', [App\Http\Controllers\PCartController::class, 'index'])->name('pcarts')->middleware('permission:add_pcarts');

    Route::get('/allcarts', [App\Http\Controllers\PCartController::class, 'allcarts'])->name('allcarts')->middleware('permission:allcarts');

    Route::get('/soldecarts', [App\Http\Controllers\PsCartController::class, 'index'])->name('soldecarts')->middleware('permission:add_soldecarts');
    Route::get('/print/{id}', [App\Http\Controllers\PVTicketController::class, 'print'])->name('print')->middleware('permission:print');
    Route::get('/printx/{start}/{end}', [App\Http\Controllers\PVTicketController::class, 'printx'])->name('printx')->middleware('permission:printx');
    Route::get('/prints/{id}', [App\Http\Controllers\PsCartController::class, 'print'])->name('prints')->middleware('permission:prints');
    Route::get('/printsolde/{start}/{end}', [App\Http\Controllers\PsCartController::class, 'printx'])->name('printsolde')->middleware('permission:printsolde');
    Route::get('/printauthcartva/{id}', [App\Http\Controllers\PCartController::class, 'printauthcartva'])->name('printauthcartva')->middleware('permission:printauthcartva');
    Route::get('/printauthcartco/{id}', [App\Http\Controllers\PCartController::class, 'printauthcartco'])->name('printauthcartco')->middleware('permission:printauthcartco');
    Route::get('/printauthcart/{id}', [App\Http\Controllers\PCartController::class, 'printauthcart'])->name('printauthcart')->middleware('permission:printauthcart');
    Route::get('/printcart/{id}', [App\Http\Controllers\PCartController::class, 'printcart'])->name('printcart')->middleware('permission:printcart');
    Route::get('/cartticket/{id}', [App\Http\Controllers\CartController::class, 'cartticket'])->name('cartticket')->middleware('permission:cartticket');
    Route::get('/carttrans/{id}', [App\Http\Controllers\CartController::class, 'carttrans'])->name('carttrans')->middleware('permission:carttrans');
    Route::get('/controltrans/{id}', [App\Http\Controllers\ControlController::class, 'controltrans'])->name('controltrans')->middleware('permission:controltrans');
    Route::get('/kabidtrans/{id}', [App\Http\Controllers\KabidController::class, 'kabidtrans'])->name('kabidtrans')->middleware('permission:kabidtrans');
    Route::get('/vendtrans/{id}', [App\Http\Controllers\VendeurController::class, 'vendtrans'])->name('vendtrans')->middleware('permission:vendtrans');

    Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->name('clients')->middleware('permission:add_clients');
    Route::get('/clientstrans/{id}', [App\Http\Controllers\ClientController::class, 'clientstrans'])->name('clientstrans')->middleware('permission:clientstrans');
    
    Route::get('/getwaytrans/{id}', [App\Http\Controllers\PaygetController::class, 'getwaytrans'])->name('getwaytrans')->middleware('permission:getwaytrans');
    
    Route::get('/spcarttrans/{id}', [App\Http\Controllers\CartController::class, 'spcarttrans'])->name('spcarttrans')->middleware('permission:spcarttrans');
    Route::get('/printspcart/{id}', [App\Http\Controllers\CartController::class, 'printspcart'])->name('printspcart')->middleware('permission:printspcart');

    Route::get('/stats', [App\Http\Controllers\StatController::class, 'index'])->name('stats')->middleware('permission:add_stats');

    Route::post('/stat1', [App\Http\Controllers\StatController::class, 'stat1'])->name('stat1');
    Route::post('/stat2', [App\Http\Controllers\StatController::class, 'stat2'])->name('stat2');
    Route::post('/stat3', [App\Http\Controllers\StatController::class, 'stat3'])->name('stat3');
    Route::post('/stat4', [App\Http\Controllers\StatController::class, 'stat4'])->name('stat4');
    Route::post('/stat5', [App\Http\Controllers\StatController::class, 'stat5'])->name('stat5');
    Route::post('/stat6', [App\Http\Controllers\StatController::class, 'stat6'])->name('stat6');

    Route::get('/server', [App\Http\Controllers\ServerController::class, 'index'])->name('server')->middleware('permission:add_server');
    Route::get('/servertrans/{id}', [App\Http\Controllers\ServerController::class, 'servertrans'])->name('servertrans')->middleware('permission:servertrans');


    Route::get('/spcarts', [App\Http\Controllers\SpcartController::class, 'index'])->name('spcarts')->middleware('permission:add_spcarts');
});



