<?php

use RoachPHP\Roach;
use App\Models\Post;
use App\Spiders\SunaSpider;
use Illuminate\Support\Facades\Route;

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
    Roach::startSpider(SunaSpider::class);

    dd(Post::all());
});
