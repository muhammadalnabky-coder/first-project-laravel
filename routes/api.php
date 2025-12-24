<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AprtmentImagesController;
use App\Http\Controllers\BookingChangeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Approved And jwt
Route::group(['middleware' => ['auth:api', 'checkApproved']], function() {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('UserImages/{id}', [AuthController::class, 'updateProfile']);

    //favorites
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('/toggle', [FavoriteController::class, 'toggle']);
    });

    //messages
    Route::prefix('messages')->group(function () {
        Route::post('/send', [MessageController::class, 'send']);
        Route::get('/chat-with/{userId}', [MessageController::class, 'chatWith']);
    });

    //notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'list']);
        Route::post('/create', [NotificationController::class, 'create']);
        Route::post('/{id}/read', [NotificationController::class, 'markRead']);
    });

    //Apartment
    Route::prefix('apartments')->group(function () {
        Route::get('/', [ApartmentController::class, 'index']);
        Route::get('/{id}', [ApartmentController::class, 'show']);
        Route::post('/', [ApartmentController::class, 'store']);
        Route::post('/Update/{id}', [ApartmentController::class, 'update']);
        Route::delete('/Delete/{id}', [ApartmentController::class, 'destroy']);
        Route::post('search', [ApartmentController::class, 'search']);

        //images
        Route::prefix('images')->group(function () {
            Route::get('/{id}',[AprtmentImagesController::class, 'index']);
            Route::post('/{id}',[AprtmentImagesController::class, 'store']);
            Route::post('/Update/{id}',[AprtmentImagesController::class, 'update']);
            Route::delete('/{id}',[AprtmentImagesController::class, 'destroy']);
        });

        //rating
        Route::post('/rate', [RatingController::class, 'rate']);
        Route::get('/{id}/ratings', [RatingController::class, 'listByApartment']);
    });

    //booking
    Route::prefix('booking')->group(function () {
        Route::get('/', [BookingController::class, 'indexForUser']);
        Route::get('/{id}', [BookingController::class, 'bookingDetails']);
        Route::post('update/{id}', [BookingChangeController::class, 'updateBooking']);
        Route::post('/create', [BookingController::class, 'createBooking']);
        Route::post('/cancel/{id}', [BookingController::class, 'cancel']);
    });

    //Approved   ->middleware('check:')======================================================>>>>>
    Route::prefix('admin')->middleware('check:create-user')->group(function () {
        Route::get('/index', [AdminController::class, 'index']);
        Route::get('/pending', [AdminController::class, 'pendingUsers']);
        Route::get('/approved', [AdminController::class, 'approvedUsers']);
        Route::get('/rejected', [AdminController::class, 'rejectedUsers']);
        Route::post('/approve/{id}', [AdminController::class, 'approveUser']);
        Route::post('/reject/{id}', [AdminController::class, 'rejectUser']);
        Route::delete('/delete/{id}', [AdminController::class, 'deleteUser']);

        Route::get('/users/{id}/apartments', [RoleController::class, 'getUserApartments']);
        Route::get('/users/{id}/bookings', [RoleController::class, 'getUserBookings']);

        Route::prefix('booking')->group(function () {
            Route::get('/', [BookingController::class, 'index']);
            Route::post('/updateStatus/{id}', [BookingController::class, 'updateStatus']);
        });
    });
});



