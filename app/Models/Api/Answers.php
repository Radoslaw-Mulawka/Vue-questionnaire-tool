<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use App\Repository\Transformers\AnswersTransformers;

class Answers extends Model
{
  protected $campaign = null;
  
  public $transformer = AnswersTransformers::class;
  
  public function __construct( array $attributes = [], Campaigns $campaign = null) {
    parent::__construct($attributes);
    if(!is_null($campaign)){
      $this->campaign = $campaign;
    }
  }

  /**
   * The function calculates the difference of dates between today and the end date of the campaign
   * @return string - formatted sring with number of days to the end or info that the campaign expired 
   */
  public function calculateDays() {
    if (is_null($this->campaign->date_to)) {
      return ['text' => trans('app.without_deadline'), 'days' => null];
    }

    $date_from = date_create(date('Y-m-d')); //Today
    $date_to = date_create($this->campaign->date_to);
    $diff = date_diff($date_from, $date_to);
    if ($diff->invert == 1) {
      return ['text' => trans('app.to_end') . ': ' . trans('app.campaign_ended'), 'days' => 0];
    } else {
      return ['text' => trans('app.to_end') . ': ' . $diff->format('%a') . ' ' . trans('app.days'), 'days' => $diff->format('%a')];
    }
  }

  /**
   * Preparation of Answers to Questions
   * @return array - prepared results
   */
  public function prepareResultsQuestions($placeId = false) {
    $questions = $this->getQuestions($this->campaign->id);
    $answers = $this->getAnswers($this->campaign->id, $placeId);
    $questionsResults = $this->foreachQuestions($questions, $answers);
    return $questionsResults;
  }

  /**
   * The function gets questions for the selected campaign
   * @param int $id - campaign id
   * @return array of questions
   */
  private function getQuestions(int $id) {
    $questionsObjects = Questions::with('options')
        ->select('id', 'option_type', 'other', 'F_question', 'F_label_other', 'F_extended_desc', 'q_require')
        ->where('id_campaign', $id)->orderBy('q_order', 'ASC')->get();
    $questions = $questionsObjects->all();
    return $questions;
  }

  /**
   * The function gets places assigned to the campaign
   * @param int $id - campaign id
   * @return array of places
   */
  public function getCampaignPlaces() {
    $campaignPlaces = $this->campaign->campaignPlaces;
    $places = array();
    $campaignPlaces
      ->map(function($item) use(&$places) {
        $places[$item->id_place] = $item->toArray();
      });
    return $places;
  }
  
  public function getCampaignPlacesWithAnswers() {
    $campaignPlaces = $this->campaign->campaignPlaces()->with('place')->get();
    foreach ($campaignPlaces as $key => $place) {
      $campaignPlaces[$key]->uniqueAnswersResults = count($this->campaign->answers()->select('guest_phid')->where('id_place', $place->id_place)->groupBy('guest_phid')->get());
      $campaignPlaces[$key]->uniqueAnswersPercentage = $this->uniqueAnswers > 0 ? round($campaignPlaces[$key]->uniqueAnswersResults * 100 / $this->uniqueAnswers, 1):0 ;
      $campaignPlaces[$key]->uniqueViewsResults = $this->campaign->uniqueViews()->where('id_place', $place->id_place)->count();
      $campaignPlaces[$key]->uniqueViewsPercentage = $this->uniqueViews > 0 ? round($campaignPlaces[$key]->uniqueViewsResults * 100 / $this->uniqueViews, 1): 0;
      $campaignPlaces[$key]->placeComment = $place->place->comment;
      $campaignPlaces[$key]->placeName = $place->place->name;
    }
    return $campaignPlaces;
  }

  /**
   * The function gets answers assigned to the campaign
   * @param int $id - campaign id
   * @param bool $places - additional grouping due to the place if true
   * @return array of places
   */
  private function getAnswers(int $id, $placeId = false) {
    $answers = array();
    if ($placeId) {
      Answers::select('id', 'id_place', 'id_question', 'id_option', 'a_value', 'guest_phid')->where('id_campaign', $id)
        ->where('id_place', $placeId)
        ->get()
        ->map(function($item) use(&$answers) {
          $answers[$item->id_question][] = $item->toArray();
        });
    } else {
      Answers::select('id', 'id_place', 'id_question', 'id_option', 'a_value', 'guest_phid')->where('id_campaign', $id)
        ->get()
        ->map(function($item) use(&$answers) {
          $answers[$item->id_question][] = $item->toArray();
        });
    }
    return $answers;
  }

