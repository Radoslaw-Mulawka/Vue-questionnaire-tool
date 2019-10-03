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

/** Public routs **/
Route::get('/poll/{code}/{preview?}', 'Api\PollController@poll')->where('preview', '^preview$');
Route::post('/poll/{code}/store', 'Api\PollController@store');

Route::get('/campaigns', 'CampaignController@index');

Route::group(['middleware' => 'api'], function () {
    Route::post('auth/login', 'Auth\LoginController@login');
    Route::post('auth/register', 'Auth\RegisterController@register');
    Route::get('/verify/{hash}', 'Auth\RegisterController@verify');
    Route::post('/sendagain', 'Auth\RegisterController@sendagain');
    Route::post('/password/forgotten', 'Auth\ForgottenPasswordController@forgotten');
    Route::get('/password/reset/{token}', 'Auth\ForgottenPasswordController@verify');
    Route::post('/password/reset', 'Auth\ForgottenPasswordController@reset');
    Route::post('/newsletter/subscribe', 'NewsletterController@subscribe');
    Route::post('/newsletter/unsubscribe/{hash}', 'NewsletterController@unsubscribe');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('auth/user', 'Auth\LoginController@user');
        Route::post('auth/logout', 'Auth\LoginController@logout');
    });

    Route::apiResource('users', 'UserController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);

    Route::apiResource('roles', 'RoleController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);

    Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);

    Route::post('/campaigns', 'CampaignController@store')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::get('/campaigns/{campaign}', 'CampaignController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::put('campaigns/{campaign}', 'CampaignController@update')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::delete('campaigns/{campaign}', 'CampaignController@destroy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::put('campaigns/{campaign}/status', 'CampaignController@updateStatus')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::put('campaigns/{campaign}/banner', 'CampaignController@updateBanner')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::delete('campaigns/{campaign}/banner', 'CampaignController@destroyBanner')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);


    Route::get('/questions', 'QuestionController@index')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::post('/questions', 'QuestionController@store')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::get('/questions/{question}', 'QuestionController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::put('/questions/{question}', 'QuestionController@update')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::delete('/questions/{question}', 'QuestionController@destroy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::get('/questions/{question}/copy', 'QuestionController@copy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);

    Route::get('/options', 'OptionController@index')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::post('/options', 'OptionController@store')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::get('/options/{option}', 'OptionController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::put('/options/{option}', 'OptionController@update')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);
    Route::delete('/options/{option}', 'OptionController@destroy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);

    Route::get('answers/{campaign}', 'AnswerController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_CAMPAIGN_MANAGE);

    Route::get('/profile', 'UserProfileController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_VIEW_MENU_CAMPAIGN);
    Route::put('/profile', 'UserProfileController@update')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_VIEW_MENU_CAMPAIGN);
    Route::put('/profile/banner', 'UserProfileController@updateBanner')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_VIEW_MENU_CAMPAIGN);
    Route::delete('/profile/banner', 'UserProfileController@destroyBanner')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_VIEW_MENU_CAMPAIGN);
    Route::delete('/profile', 'UserProfileController@destroy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_VIEW_MENU_CAMPAIGN);
    Route::put('/profile/changepassword', 'UserProfileController@changePassword')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_VIEW_MENU_CAMPAIGN);

});
