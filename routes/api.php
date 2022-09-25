<?php

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
// use Illuminate\Support\Facades\Broadcast;

// Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('login', 'Auth\LoginController@requestLogin');
    Route::post('login/verify', 'Auth\LoginController@verifyLogin');
    Route::post('login/resend', 'Auth\LoginController@resendLogin');

    Route::post('register', 'Auth\RegisterController@registerAsUser');
    Route::post('register/verify', 'Auth\RegisterController@verifyRegister');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', 'UserController@me');
    Route::get('driver-location', 'UserController@getDriverNearestLocation')->middleware('role:user');

    Route::group(['prefix' => 'user', 'middleware' => 'role:user'], function () {
        Route::put('update', 'UserController@updateProfile');
        Route::post('update/foto', 'UserController@updateProfilePicture');
        Route::post('update/number', 'UserController@updatePhoneNumber');
        Route::post('verify/number', 'UserController@verifyPhoneNumber');
        Route::get('locations', 'UserController@getFavouriteLocations');
    });
});

Route::group(['prefix' => 'driver'], function () {
    Route::post('login', 'Auth\DriverLoginController@requestLogin')->middleware(['guest']);
    Route::post('login/verify', 'Auth\DriverLoginController@verifyLogin')->middleware(['guest']);
    Route::post('login/resend', 'Auth\DriverLoginController@resendLogin')->middleware(['guest']);

    Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
        Route::get('/', 'DriverController@currentDriverProfile');
        Route::put('update', 'DriverController@updateProfile');
        Route::post('update-foto', 'DriverController@updateProfilePicture');
        Route::post('toggle-order', 'DriverController@toggleOrder');
        Route::post('panic', 'DriverController@notifyPanic');
        Route::post('photo-driver', 'DriverController@uploadPhotoDriver');
        Route::post('generate-income', 'EarningController@generateEarningByPeriode');
        Route::post('request-income', 'EarningController@requestIncome');
        Route::get('get-histories', 'EarningController@getAllHistories');
    });
});

Route::get('car-types', 'CarTypeController@getAllType')->middleware(['auth:sanctum']);

Route::group(['prefix' => 'order', 'middleware' => 'auth:sanctum'], function () {
    // trip-based order
    Route::group(['prefix' => 'trip'], function () {
        Route::middleware(['role:user'])->group(function () {
            Route::post('/', 'OrderController@createTripBasedOrder');
            Route::post('additional/{order}', 'OrderController@createAdditionalTripBasedOrder');
            Route::post('check', 'Order\OnTripController@calculatePrice');
        });
    });

    // Trip-Time Based
    Route::post('try/{order}', 'OrderController@researchDriver')->middleware('role:user');
    Route::post('cancel-search/{order}', 'OrderController@cancelSearchDriver')->middleware('role:user');
    Route::post('proceed/payment/{order}', 'OrderController@proceedPayment')->middleware('role:user');
    Route::get('detail/{order}', 'OrderController@getdetailOrder')->middleware('role:user|driver');
    Route::post('panic/{order}', 'UserController@notifyPanic')->middleware('role:user');
    Route::post('rating/{order}', 'RatingController@addRating')->middleware('role:user');
    Route::post('report/{order}', 'CrashReportController@addReport')->middleware('role:user');
    Route::post('receipt-upload/{order}', 'OrderController@uploadPaymentReceipt')->middleware('role:user');
    Route::get('transaction/{order}', 'OrderController@checkTransaction');
    Route::post('cancel/{order}', 'OrderController@cancelOrder')->middleware('role:user');
    Route::post('trigger-finish/{order}', 'OrderController@triggerFinish')->middleware('role:user');
    Route::post('trigger-start/{order}', 'OrderController@triggerStart')->middleware('role:user');

    // History
    Route::get('finished', 'OrderController@getFinishedOrders')->middleware('role:user|driver');
    Route::get('ongoing', 'OrderController@getOngoingOrders')->middleware('role:user|driver');
    Route::get('history', 'OrderController@getOrdersHistory')->middleware('role:user|driver');

    Route::middleware(['role:driver'])->group(function () {
        Route::post('accept/{order}', 'OrderController@acceptOrder');
        Route::post('decline/{order}', 'OrderController@declineOrder');
        Route::post('init/{order}', 'OrderReportController@initOrder');
        Route::post('finish/{order}', 'OrderReportController@finishOrder');
    });

    // time-based order
    Route::group(['prefix' => 'time'], function () {
        Route::middleware(['role:user'])->group(function () {
            Route::post('/', 'OrderController@createTimeBasedOrder');
            Route::post('additional/{order}', 'OrderController@createAdditionalTimeBasedOrder');
            Route::post('check', 'Order\OnTimeController@calculatePrice');
        });
    });
});

// Review/Rating
Route::get('reviews', 'RatingController@listReviews')->middleware(['auth:sanctum', 'role:driver']);

// Live Chat
Route::group(['prefix' => 'chat','middleware' => ['auth:sanctum', 'role:user|driver']], function () {
    Route::get('conversation/{conversation}', 'ChatController@getMessageByConversationId');
    Route::post('message/{conversation}', 'ChatController@sendMessage');
});

// Notification
Route::group(['prefix' => 'notification', 'middleware' => 'auth:sanctum'], function () {
    Route::get('unread', 'NotificationController@getUnredNotification');
    Route::get('all', 'NotificationController@getAllNotification');
    Route::post('read/{notificationId}', 'NotificationController@markNotificationAsRead');
});

// TODO: remove this in production. development purpose only.
Route::post('devlogin', 'Auth\LoginController@developerLogin')->middleware('guest');
