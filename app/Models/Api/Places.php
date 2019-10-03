<?php

namespace App\Models\Api;

use App\Scopes\PlaceScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Repository\Transformers\PlacesTransformers;

class Places extends Model {

  public $transformer = PlacesTransformers::class;

  protected static function boot()
  {
      parent::boot();
      static::addGlobalScope(new PlaceScope);
  }

  /**
     * Get the places record associated with the campaign.
     */
    public function campaignPlaces()
    {
        return $this->hasMany('App\Models\Api\CampaignPlaces', 'id_place', 'id');
    }

    /**
   * MVC MODEL start from here
   */
  public function prepadeDataAndSave(Request $request, $userID, $new = false) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if(empty($data)){
      return false;
    }

    $validator = $this->validator($data['placeData']);
    if ($validator->fails()) {
      $data['placeData']['errors'] = $validator->errors();
      return $data;
    }

    $this->name = $data['placeData']['placeName'];
    $this->comment = isset($data['placeData']['placeComment']) ? $data['placeData']['placeComment'] : $this->comment;
    $this->id_user = $userID;
    $this->save();

    return true;
  }

    /**
   * Get a validator for a new request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data) {

    return Validator::make($data, [
        'placeName' => 'required|max:100',
        'placeComment' => 'nullable'
    ]);
  }

  /**
   * The function deletes the place and all assigned places to campaign
   * @return boolean true if success
   */
  public function deletePlace() {
    $campaignPlacesDel = CampaignPlaces::where('id_place', $this->id)->delete();
    $answersDel = Answers::where('id_place', $this->id)->delete();
    if ($this->delete()) {
      return true;
    }
  }

}
