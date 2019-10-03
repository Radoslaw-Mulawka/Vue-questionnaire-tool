<?php


namespace App\Laravue\Models;

use App\Scopes\QuestionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Question extends Model
{

    protected $fillable = [
        'question',
        'extended_desc',
        'require',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new QuestionScope());
    }

    public function options()
    {
        return $this->hasMany('App\Laravue\Models\Option', 'questions_id')->orderBy('order', 'asc');
    }

    /**
     * Get the option type record associated with the question.
     */
    public function optionType()
    {
        return $this->hasOne('App\Laravue\Models\OptionType', 'id', 'option_type');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Laravue\Models\Campaign', 'campaigns_id');
    }

    public function answers() {
        return $this->hasMany('App\Models\Api\Answers', 'questions_id', 'id');
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
    public function getUsersId(): int
    {
        return $this->users_id;
    }

    /**
     * @param int $users_id
     * @return Question
     */
    public function setUsersId(int $users_id): self
    {
        $this->users_id = $users_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCampaignsId(): int
    {
        return $this->campaigns_id;
    }

    /**
     * @param int $campaigns_id
     * @return Question
     */
    public function setCampaignsId(int $campaigns_id): self
    {
        $this->campaigns_id = $campaigns_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getOptionType(): string
    {
        return $this->option_type;
    }

    /**
     * @param string $option_type
     * @return Question
     */
    public function setOptionType(string $option_type): self
    {
        $this->option_type = $option_type;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return Question
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return int
     */
    public function getOther(): ?int
    {
        return $this->other;
    }

    /**
     * @param int $other
     * @return Question
     */
    public function setOther(?int $other): self
    {
        $this->other = $other;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return Question
     */
    public function setQuestion(string $question): self
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelOther(): ?string
    {
        return $this->label_other;
    }

    /**
     * @param string $label_other
     * @return Question
     */
    public function setLabelOther(?string $label_other): self
    {
        $this->label_other = $label_other;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtendedDesc(): ?string
    {
        return $this->extended_desc;
    }

    /**
     * @param string $extended_desc
     * @return Question
     */
    public function setExtendedDesc(?string $extended_desc): self
    {
        $this->extended_desc = $extended_desc;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequire(): int
    {
        return $this->require;
    }

    /**
     * @param int $require
     * @return Question
     */
    public function setRequire(int $require): self
    {
        $this->require = $require;
        return $this;
    }


    public function checkAndSaveData(Request $request, int $campaignId)
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();
        if (empty($data)) {
            return false;
        }

        $validateData = $this->validateAllData($data);
        if ($validateData !== true) {
            return $validateData;
        }

        if (is_null($this->prepare($data['questionData'], $campaignId))) {
            return false;
        }

        return $this->id > 0 ? true : false;
    }

    public function validateAllData($data, $edit = false)
    {
        $errors = 0;
        $questionValidator = $this->validator($data['questionData']);
        if ($questionValidator->fails()) {
            $data['errors'] = $questionValidator->errors();
            $errors++;
        }

        if (!$edit
            && ($data['questionData']['type'] == 'checkbox' || $data['questionData']['type'] == 'radio')
            && (!isset($data['questionData']['options']) || count($data['questionData']['options']) == 0)) {
            $data['errors'] = trans('app.question_no_options');
            $errors++;
        }

        if (isset($data['options']) && count($data['options']) > 0) {
            foreach ($data['options'] as $optKey => $opt) {
                $options = new Option();
                $optionsValidator = $options->validator($opt);
                if ($optionsValidator->fails()) {
                    $data['options'][$optKey]['errors'] = $optionsValidator->errors();
                    $errors++;
                }
            }
        }

        return $errors > 0 ? $data : true;
    }

    public function prepare($data, $campaignId, $edit = false)
    {
        $this->validator($data)->validate();

        $this->setUsersId(auth()->user()->getAuthIdentifier());
        $this->setCampaignsId($campaignId);
        $this->setQuestion($data['questionMainText']);
        $this->setExtendedDesc(isset($data['questionAdditionalText']) ? $data['questionAdditionalText'] : $this->extended_desc);
        $this->setOptionType($data['type']);
        if (isset($data['questionOrder']) && !isset($this->id)) {
            $this->setOrder($data['questionOrder']);
        } elseif (!isset($data['questionOrder']) && !isset($this->id)) {
            $max = $this->where('campaigns_id', (int)$campaignId)->max('order');
            $this->setOrder($max + 1);
        }

        $this->setOther(isset($data['questionOther']) ? $data['questionOther'] : $this->other);
        $this->setLabelOther(isset($data['questionLabelOther']) ? $data['questionLabelOther'] : $this->label_other);
        $this->setRequire($data['required']);
        $this->save();

        if (!$edit && isset($data['options']) && ($data['type'] == 'checkbox' || $data['type'] == 'radio')) {
            foreach ($data['options'] as $value) {
                $option = new Option();
                $option->prepare($value, $this->id, $campaignId);
            }
        }

        return $this->id > 0 ? $this->id : null;
    }

    public function validator(array $data)
    {
        return Validator::make($data, $this->prepareValidatorForAllQuestions($data['type']));
    }

    protected function prepareValidatorForAllQuestions($option_type)
    {
        $questionValidators = [
            'questionMainText' => 'required|max:1000',
            'type' => 'required|exists:option_types,name',
            'questionOther' => 'nullable',
            'questionLabelOther' => 'nullable|max:1000',
            'questionAdditionalText' => 'nullable',
            'required' => 'required|boolean'
        ];

        return $questionValidators;
    }
}
