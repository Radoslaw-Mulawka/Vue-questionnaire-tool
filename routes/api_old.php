<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Accept, Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');

/** Public routs **/
Route::get('/poll/{code}/{preview?}', 'Api\PollController@poll')->where('preview', '^preview$');
Route::post('/poll/{code}/store', 'Api\PollController@store');
Route::get('/verify/{hash}', 'Auth\RegisterController@verify');
Route::post('/oauth/token', [
  'uses' => 'Api\CustomAccessTokenController@issueUserToken'
]);

/** Only our clients can use this routs **/
Route::middleware('client.credentials')->group(function () {
  Route::post('/register', 'Auth\RegisterController@register');
  Route::post('/sendagain', 'Auth\RegisterController@sendagain');
  Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
  Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
});


/** Authenticated routs **/
Route::middleware('auth:api')->group(function () {

  Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
  Route::get('/dashboard', 'Api\CampaignsController@dashboard')->name('dashboard');

  /* Campaigns */
  Route::get('campaigns', 'Api\CampaignsController@index')->name('campaigns.index');
  Route::post('campaigns', 'Api\CampaignsController@store')->name('campaigns.store');
  Route::get('campaigns/{campaign}', 'Api\CampaignsController@show')->name('campaigns.show');
  Route::put('campaigns/{campaign}', 'Api\CampaignsController@update')->name('campaigns.update');
  Route::delete('campaigns/{campaign}', 'Api\CampaignsController@destroy')->name('campaigns.destroy');
  Route::put('campaigns/{campaign}/publish/{status}', 'Api\CampaignsController@publish')->name('campaigns.publish');
  Route::get('campaigns/{campaign}/places/{all?}', 'Api\CampaignsController@places')->name('campaign.places');
  Route::post('campaigns/{campaign}/assignplaces', 'Api\CampaignsController@assignplaces')->name('campaigns.assignplaces');
  Route::delete('campaigns/{campaign}/unassignplaces', 'Api\CampaignsController@unassignplaces')->name('campaigns.unassignplaces');
  Route::get('campaigns/{campaign}/preview', 'Api\PollController@preview')->name('campaigns.preview');
  
  /* Questions */
  Route::get('campaigns/{campaign}/questions', 'Api\QuestionsController@index')->name('questions.index');
  Route::post('campaigns/{campaign}/questions', 'Api\QuestionsController@store')->name('questions.store');
  Route::get('campaigns/{campaign}/questions/{question}', 'Api\QuestionsController@show')->name('questions.show');
  Route::put('campaigns/{campaign}/questions/{question}', 'Api\QuestionsController@update')->name('questions.update');
  Route::delete('campaigns/{campaign}/questions/{question}', 'Api\QuestionsController@destroy')->name('questions.destroy');
  Route::post('campaigns/{campaign}/questions/reorder', 'Api\QuestionsController@reorder')->name('questions.reorder');

  /* Options */
  Route::get('campaigns/{campaign}/questions/{question}/options', 'Api\OptionsController@index')->name('options.index');
  Route::post('campaigns/{campaign}/questions/{question}/options', 'Api\OptionsController@store')->name('options.store');
  Route::get('campaigns/{campaign}/questions/{question}/options/{option}', 'Api\OptionsController@show')->name('options.show');
  Route::put('campaigns/{campaign}/questions/{question}/options/{option}', 'Api\OptionsController@update')->name('options.update');
  Route::delete('campaigns/{campaign}/questions/{question}/options/{option}', 'Api\OptionsController@destroy')->name('options.destroy');

  // Get all option types from dictionary
  Route::get('optiontypes', 'Api\OptionsController@optiontypes')->name('options.optiontypes');

  /* Places */
  Route::get('places', 'Api\PlacesController@index')->name('places.index');
  Route::post('places', 'Api\PlacesController@store')->name('places.store');
  Route::get('places/{place}', 'Api\PlacesController@show')->name('places.show');
  Route::put('places/{place}', 'Api\PlacesController@update')->name('places.update');
  Route::delete('places/{place}', 'Api\PlacesController@destroy')->name('places.destroy');

  /* Profile */
  Route::get('profile', 'Api\ProfileController@show')->name('profile.show');
  Route::put('profile', 'Api\ProfileController@update')->name('places.update');
  Route::delete('profile', 'Api\ProfileController@destroy')->name('places.destroy');
  Route::put('profile/changepassword', 'Api\ProfileController@changepassword')->name('places.changepassword');
  
  
  Route::get('answers/{campaign}', 'Api\AnswersController@show')->name('answers.show');
  Route::get('answers/{campaign}/places', 'Api\AnswersController@placeslist')->name('answers.placeslist');
  Route::get('answers/{campaign}/places/{place}', 'Api\AnswersController@place')->name('answers.place');
});

