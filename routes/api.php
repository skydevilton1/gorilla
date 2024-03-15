<?php


use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrefixController;
use App\Http\Controllers\SocialLoginController;
use App\Models\Influencer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/login', [LoginController::class, 'login']);
Route::post('/Soclogin', [SocialLoginController::class, 'login']);



Route::group(['middleware' => 'checkjwt'], function () {
    //user
    Route::get('/get_user', [InfluencerController::class, 'getUser']);
    Route::resource('influencer', InfluencerController::class);
    Route::get('/user_profile', [InfluencerController::class, 'getProfileUser']);

    // Prefix
    Route::resource('prefix', PrefixController::class);
    Route::get('prefix_page', [PrefixController::class, 'Page']);
});
