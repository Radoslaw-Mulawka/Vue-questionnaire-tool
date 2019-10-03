<?php


namespace App\Services;


use App\Http\Resources\OptionResources;
use App\Laravue\Models\Campaign;
use App\Laravue\Models\Option;
use App\Laravue\Models\OptionType;
use App\Laravue\Models\Question;
use App\Traits\ApiResponser;
use App\Utils\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class OptionService
{
    use ApiResponser;

    /**
     * @var ValidationHelper
     */
    private $validationHelper;

    public function __construct(ValidationHelper $validationHelper)
    {
        $this->validationHelper = $validationHelper;
    }

    public function createOption(Request $request)
    {
        $data = $this->validationHelper->getArrayIfKeyIsValid($request, 'questionId');

        /** @var Question $question */
        $question = Question::find($data['questionId']);

        /** @var Campaign $campaign */
        $campaign = $question->campaign;

        $this->validationHelper->canEdit($campaign);
        $this->checkQuestionType($question);

        $options = Option::where('questions_id', $question->getId())
            ->where('campaigns_id', $campaign->getId())->get();

        $option = new Option();
        $option->setUsersId($question->getUsersId())
            ->setCampaignsId($campaign->getId())
            ->setQuestionsId($question->getId())
            ->setOrder($options->max('order') + 1)
            ->setLabel('Opcja numer ' . ($options->count() + 1));

        $option->save();

        return $this->respondSuccess(new OptionResources($option), trans('app.option_add'));

    }

    public function updateOption(Option $option, Request $request)
    {
        $data = $this->validationHelper->getArrayIfKeyIsValid($request, 'label');
        $validation = Validator::make($data, ['label' => 'required|max:1000']);

        if ($validation->fails()) {
            throw new ValidationException(null, $this->respondValidationError($validation->errors()->first(), ''));
        };

        $campaign = $option->campaign;
        $this->validationHelper->canEdit($campaign);

        $option->setLabel($data['label']);
        $option->save();

        return $this->respondSuccess('', '');
    }

    public function destroyOption(Option $option)
    {
        $campaign = $option->campaign;
        $this->validationHelper->canEdit($campaign);

        $question = $option->questions;
        $optionsCount = $question->options->count();

        if ($optionsCount <= 1) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.status_error'), ''));
        }

        $option->delete();
        return $this->respondSuccess('', trans('app.option_delete'));
    }

    private function checkQuestionType(Question $question)
    {
        $optionTypesNames = OptionType::where('can_add_option', 1)->pluck('name')->toArray();
        if (!in_array($question->getOptionType(), $optionTypesNames)) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.option_type_incompatible'), ''));
        }
    }

}
