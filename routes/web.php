<?php

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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    //return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('backend')->middleware(['auth'])->group(function () {
    Route::view('/', 'architect.index')->name('index');

    Route::namespace('Backend')->group(function () {

        Route::middleware(['can:admin-access'])->group(function () {
            Route::resource('users', 'UserController');
            Route::resource('outlet', 'OutletController');
            Route::resource('department', 'DepartmentController');
            Route::resource('issue', 'IssueController');
            Route::resource('ticketStatus', 'TicketStatusController');
            Route::resource('smsRecipient', 'SmsRecipientController');
            Route::resource('complain', 'ComplainController')->except(['destroy']);
        });

        Route::middleware(['can:agent-access'])->group(function () {
            Route::resource('complain', 'ComplainController')->only(['create', 'index', 'store']);
        });
    });

});
