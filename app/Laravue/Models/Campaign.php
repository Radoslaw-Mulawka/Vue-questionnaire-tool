<?php


namespace App\Laravue\Models;

use App\Scopes\CampaignScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class Campaign extends Model
{

    protected $fillable = [
        'name',
        'intro_text',
        'ending_text',
    ];

    protected $dates = [
        'date_from',
        'date_to',
    ];

    protected static function boot()
    {
        parent::boot();
        Carbon::serializeUsing(function (Carbon $carbon)
        {
            return $carbon->format('Y-m-d');
        });
        static::addGlobalScope(new CampaignScope);
    }

    public function questions()
    {
        return $this->hasMany('App\Laravue\Models\Question', 'campaigns_id');
    }

    public function options()
    {
        return $this->hasMany('App\Laravue\Models\Option', 'campaigns_id');
    }

    public function answers() {
        return $this->hasMany('App\Models\Api\Answers', 'campaigns_id', 'id');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->users_id;
    }

    /**
     * @param int $userId
     * @return Campaign
     */
    public function setUserId(int $userId): self
    {
        $this->users_id = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Campaign
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Campaign
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Campaign
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getIntroText(): ?string
    {
        return $this->intro_text;
    }

    /**
     * @param string $intro_text
     * @return Campaign
     */
    public function setIntroText(?string $intro_text): self
    {
        $this->intro_text = $intro_text;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndingText(): ?string
    {
        return $this->ending_text;
    }

    /**
     * @param string $ending_text
     * @return Campaign
     */
    public function setEndingText(?string $ending_text): self
    {
        $this->ending_text = $ending_text;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Campaign
     */
    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelTitle(): ?string
    {
        return $this->label_title;
    }

    /**
     * @param string $label_title
     * @return Campaign
     */
    public function setLabelTitle(?string $label_title): self
    {
        $this->label_title = $label_title;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getDateFrom(): ?Carbon
    {
        return $this->date_from;
    }

    /**
     * @param Carbon $date_from
     * @return Campaign
     */
    public function setDateFrom(?Carbon $date_from): self
    {
        $this->date_from = $date_from;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getDateTo(): ?Carbon
    {
        return $this->date_to;
    }

    /**
     * @param Carbon $date_to
     * @return Campaign
     */
    public function setDateTo(?Carbon $date_to): self
    {
        $this->date_to = $date_to;
        return $this;
    }


    /**
     * @param Request $request
     * @param bool $new - should be true if is new campaign
     * @return boolean true if success
     */
    public function checkAndSaveData(Request $request)
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();
        if (empty($data)) {
            return false;
        }

        $validateData = $this->validateAllData($data);
        if ($validateData !== true) {
            return $validateData;
        }

        if (is_null($this->saveData($data))) {
            return false;
        }

        if (!$request->isJson() && $request->file('banner')) {
            $this->setLogo($this->uploadImage($request->file('banner')));
            $this->save();
        }

        if (isset($data['questionsList']) && count($data['questionsList']) > 0) {
            $order = 1;
            foreach ($data['questionsList'] as $item) {
                $item['questionsList'] = $order;
                $question = new Question();
                $question->prepare($item, $this->id);
                unset($question);
                $order++;
            }
        }

        return $this->id > 0 ? true : false;
    }

    public function validateAllData($data)
    {
        $errors = 0;
        $campaignValidator = $this->validator($data);
        if ($campaignValidator->fails()) {
            $data['data']['errors'] = $campaignValidator->errors();
            $errors++;
        }

        if (isset($data['questionsList']) && count($data['questionsList']) > 0) {
            foreach ($data['questionsList'] as $key => $item) {
                $question = new Question();
                $questionValidator = $question->validator($item);
                if ($questionValidator->fails()) {
                    $data['questionsList'][$key]['errors'] = $questionValidator->errors();
                    $errors++;
                }

                if (($item['type'] == 'checkbox' || $item['type'] == 'radio') && (!isset($item['options']) || count($item['options']) == 0)) {
                    $data['questionsList'][$key]['errors'] = trans('app.question_no_options');
                    $errors++;
                }

                if (isset($item['options']) && count($item['options']) > 0) {
                    foreach ($item['options'] as $optKey => $opt) {
                        $options = new Option();
                        $optionsValidator = $options->validator($opt);
                        if ($optionsValidator->fails()) {
                            $data['questionsList'][$key]['options'][$optKey]['errors'] = $optionsValidator->errors();
                            $errors++;
                        }
                    }
                }
            }
        }
        return $errors > 0 ? $data : true;
    }

    public function saveData($data)
    {
        $this->validator($data)->validate();

        $this->setName($data['name']);
        $this->setStatus(0);
        $this->setIntroText(isset($data['enterText']) ? $data['enterText'] : null);
        $this->setEndingText(isset($data['endText']) ? $data['endText'] : null);
        $this->setUserId(auth()->user()->getAuthIdentifier());
        $this->setLogo('');
        $this->save();

        return $this->id > 0 ? $this->id : null;
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:1000',
            'banner' => 'nullable|file|max:2048|image',
            'enterText' => 'nullable',
            'endText' => 'nullable',
        ]);
    }

    private function uploadImage($file, $width = 690, $height = 260)
    {
        $folderName = sha1('katalog' . $this->id);
        $filePath = $file->hashName();

        if (!Storage::disk('public')->exists($folderName)) {
            Storage::disk('public')->makeDirectory($folderName);
        }

        if (!empty($this->logo) && Storage::disk('public')->exists($this->logo)) {
            Storage::disk('public')->delete($this->logo);
        }

        $image = Image::make($file);
        $image->fit($width, $height, function ($constraint)
        {
            //$constraint->upsize(); // Zablokowanie rozciągania zdjęcia
            //(gdy ta linijka wyżej jest odkomentowana to zdjęcie nie będzie się
            //rozciągać do zadanych wymiarów jeśli któryś jest mniejszy)
        });

        $path = Storage::disk('public')->put($folderName . '/' . $filePath, (string) $image->encode(), 'public');
        return $folderName . '/' . $filePath;
    }
}
