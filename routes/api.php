<?php

use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfficeApiController;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
  // Permissions
  Route::apiResource('permissions', 'PermissionsApiController');

  // Roles
  Route::apiResource('roles', 'RolesApiController');

  // Users
  Route::apiResource('users', 'UsersApiController');
});
Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
});

Route::group(['middleware' => ['cors']], function() {

    Route::post('blogs', [App\Http\Controllers\Api\BlogApiController::class, 'index']);
    Route::post('blog', [App\Http\Controllers\Api\BlogApiController::class, 'blogsingle']);
    Route::get('filters', [App\Http\Controllers\Api\FilterProductApiController::class, 'filters']);
    Route::get('filters-bk', [App\Http\Controllers\Api\FilterProductApiController::class, 'filters_bk']);
    Route::post('office_list', [App\Http\Controllers\Api\OfficeApiController::class, 'index']);
    Route::post('office_single', [App\Http\Controllers\Api\OfficeApiController::class, 'singleoffice']);
    Route::post('search-property', [App\Http\Controllers\Api\OfficeApiController::class, 'searchoffice']);
    Route::post('single-property', [App\Http\Controllers\Api\OfficeApiController::class, 'singleProperty']);
    Route::post('wishlists', [App\Http\Controllers\Api\WishlistApiController::class, 'index']);
    Route::post('add-to-wishlist', [App\Http\Controllers\Api\WishlistApiController::class, 'addtowishlist']);
    Route::post('remove-from-wishlist', [App\Http\Controllers\Api\WishlistApiController::class, 'removefromwishlist']);
    Route::post('user-login', [App\Http\Controllers\Api\UserApiController::class, 'login']);
    Route::post('user-logout', [App\Http\Controllers\Api\UserApiController::class, 'logout']);
    Route::post('change-password', [App\Http\Controllers\Api\UserApiController::class, 'changepassword']);
    Route::post('update-image', [App\Http\Controllers\Api\UserApiController::class, 'uploadProfilePicture']);
    Route::post('update-user', [App\Http\Controllers\Api\UserApiController::class, 'updateuser']);
    Route::post('online-status', [App\Http\Controllers\Api\UserApiController::class, 'useronlinestatus']);
    Route::post('get-online-status', [App\Http\Controllers\Api\UserApiController::class, 'getonlinestatus']);
    Route::post('social-login', [App\Http\Controllers\Api\UserApiController::class, 'socialLogin']);
    Route::post('forget-password', [App\Http\Controllers\Api\UserApiController::class, 'forgetpassword']);
    Route::post('send-forget-otp', [App\Http\Controllers\Api\UserApiController::class, 'sendforgetotp']);
    Route::post('verify-forget-otp', [App\Http\Controllers\Api\UserApiController::class, 'verifyforgetotp']);
    Route::post('notifications', [App\Http\Controllers\Api\UserApiController::class, 'sendNotification']);
    Route::post('list-notifications', [App\Http\Controllers\Api\UserApiController::class, 'getNotification']);
    Route::post('user-register', [App\Http\Controllers\Api\UserApiController::class, 'register']);
    Route::post('user-inactive', [App\Http\Controllers\Api\UserApiController::class, 'deleteUser']);
    Route::post('question-answer-list', [App\Http\Controllers\Api\QuestionApiController::class, 'index']);
    Route::post('book-space', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'create']);

    Route::post('confirm-and-pay-detail', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'confirmAndPayDetails']);
    Route::post('my-account', [App\Http\Controllers\Api\UserApiController::class, 'myaccount']);
    Route::post('send-otp', [App\Http\Controllers\Api\UserApiController::class, 'sendOTP']);
    Route::post('verify-otp', [App\Http\Controllers\Api\UserApiController::class, 'verifyOTP']);
    Route::post('region-list', [App\Http\Controllers\Api\OfficeApiController::class, 'officelocations']);
    Route::post('terms', [App\Http\Controllers\Api\SettingsApiController::class, 'termsconditions']);
    Route::post('header-content', [App\Http\Controllers\Api\HomePageApiController::class, 'headerContent']);
    Route::post('safe-working', [App\Http\Controllers\Api\HomePageApiController::class, 'safeworking']);
    Route::post('tab-section', [App\Http\Controllers\Api\HomePageApiController::class, 'tabsection']);
    Route::post('on-bording-process', [App\Http\Controllers\Api\OnBordingProcessApiController::class, 'onbordingprocess']);
    Route::post('add-property', [App\Http\Controllers\Api\OfficeApiController::class, 'registeroffice']);
    Route::post('add-space', [App\Http\Controllers\Api\OfficeApiController::class, 'registerspace']);
    Route::post('get-property', [App\Http\Controllers\Api\OfficeApiController::class, 'getProperty']);
    //get-user-property
    Route::post('get-properties', [App\Http\Controllers\Api\OfficeApiController::class, 'getuserallProperty']);
    Route::post('get-space', [App\Http\Controllers\Api\OfficeApiController::class, 'getspace']);
    Route::post('get-spaces', [App\Http\Controllers\Api\OfficeApiController::class, 'getpropertallspace']);
    Route::post('space-attr-list', [App\Http\Controllers\Api\OfficeApiController::class, 'officeAttributes']);
    Route::post('blog-web', [App\Http\Controllers\Api\BlogApiController::class, 'blogforweb']);
    Route::post('plans', [App\Http\Controllers\Api\PlanApiController::class, 'planlist']);
    Route::post('avaliable-date', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'avaliableDate']);
    Route::post('edit-avaliable-date', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'editavaliableDate']);
    Route::post('edit-booking', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'editBooking']);
    Route::post('booking-payment', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'bookingpayment']);
    Route::post('enquiry-payment', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'bookingInquirypayment']);
    Route::post('my-booking', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'mybooking']);
    Route::post('landload-bookings', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'landloadbooking']);
    Route::post('single-booking', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'singlebooking']);
    Route::post('cancel-booking', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'canclebooking']);
    Route::post('booking-actions', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'bookingActions']);
    Route::post('property-actions', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'propertyActions']);
    Route::post('space-actions', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'spaceActions']);

    Route::post('register-staff', [App\Http\Controllers\Api\StaffApiController::class, 'staffRegister']);
    Route::post('edit-staff', [App\Http\Controllers\Api\StaffApiController::class, 'staffedit']);
    Route::post('get-staff', [App\Http\Controllers\Api\StaffApiController::class, 'staffget']);
    Route::post('login-staff', [App\Http\Controllers\Api\StaffApiController::class, 'staffLogin']);
    Route::post('delete-staff', [App\Http\Controllers\Api\StaffApiController::class, 'staffDelete']);
    Route::post('delete-property', [App\Http\Controllers\Api\OfficeApiController::class, 'deletePropery']);
    Route::post('delete-space', [App\Http\Controllers\Api\OfficeApiController::class, 'deleteSpace']);
    Route::post('invoice', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'invoice']);
    Route::post('strip-test', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'striptest']);
    Route::post('booking-enquiry', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'bookingenquiry']);
    Route::post('pages', [App\Http\Controllers\Api\PageApiController::class, 'pages']);
    Route::post('retrive-card', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'retriveCard']);
    Route::post('signature', [App\Http\Controllers\Api\OfficeBookingApiController::class, 'singnature']);
    Route::post('all-cards', [App\Http\Controllers\Api\HomePageApiController::class, 'allCardsList']);
    Route::post('delete-card', [App\Http\Controllers\Api\HomePageApiController::class, 'deleteCard']);
    Route::post('add-card', [App\Http\Controllers\Api\HomePageApiController::class, 'addCard']);
    Route::post('buy-plan', [App\Http\Controllers\Api\HomePageApiController::class, 'buyPlan']);
    Route::post('get-user-info', [App\Http\Controllers\Api\HomePageApiController::class, 'getUserInfo']);
    Route::post('add-rating', [App\Http\Controllers\Api\RatingApiController::class, 'create']);
    Route::post('web-settings', [App\Http\Controllers\Api\WebSettingApiController::class, 'index']);

    Route::post('strip-account-callback', [App\Http\Controllers\Api\StripeApiController::class, 'createStripAccountCallback']);
    Route::post('create-invoice', [App\Http\Controllers\Api\StripeApiController::class, 'createInvoice']);


    Route::post('user-notifications', [App\Http\Controllers\Api\HomePageApiController::class, 'userNotification']);


    Route::post('verify-user', [App\Http\Controllers\Api\UserApiController::class, 'verifyUserByToken']);

});


