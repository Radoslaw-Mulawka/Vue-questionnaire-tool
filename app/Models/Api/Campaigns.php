<?php

namespace App\Models\Api;

use App\Scopes\CampaignScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use QrCode;
use App\Repository\Transformers\CampaignsTransformers;

class Campaigns extends Model {

  const CAMPAIGN_PUBLIC = '1';
  const CAMPAIGN_DRAFT = '0';

  public $transformer = CampaignsTransformers::class;

  protected static function boot() {
    parent::boot();
    static::addGlobalScope(new CampaignScope);
  }

  /**
   * Get the questions for the campaign.
   */
  public function questions() {
    return $this->hasMany('App\Models\Api\Questions', 'id_campaign')->orderBy('q_order', 'asc');
  }

  /**
   * Get the places record associated with the campaign.
   */
  public function campaignPlaces() {
    return $this->hasMany('App\Models\Api\CampaignPlaces', 'id_campaign', 'id');
  }

  /**
   * Get the answers record associated with the campaign.
   */
  public function answers() {
    return $this->hasMany('App\Models\Api\Answers', 'id_campaign', 'id');
  }

  /**
   * Get the answers record associated with the campaign.
   */
  public function uniqueViews() {
    return $this->hasMany('App\Models\Api\UniqueViews', 'id_campaign', 'id');
  }

  /**
   * The function prepare and save data of Campaign
   * @param Request $request
   * @param bool $new - should be true if is new campaign
   * @return boolean tru if success
   */
  public function prepareDataAndSave(Request $request) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if (empty($data)) {
      return false;
    }

    $validateData = $this->validateAllData($data);
    if ($validateData !== true) {
      return $validateData;
    }

    if (is_null($this->saveBasicData($data['basicData']))) {
      return false;
    }

    if (!$request->isJson() && $request->file('campaignBanner')) {
      $this->F_logo = $this->uploadImage($request->file('campaignBanner'));
      $this->save();
    }

    if (isset($data['questions']) && count($data['questions']) > 0) {
      $order = 1;
      foreach ($data['questions'] as $item) {
        $item['questionOrder'] = $order;
        $question = new Questions();
        $question->prepare($item, $this->id);
        unset($question);
        $order++;
      }
    }

    if (isset($data['campaignPlaces']) && count($data['campaignPlaces']) > 0) {
      $this->assignPlaces($request);
    }

