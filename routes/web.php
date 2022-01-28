<?php

use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Dcblogdev\Xero\Facades\Xero;
use Dcblogdev\Xero\Models\XeroToken;

Route::redirect('/', '/login');
// Route::redirect('/home', '/deskbooking/public/admin');
Auth::routes(['register' => false]);
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
  Route::get('/', 'HomeController@index')->name('home');
  // Permissions
  Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
  Route::resource('permissions', 'PermissionsController');
  // Roles
  Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
  Route::resource('roles', 'RolesController');
  // Users
  Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
  Route::resource('users', 'UsersController');

  Route::resource('categories', 'CategoriesController');
  Route::get('destroy-category/{id}', 'CategoriesController@destroy');
  Route::get('change-category-status/{id}', 'CategoriesController@changeStatus');
  Route::resource('pages', 'PagesController');
  Route::resource('tax', 'TaxController');
  Route::get('change-tax-status/{id}', 'TaxController@changeStatus');

  Route::resource('selling-zone', 'SellingZoneController');
  Route::get('get-state/{id}', 'SellingZoneController@getState');
  Route::get('change-selling-status/{id}', 'SellingZoneController@changeStatus');
  Route::get('get-city/{id}', 'SellingZoneController@getCity');

  Route::resource('home', 'HomeController');
  Route::get('load-page', 'HomeController@loadPage')->name('load-page');
  Route::get('add-module', 'HomeController@addModule')->name('add-module');
  Route::get('change-home-page-status/{id}/{st}', 'HomeController@changeStatus')->name('change-home-page-status');

  Route::resource('mail-template', "MailTemplate");
  Route::resource('setting', "SettingController");

  //tiny
  Route::post('upload', 'BlogController@upload');

  //home page settings
  Route::resource('home-setting', "HomeSettingsController");

  //Notifications
  Route::resource('notifications', "NotificationsController");

  Route::resource('attribute', 'AttributeController');
  Route::get('verifymail', 'AttributeController@verify')->name('verifymail');
  Route::resource('attribute-value', 'AttributeValueController');
  Route::get('get-sub-attribute/{id}', 'AttributeValueController@getSubAttribute');

  Route::post('fetch-attribute-value', 'AttributeValueController@fetchAttributeValue')->name('fetch-attribute-value');
  Route::post('get-attribute-value-data', 'AttributeValueController@getAttributeData');
  Route::resource('currency-exchange', 'CurrencyExchangeController');
  Route::resource('newsletter', 'NewsletterController');
  Route::resource('currency-exchange-rate', 'CurrencyExchangeRateController');
  Route::get('make-default-currency/{id}/{st}', 'CurrencyExchangeController@makeDefaultCurrency');

  Route::resource('stripe-setup', "StripePaymentController");
  Route::get('change-chimp-status/{id}/{st}', 'NewsletterController@changeStatus');
  Route::get('change-payment-status/{id}/{st}', 'StripePaymentController@changeStatus');
  Route::resource('coupon', 'CouponController');
  Route::resource('giftcard', 'GiftCardController');
  Route::post('/', 'HomeController@getPerformance');

  Route::resource('office', 'OfficeController');
  Route::get('user-property/{user_id}', [App\Http\Controllers\Admin\OfficeController::class, 'userproperty'])->name('user-property');
  Route::get('search-property', [App\Http\Controllers\Admin\OfficeController::class, 'searchProperty'])->name('search-property');
  Route::get('property-filters', [App\Http\Controllers\Admin\OfficeController::class, 'propertyFilters'])->name('property-filters');
  Route::post("change-status", "OfficeController@changeStatus")->name('change-status');
  Route::resource('space', 'SpaceController');
  Route::post('change-space-stauts', [App\Http\Controllers\Admin\SpaceController::class, 'changeSpaceStatus'])->name('change-space-stauts');
  Route::get('search-space', [App\Http\Controllers\Admin\SpaceController::class, 'searchSpace'])->name('search-space');
  Route::get('show-space/{id}', 'OfficeController@showspace')->name('show-space');
  Route::get('filter-space-by-property', [App\Http\Controllers\Admin\SpaceController::class, 'filerSpaceByProperty'])->name('filter-space-by-property');
  Route::get('filter-space-by-type', [App\Http\Controllers\Admin\SpaceController::class, 'filerSpaceByType'])->name('filter-space-by-type');
  Route::resource('desk-type', 'DeskTypeController');
  Route::get('change-desk-status/{id}/{st}', 'DeskTypeController@changeStatus');
  Route::get('coupon-mail/{id}', 'CouponController@couponMail');
  Route::resource('blog', 'BlogController');
  Route::get('blog-status-change/{id}', 'BlogController@changeStatus')->name('blog-status-change');
  Route::get('search-blog', [App\Http\Controllers\Admin\BlogController::class, 'searchBlog'])->name('search-blog');
  Route::get('filter-blog', [App\Http\Controllers\Admin\BlogController::class, 'filterBlog'])->name('filter-blog');
  Route::get('date-blog', [App\Http\Controllers\Admin\BlogController::class, 'dateBlog'])->name('date-blog');
  Route::resource('bookings', 'BookingController');
  Route::post("change-booking-status", "BookingController@changeBookingStatus")->name('change-booking-status');
  Route::get("booking-invoice/{id}", "BookingController@invoice")->name('booking-invoice');
  Route::resource('space', 'SpaceController');
  Route::resource("blog-category", "BlogCategoryController");
  Route::resource("plan", "PlanController");
  Route::resource("plan-feature", "PlanFeatureController");
  Route::get("change-blogcat-status/{id}/{st}", "BlogCategoryController@changeStatus");
  Route::resource('membership', "MembershipController");
  Route::get('change-subscription-status/{id}/{st}', "MembershipController@changeStatus")->name('change-subscription-status');

 
  Route::get("single-booking/{id}", "BookingController@singleBooking")->name('single-booking');
  Route::get("remove-property-image/{id}", "OfficeController@removePropertyImage")->name('remove-property-image');
   
  Route::get('return-back', function () {
    echo '<script type="text/javascript">', 'history.go(-2);', '</script>';
  });
});
Route::get('generate-pdf', "Admin\OrderController@invoicePdf")->name("generate-pdf");

  Route::get('email/verify/{token}', [App\Http\Controllers\Admin\UsersController::class, 'verifyUser']);
  Route::get('change/password/{id}', [App\Http\Controllers\Admin\UsersController::class, 'changepasswoord']);
  Route::post('update/password/', [App\Http\Controllers\Admin\UsersController::class, 'updatepasswoord'])->name('update-pass');;
Route::post('ckeditor/upload', [App\Http\Controllers\Admin\UsersController::class,'upload'])->name('ckeditor.upload');

Route::get('online-status/{id}', [App\Http\Controllers\Api\UserApiController::class, 'onlinestatus'])->name('online-status');


Route::group(['middleware' => ['web', 'XeroAuthenticated']], function(){
    Route::get('xero', function(){
        return Xero::get('contacts');
    });
});

Route::get('xero/connect', function(){
    return Xero::connect();
});

Route::post('upload-images', [App\Http\Controllers\Admin\OfficeController::class, 'uploadImages']);


Route::get('send-all-mails', function() {
  // 
  \Artisan::call('schedule:run');
  return response()->json(['msg'=>'cron run successfuly!', 'status'=>true], 200);
});