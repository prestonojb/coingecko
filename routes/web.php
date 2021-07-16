<?php

use Illuminate\Support\Facades\Route;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

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

Route::view('/', 'landing')->name('/');

Route::middleware('auth')->get('/favourites', function () {
    if(auth()->check()) {
        $user = auth()->user();
        $favourite_coins = $user->favourite_coins();
    }
    return view('favourites', compact('favourite_coins'));
})->name('favourites');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('coins/favourite', function(){
    $coin_id = request('coin_id');
    $user = auth()->user();

    $user->toggleFavouriteCoin($coin_id);
    return 'OK';
});
