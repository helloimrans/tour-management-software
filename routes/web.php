<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourScheduleController;
use App\Http\Controllers\Admin\MemberManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\ValidationController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

//Clear Cache
Route::get('clear', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('storage:link');
    return 'Cache Cleared Successfully'; //Return anything
});

//Remote validation
Route::post('check-availability', [ValidationController::class, 'checkAvailability'])->name('check-availability')->middleware('auth');
Route::post('check-old-password', [ValidationController::class, 'checkOldPassword'])->name('check-old-password')->middleware('auth');


Route::group(['middleware' => ['web']], function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/tours', [LandingController::class, 'tourListing'])->name('tours.listing');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::get('/member/register', [MemberController::class, 'showRegistrationForm'])->name('member.show.register');
    Route::post('/member/register', [MemberController::class, 'register'])->name('member.register');
});

Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
});

Route::group(['prefix' => 'notification', 'as' => 'notification.', 'middleware' => ['auth']], function () {
    Route::get('/get-all-notifications', [NotificationController::class, 'getAllNotifications'])->name('get.all.notifications');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark.all.as.read');
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark.as.read');
    Route::post('/remove/{id}', [NotificationController::class, 'remove'])->name('remove');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {

    //Role permission start
    Route::get('roles/{role}/permissions', [RoleController::class, 'rolePermissionIndex'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [RoleController::class, 'rolePermissionSync'])->name('roles.permission-sync');
    Route::get('roles/datatable', [RoleController::class, 'getDatatable'])->name('roles.datatable');
    Route::get('permissions/datatable', [PermissionController::class, 'getDatatable'])->name('permissions.datatable');

    Route::resources([
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
    ]);

     //Role permission end

    //Global status change
    Route::post('/change-status', [StatusController::class, 'changeStatus'])->name('change.status');

    Route::get('/setting', [SettingsController::class, 'index'])->name('setting');
    Route::post('/setting/update', [SettingsController::class, 'update'])->name('setting.update');

    //Profile edit start
    Route::get('/edit-profile', [UserController::class, 'editProfile'])->name('edit.profile');
    Route::post('/update-profile-otp-send', [UserController::class, 'updateProfileOtpSend'])->name('update-profile-otp-send');
    Route::post('/update-profile-otp-verify', [UserController::class, 'updateProfileOtpVerify'])->name('update-profile-otp-verify');
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
    Route::put('/update-password', [UserController::class, 'updatePassword'])->name('update.password');

    Route::resource('general-users', UserController::class)->names('general.user');
    Route::post('general-users/{id}/approve', [UserController::class, 'approve'])->name('general.user.approve');
    Route::post('general-users/{id}/assign-role', [UserController::class, 'assignRole'])->name('general.user.assign-role');
    Route::resource('admin-users', AdminUserController::class)->names('admin.user');

    Route::resource('tours', TourController::class)->names('tour');

    // Tour Schedule Management
    Route::get('/tour-schedule/{tourId}', [TourScheduleController::class, 'index'])->name('tour.schedule.index');
    Route::post('/tour-schedule', [TourScheduleController::class, 'store'])->name('tour.schedule.store');
    Route::put('/tour-schedule/{id}', [TourScheduleController::class, 'update'])->name('tour.schedule.update');
    Route::delete('/tour-schedule/{id}', [TourScheduleController::class, 'destroy'])->name('tour.schedule.destroy');

    // Member Management
    Route::get('/member-management', [MemberManagementController::class, 'index'])->name('member-management.index');
    Route::post('/member-management/add-to-tour', [MemberManagementController::class, 'addToTour'])->name('member-management.add-to-tour');
    Route::put('/member-management/{id}', [MemberManagementController::class, 'update'])->name('member-management.update');
    Route::delete('/member-management/{id}', [MemberManagementController::class, 'destroy'])->name('member-management.destroy');

});

Route::group(['prefix' => 'member', 'as' => 'member.', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MemberController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [MemberController::class, 'updateProfile'])->name('profile.update');
    Route::get('/tours', [MemberController::class, 'tours'])->name('tours');
    Route::post('/join-tour/{tourId}', [MemberController::class, 'joinTour'])->name('join-tour');
    Route::get('/current-tour', [MemberController::class, 'currentTour'])->name('current-tour');
    Route::get('/tour-history', [MemberController::class, 'tourHistory'])->name('tour-history');
    Route::get('/add-payment', [MemberController::class, 'showPaymentForm'])->name('add-payment');
    Route::post('/add-payment', [MemberController::class, 'addPayment'])->name('payment.store');
    Route::get('/payment-history', [MemberController::class, 'paymentHistory'])->name('payment-history');
});


