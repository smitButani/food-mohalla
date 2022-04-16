<?php

use Illuminate\Http\Request;

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

Route::get('/', function(){
    return 'ready to call apis.';
});

// user Authentication Api routes
Route::post('/otp-generate','Api\UserController@tokenGenerate');
Route::post('/otp-verification','Api\UserController@verifyOtp')->name('login');
Route::post('/create-user','Api\UserController@userCreate')->name('Create');
Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function(){
    Route::get('/view-profile','Api\UserController@userDetails');
    Route::post('/update','Api\UserController@update');
    Route::post('/store-list-by-location', 'Api\ShopController@list');
});

//dashboard Api List
Route::get('best-offers/', 'Api\BestOfferController@list');
Route::post('grab-best-deals/', 'Api\GrabBestDealController@list');
Route::post('recommended/', 'Api\RecommandedController@list');
Route::post('category/', 'Api\CategoryController@list');
Route::post('product/', 'Api\ProductController@list');
Route::post('categories-wise-products/', 'Api\CategoryController@categoryWishProducts');
// Route::get('product-wise-variants/{productId}', 'Api\ProductController@productWiseVariants');
Route::get('product-customize-details/{productId}', 'Api\ProductController@productCustomizeDetails');
Route::post('product-search/', 'Api\ProductController@productSearch');
Route::get('products-details/{productId}', 'Api\ProductController@productDetails');

// Offer Api List
Route::get('offers/', 'Api\OfferController@list');

// Cart Api List
Route::post('/product-item-total', 'Api\CartController@productTotalCount');
Route::post('/add-to-cart', 'Api\CartController@addToCart')->middleware('auth:api');
Route::get('/get-cart-item', 'Api\CartController@getCart')->middleware('auth:api');
Route::post('/update-cart-item', 'Api\CartController@updateCart')->middleware('auth:api');
Route::post('/delete-cart-item', 'Api\CartController@deleteCartItem')->middleware('auth:api');
Route::post('/payment-page', 'Api\CartController@paymentPage')->middleware('auth:api');
Route::post('/check-promo', 'Api\CartController@checkPromocode')->middleware('auth:api');
Route::post('/create-order', 'Api\CartController@createOrder')->middleware('auth:api');
Route::post('/order-list', 'Api\CartController@orderList')->middleware('auth:api');
Route::post('/order-details', 'Api\CartController@orderDetails')->middleware('auth:api');
Route::post('/order-status-change', 'Api\CartController@orderStatusChange')->middleware('auth:api');
// Route::post('/order-lists', 'Api\CartController@orderLists')->middleware('auth:api');

//Address Api List
Route::group(['prefix' => 'user-address', 'middleware' => 'auth:api'], function(){
    Route::get('/', 'Api\UserAddressController@list');
    Route::get('/{id}', 'Api\UserAddressController@get_one');
    Route::post('/create', 'Api\UserAddressController@create');
    Route::post('/update/{id}', 'Api\UserAddressController@update');
    Route::delete('/delete/{id}', 'Api\UserAddressController@delete');
});

//shop
Route::group(['prefix' => 'shop'], function(){
    Route::get('/', 'Api\ShopController@list');
    Route::get('/{id}', 'Api\ShopController@get_one');
    Route::post('/create', 'Api\ShopController@create');
    Route::post('/update/{id}', 'Api\ShopController@update');
    Route::delete('/delete/{id}', 'Api\ShopController@delete');
});

// category
Route::group(['prefix' => 'category'], function(){
    Route::get('/{id}', 'Api\CategoryController@get_one');
    Route::post('/create', 'Api\CategoryController@create');
    Route::post('/update/{id}', 'Api\CategoryController@update');
    Route::delete('/delete/{id}', 'Api\CategoryController@delete');
});

// product
Route::group(['prefix' => 'product'], function(){
    // Route::get('/', 'Api\ProductController@list');
    Route::get('/{id}', 'Api\ProductController@get_one');
    Route::post('/create', 'Api\ProductController@create');
    Route::post('/update/{id}', 'Api\ProductController@update');
    Route::delete('/delete/{id}', 'Api\ProductController@delete');
});

// product-variant
Route::group(['prefix' => 'product-variant'], function(){
    Route::get('/', 'Api\ProductVariantController@list');
    Route::get('/{id}', 'Api\ProductVariantController@get_one');
    Route::post('/create', 'Api\ProductVariantController@create');
    Route::post('/update/{id}', 'Api\ProductVariantController@update');
    Route::delete('/delete/{id}', 'Api\ProductVariantController@delete');
});

// product-customize-type
Route::group(['prefix' => 'product-customize-type'], function(){
    Route::get('/', 'Api\ProductCustomizeTypeController@list');
    Route::get('/{id}', 'Api\ProductCustomizeTypeController@get_one');
    Route::post('/create', 'Api\ProductCustomizeTypeController@create');
    Route::post('/update/{id}', 'Api\ProductCustomizeTypeController@update');
    Route::delete('/delete/{id}', 'Api\ProductCustomizeTypeController@delete');
});

// product-customize-option
Route::group(['prefix' => 'product-customize-option'], function(){
    Route::get('/', 'Api\ProductCustomizeOptionController@list');
    Route::get('/{id}', 'Api\ProductCustomizeOptionController@get_one');
    Route::post('/create', 'Api\ProductCustomizeOptionController@create');
    Route::post('/update/{id}', 'Api\ProductCustomizeOptionController@update');
    Route::delete('/delete/{id}', 'Api\ProductCustomizeOptionController@delete');
});

// grab-best-deals
Route::group(['prefix' => 'grab-best-deals'], function(){
    Route::get('/{id}', 'Api\GrabBestDealController@get_one');
    Route::post('/create', 'Api\GrabBestDealController@create');
    Route::post('/update/{id}', 'Api\GrabBestDealController@update');
    Route::delete('/delete/{id}', 'Api\GrabBestDealController@delete');
});

// recommended
Route::group(['prefix' => 'recommended'], function(){
    // Route::get('/', 'Api\RecommandedController@list');
    Route::get('/{id}', 'Api\RecommandedController@get_one');
    Route::post('/create', 'Api\RecommandedController@create');
    Route::post('/update/{id}', 'Api\RecommandedController@update');
    Route::delete('/delete/{id}', 'Api\RecommandedController@delete');
});

// offers
Route::group(['prefix' => 'offers'], function(){
    Route::get('/{id}', 'Api\OfferController@get_one');
    Route::post('/create', 'Api\OfferController@create');
    Route::post('/update/{id}', 'Api\OfferController@update');
    Route::delete('/delete/{id}', 'Api\OfferController@delete');
});

// best-offers
Route::group(['prefix' => 'best-offers'], function(){
    Route::get('/{id}', 'Api\BestOfferController@get_one');
    Route::post('/create', 'Api\BestOfferController@create');
    Route::post('/update/{id}', 'Api\BestOfferController@update');
    Route::delete('/delete/{id}', 'Api\BestOfferController@delete');
});