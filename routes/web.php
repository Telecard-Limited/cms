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
            Route::resource('rating', 'RatingController');
            Route::resource('messageRecipient', 'MessageRecipientController');
            Route::resource('category', 'CategoryController');

            Route::get('settings', 'SettingController@index')->name('settings.index');
            Route::patch('settings', 'SettingController@update')->name('settings.update');
            // Export Routes

        });

        // Search Route
        Route::get('/searchCustomer', 'SearchController@searchCustomer')->name('search.customer');
        Route::get('/searchIssue', 'SearchController@searchIssue')->name('search.issue');
        Route::resource('customer', 'CustomerController');

        Route::get('/complain/search', 'ComplainController@showSearch')->name('complain.form');
        Route::post('/complain/search/query', 'ComplainController@search')->name('complain.search');
        Route::resource('complain', 'ComplainController')->except(['destroy']);
        Route::get('/export/complain', 'ComplainController@export')->name('complain.export');

        //Widgets
        Route::get('/getWidgetData', 'WidgetController@getData')->name('widget.data');
        Route::get('/getChartLabels', 'WidgetController@getChartLabels')->name('chart.labels');
    });

    Route::namespace('Report')->middleware(['can:admin-access'])->prefix('reports')->group(function () {
        Route::prefix('complains')->group(function () {
            Route::view('/', 'architect.reports.complains')->name('report.complain.get');
            Route::post('report', 'ComplainReportController@report')->name('report.complain.post');
        });
        Route::prefix('ratings')->group(function () {
            Route::view('/', 'architect.reports.ratings')->name('report.rating.get');
            Route::post('report', 'RatingReportController@report')->name('report.rating.post');
        });
        Route::prefix('activity')->group(function () {
            Route::get('/', 'ActivityReportController@index')->name('report.activity');
        });
    });

});
