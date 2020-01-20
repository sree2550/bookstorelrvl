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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('insert1/',  'BooksController@store');
Route::post('insert2/',  'BooksController@districtstore');
Route::get('getDistricts/', 'BooksController@fetchDistricts');
Route::post('insert3/', 'BooksController@categorystore');
Route::post('insert4/', 'BooksController@subcategorystore');
Route::get('getCategory/',  'BooksController@fetchCategory');
Route::post('login/', 'BooksController@loginfunction');
Route::post('addbook/',  'BooksController@storebook');
Route::get('showbooks/', 'BooksController@viewbook');
Route::get('showusers/', 'BooksController@viewuser');

Route::get('booksupdate/{id}','BooksController@booksedit');

Route::patch('editdetails/{id}','BooksController@editdata');

Route::delete('deletebook/{id}','BooksController@deletebooks');

Route::get('reject-user/{eid}','BooksController@userreject');
Route::get('showallbooks/', 'BooksController@listbooks');
Route::get('selectCategory/{id}',  'BooksController@listselbooks');

Route::get('chooseCategory/{id}',  'BooksController@listchoosebooks');
Route::post('insertcart/',  'BooksController@addtocart');

Route::post('incre_qty/', 'BooksController@fn_incre_item_qty');
Route::post('decrmnt_qty/', 'BooksController@fn_decrmnt_qty');

Route::get('showcart/', 'BooksController@fn_view_cart');

Route::delete('deletecart/{id}','BooksController@fn_delete_cart');

Route::post('payment/', 'BooksController@fn_payment_add');
Route::post('paydelivery/', 'BooksController@fn_pay_delivery');
Route::get('showorder/', 'BooksController@fn_vieworder');
Route::get('customerdata/{id}', 'BooksController@fn_adminviewcustomer');
Route::get('bookdata/{id}', 'BooksController@fn_adminviewbook');
Route::get('paymentdata/{id}', 'BooksController@fn_adminviewpayment');
Route::get('usermessage/{id}', 'BooksController@fn_usermessage');
Route::get('searchBooks/', 'BooksController@listbooks');






Route::get('confirm-order/{eid}','BooksController@fn_orderconfirm');






















