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
    return view('welcome');
});

Route::get('/cover/{game}', function ($game_name) {
    $game_name = str_replace('+', ' ', $game_name);

    $platforms = [
        // \App\GiantBombHelper::PLATFORM_PLAYSTATION,
        // \App\GiantBombHelper::PLATFORM_PLAYSTATION_2,
        // \App\GiantBombHelper::PLATFORM_SUPER_NINTENDO_ENTERTAINMENT_SYSTEM,
    ];

    $regions = [\App\GiantBombHelper::REGION_UNITED_STATES];

    $game_images = \App\GiantBombHelper::getGameImage($game_name, $platforms, $regions);
    $cover_url = $game_images['medium_url'];
    echo '<img src="' . $cover_url . '">';
});

Auth::routes();

Route::get('/home', 'HomeController@index');
