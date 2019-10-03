<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Newsletter extends Model {

  use Notifiable;
  
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'newsletter';

}
