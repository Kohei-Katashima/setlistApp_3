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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/', 'HomeController@index')->name('home');
    
    
    Route::get('/setlists/{id}/songs', 'SongController@index')->name('songs.index');
    
    // リスト追加
    Route::get('/setlists/create', 'SetlistController@showCreateForm')->name('setlists.create');
    Route::post('/setlists/create', 'SetlistController@create');
    
    // 曲追加
    Route::get('/setlists/{id}/songs/create', 'SongController@showCreateForm')->name('songs.create');
    Route::post('/setlists/{id}/songs/create', 'SongController@create');
    
    // 曲編集
    Route::get('/setlists/{id}/songs/{song_id}/edit', 'SongController@showEditForm')->name('songs.edit');
    Route::post('/setlists/{id}/songs/{song_id}/edit', 'SongController@edit');
    
});