    return $this->id > 0 ? true : false;
  }

  public function validateAllData($data) {
    $errors = 0;
    $campaignValidator = $this->validator($data['basicData']);
    if ($campaignValidator->fails()) {
      $data['basicData']['errors'] = $campaignValidator->errors();
      $errors++;
    }

    if (isset($data['questions']) && count($data['questions']) > 0) {
      foreach ($data['questions'] as $key => $item) {
        $question = new Questions();
        $questionValidator = $question->validator($item);
        if ($questionValidator->fails()) {
          $data['questions'][$key]['errors'] = $questionValidator->errors();
          $errors++;
        }

        if (($item['questionOptionType'] == 'checkbox' || $item['questionOptionType'] == 'radio') && (!isset($item['options']) || count($item['options']) == 0)) {
          $data['questions'][$key]['errors'] = trans('app.question_no_options');
          $errors++;
        }

        if (isset($item['options']) && count($item['options']) > 0) {
          foreach ($item['options'] as $optKey => $opt) {
            $options = new Options();
            $optionsValidator = $options->validator($opt);
            if ($optionsValidator->fails()) {
              $data['questions'][$key]['options'][$optKey]['errors'] = $optionsValidator->errors();
              $errors++;
            }
          }
        }
      }
    }

    if (isset($data['campaignPlaces']) && count($data['campaignPlaces']) > 0) {
      foreach ($data['campaignPlaces'] as $placeId) {
        $places = new CampaignPlaces();
        $placesValidator = $places->validatorIdPlace(['id' => $placeId]);
        if ($placesValidator->fails()) {
          $data['campaignPlaces']['errors'] = $placesValidator->errors();
          $errors++;
        }
      }
    }

    return $errors > 0 ? $data : true;
  }

  public function saveBasicData($data) {
    $this->validator($data)->validate();
    $this->name = $data['campaignName'];
    $this->description = null; // For the future
    $this->status = 0;
    $this->F_intro_text = isset($data['campaignIntroText']) ? $data['campaignIntroText'] : null;
    $this->F_ending_text = isset($data['campaignEndingText']) ? $data['campaignEndingText'] : null;
    $this->F_E_label_title = null; // For the future
    $this->date_from = $data['campaignDateFrom'];
    $this->date_to = isset($data['campaignDateTo']) ? $data['campaignDateTo'] : null;
    $this->users_id = auth()->user()->getAuthIdentifier();
    $this->F_logo = '';
    $this->save();

    return $this->id > 0 ? $this->id : null;
  }

  /**
   * Get a validator for a new campaign request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validator(array $data) {
    return Validator::make($data, [
        'campaignName' => 'required|max:1000',
        //'description' => 'nullable',
        //'campaignStatus' => 'required|in:0,1',
        'campaignIntroText' => 'nullable',
        'campaignEndingText' => 'nullable',
        'campaignBanner' => 'nullable|file|max:2048|image',
        //'F_E_label_title' => 'nullable',
        'campaignDateFrom' => 'required|date_format:Y-m-d|after:yesterday',
        'campaignDateTo' => 'nullable|date_format:Y-m-d|after:campaignDateFrom|after:yesterday'
    ]);
  }

  public function partialValidate(array $data, $all) {
    Validator::extend('edited_date', function($field, $value, $parameters) {
      if ($value >= $this->date_from || $value >= date('Y-m-d')) {
        return true;
      }
      return false;
    });

    Validator::extend('date_from_to', function($field, $value, $parameters) {
      if ($value <= $this->date_to) {
        return true;
      }
      return false;
    });

    Validator::extend('disable_when_published', function($field, $value, $parameters) {
      if ($this->status == 1 && $this->date_from <= date('Y-m-d') && $value != $this->date_from) {
        return false;
      }
      return true;
    });


    $rules = [
      'campaignName' => 'required|max:1000',
      //'description' => 'nullable',
      //'campaignStatus' => 'required|in:0,1',
      'campaignIntroText' => 'nullable',
      'campaignEndingText' => 'nullable',
      'campaignBanner' => 'nullable|file|max:2048|image',
      //'F_E_label_title' => 'nullable',
      'campaignDateFrom' => 'required|date_format:Y-m-d|edited_date|disable_when_published',
      'campaignDateTo' => 'nullable|date_format:Y-m-d|edited_date'
    ];

    if (!isset($all['campaignDateTo']) && isset($all['campaignDateFrom']) && !empty($this->date_to)) {
      $rules['campaignDateFrom'] .= '|date_from_to';
    }

    if (isset($all['campaignDateTo']) && isset($all['campaignDateFrom'])) {
      $rules['campaignDateTo'] .= '|after:' . $all['campaignDateFrom'];
    }

    $validator = Validator::make($data, [
        key($data) => $rules[key($data)],
    ]);

    if ($validator->fails()) {
      return $validator->errors();
    }

    return true;
  }

  public function prepareDataAndUpdate(Request $request) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if (empty($data) || empty($data['basicData'])) {
      return false;
    }

    foreach ($data['basicData'] as $key => $value) {
      $data['basicData']['errors'] = $this->partialValidate([$key => $value], $data['basicData']);
      if ($data['basicData']['errors'] !== true) {
        return $data;
      }
    }

    if (isset($data['basicData']['campaignName'])) {
      $this->name = $data['basicData']['campaignName'];
    }

     if (isset($data['basicData']['campaignIntroText']) || (!empty($this->F_intro_text) && array_key_exists('campaignIntroText', $data['basicData']))) {
      $this->F_intro_text = $data['basicData']['campaignIntroText'];
    }

    if (isset($data['basicData']['campaignEndingText']) || (!empty($this->F_ending_text) && array_key_exists('campaignEndingText', $data['basicData']))) {
      $this->F_ending_text = $data['basicData']['campaignEndingText'];
    }

    if (isset($data['basicData']['campaignDateFrom'])) {
      $this->date_from = $data['basicData']['campaignDateFrom'];
    }

    if (isset($data['basicData']['campaignDateTo']) || (!empty($this->date_to) && array_key_exists('campaignDateTo', $data['basicData']))) {
      $this->date_to = $data['basicData']['campaignDateTo'];
    }

    if (!$request->isJson() && $request->file('basicData')) {
      $this->F_logo = $this->uploadImage($request->file('basicData')['campaignBanner']);
    }

    $this->save();

    return $this->id > 0 ? true : false;
  }

  /**
   * The function gets places and checks if someone is assigned to campaign
   * @return array of places
   */
  public function getPlaces($all) {
    $campaignPlaces = $this->campaignPlaces;

    if ($all == 'all') {
      if (request()->has('search')) {
        $search = request()->search;
        $places = Places::where('name', 'LIKE', '%' . strtolower($search) . '%')
          ->orWhere('comment', 'LIKE', '%' . strtolower($search) . '%')
          ->orderBy('name', 'asc')
          ->get();
      } else {
        $places = Places::orderBy('name', 'asc')
          ->get();
      }

      $placesArray = $places->toArray();

      if (count($campaignPlaces)) {
        foreach ($campaignPlaces as $value) {
          $find_key = array_search($value['id_place'], array_column($placesArray, 'id'));
          if($find_key !== false && !is_null($find_key)){
            $places[$find_key]['assigned'] = true;
            $places[$find_key]['shortcode'] = $value->shortcode;
            $places[$find_key]['label_name'] = $value->label_name;
            $places[$find_key]['id_campaign'] = $this->id;
          }
        }
      }

      return $places;
    } else {
      return $campaignPlaces;
    }
  }

  /**
   * The function deletes the campaign and all questions, options and places,
   * answers and views assigned to it
   * @return boolean true if success
   */
  public function deleteCampaign() {
    $directory = sha1('katalog' . $this->id);
    if (Storage::disk('public')->exists($directory)) {
      Storage::disk('public')->deleteDirectory($directory);
    }

    $questionsDel = Questions::where('id_campaign', $this->id)->delete();
    $optionsDel = Options::where('id_campaign', $this->id)->delete();
    $campaignPlacesDel = CampaignPlaces::where('id_campaign', $this->id)->delete();
    $answersDel = Answers::where('id_campaign', $this->id)->delete();
    $uniqueViewsDel = UniqueViews::where('id_campaign', $this->id)->delete();
    if ($this->delete()) {
      return true;
    }
  }

  /**
   * The function assigns place to the campaign
   * @param Request $request
   */
  public function assignPlaces($request) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if (empty($data) || empty($data['campaignPlaces'])) {
      return false;
    }

    if (!is_array($data['campaignPlaces'])) {
      return false;
    }

    foreach ($data['campaignPlaces'] as $value) {
      $place = Places::find($value);
      if ($place->users_id != auth()->user()->getAuthIdentifier()) {
        $data['campaignPlaces']['errors'] = trans('app.not_your_place') . ' ID: ' . $value;
        return $data;
      }
    }

    foreach ($data['campaignPlaces'] as $value) {
      $shortcode = $this->generateRandString(5);
      $place = Places::find($value);
      $campaignPlaces = CampaignPlaces::firstOrCreate(
          [
          'users_id' => (int) auth()->user()->getAuthIdentifier(),
          'id_place' => (int) $place->id,
          'id_campaign' => (int) $this->id
          ], [
          'shortcode' => $shortcode,
          'label_name' => $place->name
      ]);
      if ($campaignPlaces) {
        $this->generateLabel($campaignPlaces, env('APP_URL_LP'));
      }
    }

    return true;
  }

  /**
   * The function unassigns places to the campaign
   * @param Request $request
   * @return mixed
   */
  public function unassignPlaces($request) {
    $data = $request->isJson() ? $request->json()->all() : $request->all();
    if (empty($data) || empty($data['campaignPlaces'])) {
      return false;
    }

    if (!is_array($data['campaignPlaces'])) {
      return false;
    }

    $countPlaces = CampaignPlaces::where('id_campaign', $this->id)
      ->count();

    foreach ($data['campaignPlaces'] as $value) {
      $place = Places::find($value);
      if ($place->users_id != auth()->user()->getAuthIdentifier()) {
        $data['campaignPlaces']['errors'] = trans('app.not_your_place') . ' ID: ' . $value;
        return $data;
      }
      $campaignPlace = CampaignPlaces::where('id_place', $value)
        ->where('id_campaign', $this->id)
        ->first();

      if (!$campaignPlace) {
        $data['campaignPlaces']['errors'] = trans('app.campaign_assign_place_no_place') . ' ID: ' . $value;
        return $data;
      }
    }

    if ($countPlaces == count($data['campaignPlaces']) && $this->status == 1) {
      return $data['campaignPlaces']['errors'] = trans('app.campaign_place_at_least_one');
    }

    foreach ($data['campaignPlaces'] as $value) {
      $campaignPlace = CampaignPlaces::where('id_place', $value)
        ->where('id_campaign', $this->id)
        ->first();
      $folderName = sha1('katalog' . $campaignPlace->id_campaign);
      if (!empty($campaignPlace->shortcode) && Storage::disk('public')->exists($folderName . '/label_' . $campaignPlace->shortcode . '.png')) {
        Storage::disk('public')->delete($folderName . '/label_' . $campaignPlace->shortcode . '.png');
      }

      $answersDel = Answers::where('id_campaign', $this->id)
        ->where('id_place', $campaignPlace->id_place)
        ->delete();
      $campaignPlace->delete();
    }

    return true;
  }

  /**
   * Generate shortcode for places and campaign
   * @param int $number number of characters in string
   * @return string
   */
  private function generateRandString(int $number = 5) {
    //$string = substr(md5(uniqid(mt_rand(), true)), 0, $number);
    $chrList = 'abcdefghijklmnopqrstuvwxyz0123456789';

    // Minimum/Maximum times to repeat character List to seed from
    $chrRepeatMin = 1; // Minimum times to repeat the seed string
    $chrRepeatMax = 10; // Maximum times to repeat the seed string
    // Length of Random String returned
    $chrRandomLength = $number;

    // The ONE LINE random command with the above variables.
    $string = substr(str_shuffle(str_repeat($chrList, mt_rand($chrRepeatMin, $chrRepeatMax))), 1, $chrRandomLength);

    $campaignPlaces = CampaignPlaces::where('shortcode', $string)->count();
    if ($campaignPlaces > 0) {
      $this->generateRandString($number);
    } else {
      return $string;
    }
  }

  /**
   * The function save Campaign status
   * @param boolean true if success
   */
  public function changeStatus($status) {
    $statusArr = ['status' => $status];
    Validator::make($statusArr, ['status' => 'required|in:0,1'])->validate();
    $this->status = $status;
    $this->save();
  }

  /**
   * Upload image on server
   * @param Illuminate\Http\UploadedFile $file
   * @param int $width - default 400px
   * @param int $height - default 250px
   * @return string $path - path to file
   */
  private function uploadImage($file, $width = 690, $height = 260) {
    $folderName = sha1('katalog' . $this->id);
    $filePath = $file->hashName();

    if (!Storage::disk('public')->exists($folderName)) {
      Storage::disk('public')->makeDirectory($folderName);
    }

    if (!empty($this->F_logo) && Storage::disk('public')->exists($this->F_logo)) {
      Storage::disk('public')->delete($this->F_logo);
    }

    $image = Image::make($file);
    $image->fit($width, $height, function ($constraint) {
      //$constraint->upsize(); // Zablokowanie rozciągania zdjęcia
      //(gdy ta linijka wyżej jest odkomentowana to zdjęcie nie będzie się
      //rozciągać do zadanych wymiarów jeśli któryś jest mniejszy)
    });

    $path = Storage::disk('public')->put($folderName . '/' . $filePath, (string) $image->encode(), 'public');
    return $folderName . '/' . $filePath;
  }

  /**
   * Generate campaing label assigned to place
   * @param App\CampaignPlaces $campaignPlaces
   * @param string $url - root url
   * @return boolean
   */
  private function generateLabel($campaignPlaces, $url) {
    $folderName = sha1('katalog' . $campaignPlaces->id_campaign);
    $qrcode = QrCode::format('png')
      ->size(370)->margin(1)
      ->color(0, 0, 0)->backgroundColor(255, 255, 255)
      ->generate($url . '/' . $campaignPlaces->shortcode);

    $campaign = Campaigns::findOrFail($campaignPlaces->id_campaign);

    $label = Image::make('img/qr_code_label.png');
    $newtext = wordwrap($campaign->name, 40, " \n");

    $label->text((string) $newtext, 93, 124, function($font) {
      $font->file(public_path('fonts/Muli-Bold.ttf'));
      $font->size(36);
      $font->color('#5f5f71');
      $font->align('left');
      $font->valign('top');
    });

    $label->insert($qrcode, 'top-left', 400, 448);

    $newtext3 = 'Zeskanuj QR kod specjalną aplikacją zainstalowaną na Twoim telefonie lub w razie problemów';
    $label->text((string) $newtext3, 95, 1150, function($font) {
      $font->file(public_path('fonts/Muli-Regular.ttf'));
      $font->size(18);
      $font->color('#5f5f71');
      $font->align('left');
      $font->valign('bottom');
    });

    $newtext4 = 'użytj tego adresu: ';
    $label->text((string) $newtext4, 95, 1195, function($font) {
      $font->file(public_path('fonts/Muli-Regular.ttf'));
      $font->size(18);
      $font->color('#5f5f71');
      $font->align('left');
      $font->valign('bottom');
    });

    $newtext5 = $url . '/' . $campaignPlaces->shortcode;
    $label->text((string) $newtext5, 255, 1195, function($font) {
      $font->file(public_path('fonts/Muli-Bold.ttf'));
      $font->size(18);
      $font->color('#874d9e');
      $font->align('left');
      $font->valign('bottom');
    });

    $newtext2 = wordwrap($campaignPlaces->label_name, 70, " \n");
    $label->text((string) $newtext2, 95, 1290, function($font) {
      $font->file(public_path('fonts/Muli-Regular.ttf'));
      $font->size(30);
      $font->color('#242439');
      $font->align('left');
      $font->valign('bottom');
    });

    if (Storage::disk('public')->put($folderName . '/label_' . $campaignPlaces->shortcode . '.png', (string) $label->encode('png'), 'public')) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * The function generates a packet of files to be downloaded and packs it to the zip archive
   * @param type $campaignPlaces
   * @return string path to zip file
   */
  public function downloadZip($campaignPlaces) {
    $folderName = sha1('katalog' . $this->id);
    $folderPath = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($folderName);
    $zip = new \ZipArchive();
    $filename = $folderPath . "/qrcodes.zip";

    if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
      return \Redirect::route('campaigns.show', $this->id)
          ->with('message', trans('app.campaign_download_zip_error') . "<$filename>\n");
    }

    foreach ($campaignPlaces as $value) {
      $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($folderName . '/label_' . $value->shortcode . '.png');
      $zip->addFile($path, 'label_' . $value->shortcode . '.png');
    }

    $zip->close();

    return $filename;
  }

  /**
   * DASHBOARD VIEW
   */

  /**
   * The function generates values for dashboard
   * @return \stdClass -  Dashboard object with values
   */
  public function getDashboard() {
    $dashboard = new \stdClass;
    $campaigns = Campaigns::orderBy('status', 'desc')
      ->orderBy('created_at', 'desc')
      ->orderBy('name', 'asc')
      ->get();
    $tempUniqueViews = 0;
    $tempQuestionaries = 0;
    foreach ($campaigns as $campaign) {
      $campaign->uniqueAnswers = count($campaign->answers()->select('guest_phid')->groupBy('guest_phid')->get());
      $campaign->uniqueViews = $campaign->uniqueViews()->count();
      $tempUniqueViews += $campaign->uniqueViews;
      $tempQuestionaries += $campaign->uniqueAnswers;
    }

    $today = date('Y-m-d');
    $endedDate = date('Y-m-d', strtotime("+1 week")); // one week
    $dashboard->activeCampaigns = Campaigns::where('status', 1)
      ->where('date_to', '>=', $today)
      ->orWhereNull('date_to')
      ->where('date_from', '<=', $today)
      ->count();
    $dashboard->allSendedPolls = $tempQuestionaries;
    $dashboard->allPlaces = Places::count();

    $dashboard->endedCampaigns = Campaigns::where('date_to', '<=', $endedDate)
      ->where('date_to', '>=', $today)
      ->where('status', 1)
      ->count();
    $dashboard->uniqueViews = $tempUniqueViews;
    $dashboard->allQuestions = Questions::count();

    return $dashboard;
  }

}
