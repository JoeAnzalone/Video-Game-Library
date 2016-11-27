<?php

use \App\GiantBombHelper;

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
    return view('welcome');
});

Route::get('/cover/{game}', function ($game_name) {
    $platforms = [GiantBombHelper::PLATFORM_PLAYSTATION, GiantBombHelper::PLATFORM_PLAYSTATION_2];
    $game_images = GiantBombHelper::getGameImage($game_name, $platforms);
    $cover_url = $game_images['medium_url'];
    echo '<img src="' . $cover_url . '">';
});

Auth::routes();

Route::get('/home', 'HomeController@index');
