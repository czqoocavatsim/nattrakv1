<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\ClxMessagesController;
use App\Http\Controllers\NotamsController;
use App\Http\Controllers\RclMessagesController;
use App\Http\Controllers\VatsimAuthController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    $notams = Cache::remember('notams', now()->addMinutes(10), function () {
        return json_decode(Http::timeout(5)->get('https://ganderoceanicoca.ams3.digitaloceanspaces.com/resources/data/nattrak/notams.json'));
    });
    return view('welcome', compact('notams'));
})->name('welcome');

Route::prefix('auth')->name('auth')->group(function () {
    Route::get('/redirect', [VatsimAuthController::class, 'redirect'])->name('.redirect');
    Route::get('/authenticate', [VatsimAuthController::class, 'authenticate'])->name('.authenticate');
    Route::get('/deauthenticate', [VatsimAuthController::class, 'deauthenticate'])->name('.deauthenticate');
    if (config('app.env') == 'local') {
        Route::get('/{cid}', function ($cid) {
           \Illuminate\Support\Facades\Auth::loginUsingId($cid);
           return redirect()->route('welcome');
        });
    }
});;


Route::prefix('administration')->name('administration')->middleware('can:administrate')->group(function () {
    Route::get('/', [AdministrationController::class, 'index'])->name('index');
    Route::get('/accounts', [AdministrationController::class, 'accounts'])->name('.accounts');
    Route::post('/accounts/add-access', [AdministrationController::class, 'addAccess'])->name('.accounts.add-access');
    Route::post('/accounts/remove-access', [AdministrationController::class, 'removeAccess'])->name('.accounts.remove-access');

    Route::get('/controllers', [AdministrationController::class, 'controllers'])->name('.controllers');
    Route::post('/controllers/add-access', [AdministrationController::class, 'addControllerAccess'])->name('.controllers.add-access');
    Route::post('/controllers/remove-access', [AdministrationController::class, 'removeControllerAccess'])->name('.controllers.remove-access');

    Route::get('/activity-log', [AdministrationController::class, 'activityLog'])->name('.activity-log');

    Route::prefix('notams')->name('.notams')->controller(NotamsController::class)->group(function () {
       Route::get('/', 'index')->name('.index');
    });
});

Route::prefix('pilots')->name('pilots')->middleware('can:activePilot')->group(function () {
   Route::prefix('rcl')->name('.rcl')->controller(RclMessagesController::class)->group(function () {
       Route::get('/', 'index')->name('.index');
       Route::get('/create', 'create')->name('.create');
       Route::post('/create', 'store')->name('.store');
   });

   Route::get('message-history', function () {
       return view('pilots.message-history', [
           '_pageTitle' => 'Message History'
       ]);
   })->name('.message-history');
});

Route::prefix('controllers')->name('controllers')->middleware('can:activeController')->group(function () {
    Route::prefix('clx')->name('.clx')->controller(ClxMessagesController::class)->group(function () {
        Route::get('/pending', 'getPending')->name('.pending');
        Route::get('/processed', 'getProcessed')->name('.processed');
        Route::post('/transmit/{rclMessage:id}', 'transmit')->name('.transmit');
        Route::get('/rcl-msg/{rclMessage:id}', 'showRclMessage')->name('.show-rcl-message');
        Route::post('/rcl-msg/{rclMessage:id}/del', 'deleteRclMessage')->name('.delete-rcl-message');
        Route::post('/rcl-msg/{rclMessage:id}/revert-to-voice', 'revertToVoice')->name('.revert-to-voice');
    });
});
