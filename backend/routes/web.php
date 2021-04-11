<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index')->name('home');

    // リスト追加
    Route::get('/setlists/create', 'SetlistController@showCreateForm')->name('setlists.create');
    Route::post('/setlists/create', 'SetlistController@create');
    // リスト編集
    Route::get('/setlists/{setlist}/edit', 'SetlistController@showEditForm')->name('setlists.edit');
    Route::post('/setlists/{setlist}/edit', 'SetlistController@edit');

    // リスト削除
    Route::delete('/setlists/{setlist}/delete', 'SetlistController@delete')->name('setlists.delete');
    // 検索
    Route::get('/setlists/{setlist}/songs/search', 'SongController@search')->name('songs.search');

    Route::group(['middleware' => 'can:view,setlist'], function () {
        Route::get('/setlists/{setlist}/songs', 'SongController@index')->name('songs.index');


        // 曲追加
        Route::get('/setlists/{setlist}/songs/create', 'SongController@showCreateForm')->name('songs.create');
        Route::post('/setlists/{setlist}/songs/create', 'SongController@create');

        // 曲編集
        Route::get('/setlists/{setlist}/songs/{song}/edit', 'SongController@showEditForm')->name('songs.edit');
        Route::post('/setlists/{setlist}/songs/{song}/edit', 'SongController@edit');

        // 削除
        Route::delete('/setlists/{setlist}/songs/{song}/delete', 'SongController@delete')->name('songs.delete');
        
    });
});

Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}');
    Route::get('/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('{provider}.callback');
});
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{provider}', 'Auth\RegisterController@showProviderUserRegistrationForm')->name('{provider}');
    Route::post('/{provider}', 'Auth\RegisterController@registerProviderUser')->name('{provider}');
});

Auth::routes();