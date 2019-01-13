<?php
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
Route::get('/', function () {
    return view('login');
});
Route::get('/main/signUp', 'MainController@signUp');
Route::post('/main/register', 'MainController@register');
Route::get('/main', 'MainController@index');
Route::post('/main/checklogin', 'MainController@checklogin');
Route::get('main/successlogin', 'MainController@successlogin');
Route::get('main/logout', 'MainController@logout');
Route::get('main/homePage', 'MainController@homePage');
Route::get('main/message', 'MainController@message');
Route::get('main/message/{id}', 'MainController@getMessages');
Route::get('main/discussionDetail/{id}', 'MainController@discussionDetail');
Route::get('main/comments/{id}', 'MainController@getComments');
Route::get('main/delete', 'MainController@deleteDiscussion');
Route::post('main/addDiscussion', 'MainController@addDiscussion');
Route::post('main/addPost', 'MainController@addPost');
Route::get('main/deletePost', 'MainController@deletePost');
Route::post('main/addComment', 'MainController@addComment');
Route::get('main/deleteComment', 'MainController@deleteComment');
Route::post('main/message/sendMessage', 'MainController@sendMessage');
Route::get('main/profile', 'MainController@profile');
Route::get('main/publisher', 'MainController@publisher');
Route::get('main/printinghouse', 'MainController@printinghouse');
Route::get('main/books', 'MainController@books');
Route::get('main/quotes', 'MainController@quotes');
Route::get('main/stores', 'MainController@stores');
Route::get('main/deleteFriend', 'MainController@deleteFriend');
Route::get('main/deleteBookFromProfile', 'MainController@deleteBookFromProfile');
Route::post('main/updateBookDate', 'MainController@updateBookDate');
Route::get('main/addBookMyProfile', 'MainController@addBookMyProfile');
Route::post('main/newBook', 'MainController@newBook');
Route::post('main/addPrintingHouse', 'MainController@addPrintingHouse');
Route::post('main/addPublisher', 'MainController@addPublisher');
Route::post('main/addStore', 'MainController@addStore');
Route::get('main/deleteStore', 'MainController@deleteStore');
Route::post('main/updateStore', 'MainController@updateStore');
Route::get('main/deletePrintingHouse', 'MainController@deletePrintingHouse');
Route::post('main/updatePrintingHouse', 'MainController@updatePrintingHouse');
Route::post('main/updatePublisher', 'MainController@updatePublisher');