Route::group(['namespace' => 'Api', 'middleware' => ['apitoken']], function () {
    // Route::get('blogs/{skip?}', 'BlogApiController@index');
    // Route::get('blogs-type', 'BlogApiController@stickyblog');
    // Route::get('blog/{slug}', 'BlogApiController@show');
    Route::resource("blog-category", 'BlogCategoryController');
    Route::resource('app-setting', 'AppSetting');
    //Route::post('send-otp', "UserApiController@sendOTP");
    //Route::post('verify-otp', "UserApiController@verifyOTP");
    Route::post('user-info', 'UserApiController@userInfo');
    // Route::post('user-login', 'UserApiController@login');
    // Route::post('user-register', 'UserApiController@register');
    Route::resource('membership', "MembershipController");
    //Route::get('filters', "FilterProductApiController@filters");
    //Route::get('office_list', 'OfficeApiController@index');
    //Route::get('office_single/{id}', 'OfficeApiController@singleoffice');
    // Route::post('remove-from-wishlist', 'WishlistApiController@removefromwishlist');
    // Route::post('add-to-wishlist', 'WishlistApiController@addtowishlist');
});


    Route::post("property", [OfficeApiController::class, "property"]);
    Route::post("propertysingle", [OfficeApiController::class, "propertysingle"]);
    Route::post("space-type", [OfficeApiController::class, "spacetype"]);

    Route::post("property_space", [OfficeApiController::class, "propertyspace"]);
    Route::post("propertysingle_space", [OfficeApiController::class, "propertysinglespace"]);
