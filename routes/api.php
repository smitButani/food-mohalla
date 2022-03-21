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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/otp-generate','Api\UserController@tokenGenerate');
Route::post('/otp-verification','Api\UserController@verifyOtp')->name('login');

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function(){
    Route::post('/update','Api\UserController@update');
});

Route::get('/', function(){
    return 'ready to call apis.';
})->middleware('auth:api');

Route::group(['prefix' => 'shop'], function(){
    Route::get('/', 'Api\ShopController@list');
    Route::get('/{id}', 'Api\ShopController@get_one');
    Route::post('/create', 'Api\ShopController@create');
    Route::post('/update/{id}', 'Api\ShopController@update');
    Route::delete('/delete/{id}', 'Api\ShopController@delete');
});

Route::group(['prefix' => 'category'], function(){
    Route::get('/', 'Api\CategoryController@list');
    Route::get('/{id}', 'Api\CategoryController@get_one');
    Route::post('/create', 'Api\CategoryController@create');
    Route::post('/update/{id}', 'Api\CategoryController@update');
    Route::delete('/delete/{id}', 'Api\CategoryController@delete');
});

Route::group(['prefix' => 'product'], function(){
    Route::get('/', 'Api\ProductController@list');
    Route::get('/{id}', 'Api\ProductController@get_one');
    Route::post('/create', 'Api\ProductController@create');
    Route::post('/update/{id}', 'Api\ProductController@update');
    Route::delete('/delete/{id}', 'Api\ProductController@delete');
});

Route::group(['prefix' => 'product-variant'], function(){
    Route::get('/', 'Api\ProductVariantController@list');
    Route::get('/{id}', 'Api\ProductVariantController@get_one');
    Route::post('/create', 'Api\ProductVariantController@create');
    Route::post('/update/{id}', 'Api\ProductVariantController@update');
    Route::delete('/delete/{id}', 'Api\ProductVariantController@delete');
});


Route::group(['prefix' => 'product-customize-type', 'middleware' => 'auth:api'], function(){
    Route::get('/', 'Api\ProductCustomizeTypeController@list');
    Route::get('/{id}', 'Api\ProductCustomizeTypeController@get_one');
    Route::post('/create', 'Api\ProductCustomizeTypeController@create');
    Route::post('/update/{id}', 'Api\ProductCustomizeTypeController@update');
    Route::delete('/delete/{id}', 'Api\ProductCustomizeTypeController@delete');
});

Route::group(['prefix' => 'product-customize-option'], function(){
    Route::get('/', 'Api\ProductCustomizeOptionController@list');
    Route::get('/{id}', 'Api\ProductCustomizeOptionController@get_one');
    Route::post('/create', 'Api\ProductCustomizeOptionController@create');
    Route::post('/update/{id}', 'Api\ProductCustomizeOptionController@update');
    Route::delete('/delete/{id}', 'Api\ProductCustomizeOptionController@delete');
});


Route::group(['prefix' => 'grab-best-deals', 'middleware' => 'auth:api'], function(){
    Route::get('/', 'Api\GrabBestDealController@list');
    Route::get('/{id}', 'Api\GrabBestDealController@get_one');
    Route::post('/create', 'Api\GrabBestDealController@create');
    Route::post('/update/{id}', 'Api\GrabBestDealController@update');
    Route::delete('/delete/{id}', 'Api\GrabBestDealController@delete');
});


Route::group(['prefix' => 'recommended', 'middleware' => 'auth:api'], function(){
    Route::get('/', 'Api\RecommandedController@list');
    Route::get('/{id}', 'Api\RecommandedController@get_one');
    Route::post('/create', 'Api\RecommandedController@create');
    Route::post('/update/{id}', 'Api\RecommandedController@update');
    Route::delete('/delete/{id}', 'Api\RecommandedController@delete');
});

Route::group(['prefix' => 'offers', 'middleware' => 'auth:api'], function(){
    Route::get('/', 'Api\OfferController@list');
    Route::get('/{id}', 'Api\OfferController@get_one');
    Route::post('/create', 'Api\OfferController@create');
    Route::post('/update/{id}', 'Api\OfferController@update');
    Route::delete('/delete/{id}', 'Api\OfferController@delete');
});

Route::group(['prefix' => 'user-address', 'middleware' => 'auth:api'], function(){
    Route::get('/', 'Api\UserAddressController@list');
    Route::get('/{id}', 'Api\UserAddressController@get_one');
    Route::post('/create', 'Api\UserAddressController@create');
    Route::post('/update/{id}', 'Api\UserAddressController@update');
    Route::delete('/delete/{id}', 'Api\UserAddressController@delete');
});


Route::get('product-wise-variants/{productId}', 'Api\ProductController@productWiseVariants');
Route::get('product-customize-details/{productId}', 'Api\ProductController@productCustomizeDetails');



