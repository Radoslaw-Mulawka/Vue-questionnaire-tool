<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Repository\Transformers\CampaignPlacesTransformers;

class CampaignPlaces extends Model {

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['id_user', 'id_place', 'id_campaign', 'shortcode', 'public_name', 'label_name'];
  
  public $transformer = CampaignPlacesTransformers::class;

  public function campaign() {
    return $this->belongsTo('App\Models\Api\Campaigns', 'id_campaign');
  }
  
  public function place() {
    return $this->belongsTo('App\Models\Api\Places', 'id_place');
  }
  
      /**
   * Get a validator for a new request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validatorIdPlace(array $data) {
    Validator::extend('usersplace', function($field, $value, $parameters) {

      $place = Places::where('id', $value)->first();

      if (is_null($place)) {
        return false;
      }

      if ($place->id_user == auth()->user()->getAuthIdentifier()) {
        return true;
      }
      
      return false;
    });
    
    return Validator::make($data, [
        'id' => 'required|exists:places,id|usersplace'
    ]);
  }
}
