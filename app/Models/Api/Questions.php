<?php

namespace App\Models\Api;

use App\Scopes\QuestionsScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository\Transformers\QuestionsTransformers;

class Questions extends Model
{
    const QUESTION_REQUIRE = '1';
    const QUESTION_NO_REQUIRE = '0';

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'campaign_questions';

  public $transformer = QuestionsTransformers::class;

    protected static function boot()
  {
      parent::boot();
      static::addGlobalScope(new QuestionsScope);
  }

    /**
     * Get the questions for the campaign.
     */
    public function options()
    {
        return $this->hasMany('App\Models\Api\Options', 'id_question')->orderBy('o_order','asc');
    }

    /**
     * Get the option type record associated with the question.
     */
    public function optionType()
    {
        return $this->hasOne('App\Models\Api\OptionTypes', 'id', 'option_type');
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Api\Answers', 'id_question', 'id');
    }

  public function prepareDataAndSave(Request $request, int $campaignId) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if(empty($data)){
      return false;
    }

    $validateData = $this->validateAllData($data);
    if($validateData !== true){
      return $validateData;
    }

    if(is_null($this->prepare($data['questionData'], $campaignId))){
      return false;
    }

   return $this->id > 0 ? true : false;
  }

  public function prepareDataAndUpdate(Request $request, int $campaignId) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if(empty($data)){
      return false;
    }

    $validateData = $this->validateAllData($data, true);
    if($validateData !== true){
      return $validateData;
    }

    if(is_null($this->prepare($data['questionData'], $campaignId, true))){
      return false;
    }

   return $this->id > 0 ? true : false;
  }

  public function validateAllData($data, $edit = false) {
    $errors = 0;

    $questionValidator = $this->validator($data['questionData']);
    if ($questionValidator->fails()) {
      $data['errors'] = $questionValidator->errors();
      $errors++;
    }

    if (!$edit
      && ($data['questionData']['questionOptionType'] == 'checkbox' || $data['questionData']['questionOptionType'] == 'radio')
      && (!isset($data['questionData']['options']) || count($data['questionData']['options']) == 0)) {
      $data['errors'] = trans('app.question_no_options');
      $errors++;
    }

    if (isset($data['options']) && count($data['options']) > 0) {
      foreach ($data['options'] as $optKey => $opt) {
        $options = new Options();
        $optionsValidator = $options->validator($opt);
        if ($optionsValidator->fails()) {
          $data['options'][$optKey]['errors'] = $optionsValidator->errors();
          $errors++;
        }
      }
    }

    return $errors > 0 ? $data : true;
  }

  public function prepare($data, $campaignId, $edit = false) {
    $this->validator($data)->validate();

    $this->users_id = auth()->user()->getAuthIdentifier();
    $this->campaigns_id = $campaignId;
    $this->F_question = $data['questionName'];
    $this->F_extended_desc = isset($data['questionExtendedDesc']) ? $data['questionExtendedDesc'] : $this->F_extended_desc;
    $this->option_type = $data['questionOptionType'];
    if(isset($data['questionOrder']) && !isset($this->id)){
      $this->q_order = $data['questionOrder'];
    }elseif (!isset($data['questionOrder']) && !isset($this->id)) {
      $max = $this->where('id_campaign', (int) $campaignId)->max('q_order');
      $this->q_order = $max+1;
    }

    $this->other = isset($data['questionOther']) ? $data['questionOther'] : $this->other;
    $this->F_label_other = isset($data['questionLabelOther']) ? $data['questionLabelOther'] : $this->F_label_other;
    $this->q_require = $data['questionRequired'];
    $this->save();

    if (!$edit && isset($data['options']) && ($data['questionOptionType'] == 'checkbox' || $data['questionOptionType'] == 'radio')) {
      foreach ($data['options'] as $value) {
        $option = new Options();
        $option->prepare($value, $this->id, $campaignId);
      }
    }

    return $this->id > 0 ? $this->id : null;
  }

  /**
   * Get a validator for a new question request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validator(array $data) {
    return Validator::make($data,
      $this->prepareValidatorForAllQuestions($data['questionOptionType'])
    );
  }

  protected function prepareValidatorForAllQuestions($option_type) {

    $questionValidators = [
      'questionName' => 'required|max:1000',
      'questionOptionType' => 'required|exists:dictionary_option_types,name',
      'questionOther' => 'nullable',
      'questionLabelOther' => 'nullable|max:1000',
      'questionExtendedDesc' => 'nullable',
      'questionRequired' => 'required|boolean'
    ];

    switch ($option_type) {
      case 1:
        break;
      case 2:
        break;
      case 3:
        $questionValidators = [
          'questionName' => 'required|max:1000',
          'questionOptionType' => 'required|exists:dictionary_option_types,name',
          'questionExtendedDesc' => 'nullable',
          'questionRequired' => 'required|boolean'
        ];
        break;
      case 4:
        $questionValidators = [
          'questionName' => 'required|max:1000',
          'questionOptionType' => 'required|exists:dictionary_option_types,name',
          'questionExtendedDesc' => 'nullable',
          'questionRequired' => 'required|boolean'
        ];
        break;
    }

    return $questionValidators;
  }

    /**
   * The function deletes the question and all options and places,
   * answers and views assigned to it
   * @return boolean true if success
   */
  public function deleteQuestion() {
    $updateOrder = Questions::where('q_order', '>', $this->q_order)->decrement('q_order');
    $optionsDel = Options::where('id_question', $this->id)->delete();
    $answersDel = Answers::where('id_question', $this->id)->delete();

    if ($this->delete()) {
      return true;
    }
  }

  public static function reorderQuestions($request, $campaignId) {
    $data = $request->isJson() ? $request->json()->all() : null;
    if(empty($data) || empty($data['questionsOrder'])){
      return false;
    }

    $questions = Questions::where('id_campaign', $campaignId)->get();
    if(count($questions) != count($data['questionsOrder'])){
      $data['errors'] = trans('app.question_reorder_error_not_enough');
      return $data;
    }

    $validate = $questions->whereIn('id', $data['questionsOrder']);
    if(count($questions) != count($validate)){
      $data['errors'] = trans('app.question_reorder_error_wrong_ids');
      return $data;
    }

    $order = 1;
    foreach ($data['questionsOrder'] as $value) {
      $question = $questions->where('id', $value)->first();
      $question->q_order = $order;
      $question->save();
      $order++;
      unset($question);
    }

    return true;
  }
}
