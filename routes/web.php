<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DojoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MembershipCardController;
use App\Http\Controllers\NewCardRequestController;
use App\Http\Controllers\ProcessedCardsController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::POST('orders/store', [OrderController::class, 'store'])->name('orders.store');
Route::any('/', [App\Http\Controllers\OrderController::class, 'order_cards'])->name('order_cards');
Route::get('/getSubOrganizations/{organization}', [OrganizationController::class, 'getSubOrganizations'])->name('getSubOrganizations');
Route::get('/getDojos/{organization}/sub_org/{sub_org}', [OrderController::class, 'getDojos'])->name('getDojos');
Route::get('/getInvoice/{organization}/sub_org/{sub_org}', [OrderController::class, 'getInvoice'])->name('getInvoice');
Route::get('/getDojosOrg/{organization}/sub_org/{sub_org}', [OrderController::class, 'getDojosOrg'])->name('getDojosOrg');
Route::POST('order/existing', [OrderController::class, 'existing'])->name('orders.existing');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::delete('organizations/deleteall', [OrganizationController::class, 'deleteAll'])->name('organizations.deleteall');
    Route::delete('Dojos/deleteall', [DojoController::class, 'deleteAll'])->name('dojos.deleteall');
    Route::delete('memberships/deleteall', [MembershipCardController::class, 'deleteAll'])->name('memberships.deleteall');
    Route::get('membership/userDetail/{id}', [MembershipCardController::class, 'userDetail'])->name('membership.userDetail');
    Route::get('memberships/exportCSV', [MembershipCardController::class, 'exportCSV'])->name('memberships.exportCSV');
    Route::get('memberships/exportCSVBodno', [MembershipCardController::class, 'exportCSVBodno'])->name('memberships.exportCSVBodno');
    Route::get('memberships/exportCSVK12', [MembershipCardController::class, 'exportCSVK12'])->name('memberships.exportCSVK12');
    Route::get('newrequest/exportCSV', [NewCardRequestController::class, 'exportCSV'])->name('newrequest.exportCSV');
    Route::get('invoice_to/edit/{name}', [DojoController::class, 'info'])->name('newrequest.invoice_to');
    Route::get('ship_to/edit/{name}', [DojoController::class, 'info'])->name('newrequest.ship_to');
    Route::get('edit/{name}', [DojoController::class, 'info'])->name('membership.info');
    Route::get('newrequest/exportCSVBodno', [NewCardRequestController::class, 'exportCSVBodno'])->name('newrequest.exportCSVBodno');
    Route::get('newrequest/exportCSVK12', [NewCardRequestController::class, 'exportCSVK12'])->name('newrequest.exportCSVK12');
    Route::delete('newrequest/deleteall', [NewCardRequestController::class, 'deleteAll'])->name('newrequest.deleteall');
    Route::delete('users/deleteall', [UserController::class, 'deleteAll'])->name('users.deleteall');
    Route::delete('processed_cards/deleteall', [ProcessedCardsController::class, 'deleteAll'])->name('processed_cards.deleteall');
    Route::get('membership/{id}', [MembershipCardController::class, 'index'])->name('membership.index');

    Route::resources([
        'organizations' => OrganizationController::class,
        'dojos' => DojoController::class,
        // 'orders' => OrderController::class,
        'users' => UserController::class,
        'newrequest' => NewCardRequestController::class,
        'processed_cards' => ProcessedCardsController::class,
        'memberships' => MembershipCardController::class
    ]);
});
