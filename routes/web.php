<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Fleet\BestInterviewQuestionController;
use App\Http\Controllers\Fleet\SettingsController;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
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

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::prefix('auth')->group(function () {
    Route::any('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
    Route::post('login', [LoginController::class, 'signIn'])->name('auth.login');
});

//Route::post('/notifications/clear', [Notifications::class, 'clear'])->name('notifications.clear');

Route::group(['middleware' => ['auth', 'dbTransaction']], function () {

    //API
    Route::get('user/car-requests/{user_id}', [SettingsController::class, 'getUserCarRequests'])->name('settings.user.car.requests');
    Route::get('user/odometer/history/{user_id}', [SettingsController::class, 'getUserOdometerHistory'])->name('settings.user.car.odometer.history');
    Route::get('services/get', [ApiController::class, 'getProductsAndServices'])->name('getProductsAndServices');
    Route::get('taxes/get', [ApiController::class, 'getTaxes'])->name('getTaxes');


    //for all
    Route::get('notice-board', function () {
        $user_id = Auth::user()->id;
        $notifications =  Notification::where('to_user_id',$user_id )->orderBy('created_at', 'desc')->get();
        // Delete all notifications for current user
        Notification::where('to_user_id', $user_id)->update(['unread' => 0]);

        return view('shared.notifications', compact('notifications'));
    })->name('mynotification');

     Route::delete('notice-board/delete', function () {
        $user_id = Auth::user()->id;        
        Notification::where('to_user_id', $user_id)->delete();

           return back()->with('success', 'My Notifications Cleared!');
    })->name('mynotification.clearall');


    // FLEET ROUTES
    require __DIR__ . '/admin/fleet.php';

    //driver routes
    require __DIR__ . '/admin/driver.php';
    require __DIR__ . '/admin/merchanic.php';
    require __DIR__ . '/admin/accounts.php';

    Route::get('profile/image/{dir}/{filename}', function ($dir, $filename) {
        $path = storage_path('app/public/uploads/' . $dir . '/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file);
        $response->header("Content-Type", $type);

        return $response;
    })->name('avatar_url');

    Route::get('file/download', function () {
        $file_path = request()->get('path');
        $path =  public_path(str_replace("public/", "storage/", $file_path));
        //return public_path('uploads/car/garage/n9IMfBcGjiJ6HJ9kCPwX1LgVU0X5TmjjjhqUJ9UF.jpg');
        //return $path;public_path('storage/car/garage/n9IMfBcGjiJ6HJ9kCPwX1LgVU0X5TmjjjhqUJ9UF.jpg');// 
        // $path = public_path('storage/uploads/car/garage/n9IMfBcGjiJ6HJ9kCPwX1LgVU0X5TmjjjhqUJ9UF.jpg');
        //return $path;
        // return response()->file($path);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file);
        $response->header("Content-Type", $type);

        return $response;
    })->name('downloader');
});

Route::get('pdfview', [BestInterviewQuestionController::class, 'pdfview'])->name('pdfview');