  /**
   * The function assigns and groups answers to questions
   * @param array $questions
   * @param array $answers
   * @return array of Questions
   */
  private function foreachQuestions( $questions, array $answers) {
    foreach ($questions as $key => $question) {
      $option_ans = array();
      if (!isset($answers[$question->id])) {
        $questions[$key]->count_answers = 0;
        $questions[$key]->unique_answers = 0;
        $questions[$key]->omit_answers = (int) $this->uniqueAnswers - (int) $questions[$key]->unique_answers;
      }else{
        $questions[$key]->count_answers = count($answers[$question->id]);
        $questions[$key]->unique_answers = count(array_count_values(array_column($answers[$question->id], 'guest_phid')));
        $questions[$key]->omit_answers = (int) $this->uniqueAnswers - (int) $questions[$key]->unique_answers;
      }
      
      switch ($question->option_type) {
        case 'checkbox':
          if ($questions[$key]->count_answers > 0) {
            $option_ans = $this->prepareResultsChexbox($answers[$question->id], $questions[$key]->count_answers);
          }
          foreach ($questions[$key]->options as $key_o => $option) {
            if(isset($option_ans['options'][$option->id])){
              $questions[$key]->options[$key_o]->results = $option_ans['options'][$option->id]['results'];
              $questions[$key]->options[$key_o]->percentage = $option_ans['options'][$option->id]['percentage'];
            }else{
              $questions[$key]->options[$key_o]->results = 0;
              $questions[$key]->options[$key_o]->percentage = 0;
            }
          }
          if($question->other && $questions[$key]->count_answers > 0){
            $questions[$key]->other_answers = [
              'results' => count($option_ans['others']),
              'percentage' => $option_ans['others_percentage'],
              'texts' => $option_ans['others']
            ];
          }
          break;
        case 'radio':
          if ($questions[$key]->count_answers > 0) {
            $option_ans = $this->prepareResultsRadio($answers[$question->id], $questions[$key]->count_answers);
          }
          foreach ($questions[$key]->options as $key_o => $option) {
            if(isset($option_ans['options'][$option->id])){
              $questions[$key]->options[$key_o]->results = $option_ans['options'][$option->id]['results'];
              $questions[$key]->options[$key_o]->percentage = $option_ans['options'][$option->id]['percentage'];
            }else{
              $questions[$key]->options[$key_o]->results = 0;
              $questions[$key]->options[$key_o]->percentage = 0;
            }
          }
          if($question->other && $questions[$key]->count_answers > 0){
            $questions[$key]->other_answers = [
              'results' => count($option_ans['others']),
              'percentage' => $option_ans['others_percentage'],
              'texts' => $option_ans['others']
            ];
          }
          
          break;
        case 'text':
            if ($questions[$key]->count_answers > 0) {
                $questions[$key]->text_answers = $this->prepareResultsText($answers[$question->id]);
            }else{
                 $questions[$key]->text_answers = null;
            }
          break;
        case 'votes':
          if ($questions[$key]->count_answers > 0) {
            $voteAnswers = $answers[$question->id];
          }else{
              $voteAnswers = false;
          }
          $votesData = $this->prepareResultsRating($voteAnswers, $questions[$key]->count_answers);
          $questions[$key]->votes = $votesData['votes'];
          $questions[$key]->votesAvg = $questions[$key]->count_answers > 0 ? round(($votesData['avgSum'] / $questions[$key]->count_answers), 1) : 0;
          break;
      }
    }
    return $questions;
  }
 
  public function prepareResultsText(array $answers){
    $text = [];
    foreach($answers as $ans){
      $text[] = $ans['a_value'];
    }
    return $text;
  }

  /**
   * The function processes results for question type Rating
   * @param array $answers
   * @param int $count - The number of all unique answers for a given question
   * @return array of results
   */
  private function prepareResultsRating($answers, int $count) {
    $results = ['avgSum' => 0, 'votes' => [
      1 => ['results' => 0, 'percentage' => 0],
      2 => ['results' => 0, 'percentage' => 0],
      3 => ['results' => 0, 'percentage' => 0],
      4 => ['results' => 0, 'percentage' => 0],
      5 => ['results' => 0, 'percentage' => 0]]];

    if($answers == false){
         $results['avgSum'] = 0;
         return $results;
    }

    $sum = 0;
    $countAnswers = array_count_values(array_column($answers, 'a_value'));

    for ($i = 1; $i < 6; $i++) {
      $results['votes'][$i]['results'] = isset($countAnswers[$i]) ? $countAnswers[$i] : 0;
      $results['votes'][$i]['percentage'] = round($results['votes'][$i]['results'] / $count * 100, 1);
      $sum += $results['votes'][$i]['results'] * $i;
    }

    $results['avgSum'] = $sum;
    return $results;
  }
  
  /**
   * The function processes results for question with type Checkbox
   * @param array $answers
   * @param int $count - The number of all unique answers for a given question
   * @return array of results
   */
  private function prepareResultsChexbox(array $answers, int $count) {
    $results = array('others' => array(), 'options' => array());

    $other_keys = array_keys(array_column($answers, 'id_option'), 0);
    foreach ($other_keys as $item) {
      $results['others'][] = $answers[$item]['a_value'];
      unset($answers[$item]);
    }

    $results['others_percentage'] = round(count($results['others']) / $count * 100, 1);
    $countAnswers = array_count_values(array_column($answers, 'id_option'));

    foreach ($countAnswers as $key => $item) {
      $results['options'][$key]['results'] = $item;
      $results['options'][$key]['percentage'] = round($item / $count * 100, 1);
    }

    return $results;
  }

  /**
   * The function processes results for question with type Radio
   * @param array $answers
   * @param int $count - The number of all unique answers for a given question
   * @return array of results
   */
  private function prepareResultsRadio(array $answers, int $count) {
    $results = array('others' => array(), 'options' => array());

    $other_keys = array_keys(array_column($answers, 'id_option'), 0);
    foreach ($other_keys as $item) {
      $results['others'][] = $answers[$item]['a_value'];
      unset($answers[$item]);
    }

    $results['others_percentage'] = round(count($results['others']) / $count * 100, 1);
    $countAnswers = array_count_values(array_column($answers, 'id_option'));

    foreach ($countAnswers as $key => $item) {
      $results['options'][$key]['results'] = $item;
      $results['options'][$key]['percentage'] = round($item / $count * 100, 1);
    }

    return $results;
  }

}
