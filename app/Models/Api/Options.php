<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository\Transformers\OptionsTransformers;

class Options extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'campaign_question_options';
  
  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
  
  public $transformer = OptionsTransformers::class;

  /**
   * Get the questions for the campaign.
   */
  public function questions() {
    return $this->belongsTo('App\Models\Api\Questions', 'id');
  }
  
  /**
   * Get the answers for the option.
   */
  public function answers() {
    return $this->hasMany('App\Models\Api\Answers', 'id_option', 'id');
  }
  
  public function prepareDataAndSave(Request $request, int $userID, int $campaignId, $questionId) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if(empty($data)){
      return false;
    }
    
    $optionsValidator = $this->validator($data['optionData']);
    if ($optionsValidator->fails()) {
      $data['errors'] = $optionsValidator->errors();
      return $data;
    }
   
    if(is_null($this->prepare($data['optionData'], $questionId, $campaignId))){
      return false;
    }

   return $this->id > 0 ? true : false;
  }

  public function prepare($option, $questionId, $campaignId) {
    $this->validator($option)->validate();
    $this->option_label = $option['optionLabel'];

    if(!isset($this->id)){
      $max = Options::where('id_campaign',  $campaignId)->where('id_question',  $questionId)->max('o_order');
      $this->o_order = $max+1;
      $this->id_user = auth()->user()->getAuthIdentifier();
      $this->id_campaign = $campaignId;
      $this->id_question = $questionId;
    }

    $this->save();
    return $this->id > 0 ? $this->id: null;
  }
  
  /**
   * Get a validator for a new campaign request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validator(array $data) {
    return Validator::make($data, [
        'optionLabel' => 'required|max:1000',
        //'optionOrder' => 'required|int'
    ]);
  }
  
  /**
   * The function deletes the option and all places,
   * answers and views assigned to it
   * @return boolean true if success
   */
  public function deleteOption() {
    $updateOrder = Options::where('o_order', '>', $this->o_order)->decrement('o_order');
    $answersDel = Answers::where('id_option', $this->id)->delete();

    if ($this->delete()) {
      return true;
    }
  }
  
}
