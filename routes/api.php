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
use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');

header('Content-Type: application/json; charset=UTF-8', true);


/** Start Auth Route **/

Route::middleware('auth:api')->group(function () {
    //Auth_private
    Route::prefix('Auth_private')->group(function()
    {
        Route::post('/change_password', 'UserController@change_password');
        Route::post('/edit_profile', 'UserController@edit_profile');
        Route::post('/check_password_code', 'UserController@check_password_code');
        Route::post('/check_active_code', 'UserController@check_active_code');
        Route::get('/my_info', 'UserController@my_info');
        Route::post('/reset_password', 'UserController@reset_password');
        Route::post('/logout', 'UserController@logout');
        Route::post('/resend_code', 'UserController@resend_code');
        Route::post('/change_lang', 'UserController@change_lang');
    });

    /** Address Routes */
    Route::prefix('Address')->group(function()
    {
        Route::post('/create', 'AddressController@create');
        Route::post('/update', 'AddressController@update');
        Route::post('/delete', 'AddressController@delete');
        Route::get('/all', 'AddressController@all');
        Route::get('/single', 'AddressController@single');
    });


    /** Favourite */
    Route::prefix('Favourite')->group(function()
    {
        Route::post('/addOrRemove', 'FavouriteController@addOrRemove');
        Route::get('/myFavourite', 'FavouriteController@myFavourite');
    });

    /** Favourite */
    Route::prefix('Cart')->group(function()
    {
        Route::post('/addToCart', 'CartController@addToCart');
        Route::get('/myCart', 'CartController@myCart');
        Route::post('/removeFromCart', 'CartController@removeFromCart');
    });

    /** Order Routes */
    Route::prefix('OrderUser')->group(function () {
        Route::post('/makeOrder', 'OrderController@makeOrder');
        Route::post('/applyDiscount', 'OrderController@applyDiscount');
        Route::post('/removeCode', 'OrderController@removeCode');
        Route::get('/myOrders', 'OrderController@myOrders');
        Route::get('/singleOrder', 'OrderController@singleOrder');
    });



});
/** End Auth Route **/

/** Auth_general */
Route::prefix('Auth_general')->group(function()
{
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
    Route::post('/forget_password', 'UserController@forget_password');
});

/** General Route */
Route::prefix('General')->group(function()
{
    Route::get('/home', 'GeneralController@home');
    Route::get('/setting', 'GeneralController@setting');
});

/** Product Route */
Route::prefix('Product')->group(function()
{
    Route::get('/products', 'ProductController@products');
    Route::get('/singleProduct', 'ProductController@singleProduct');
});
