<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use App\Repository\Transformers\OptionTypesTransformers;

class OptionTypes extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'dictionary_option_types';
  
  public $transformer = OptionTypesTransformers::class;

}
