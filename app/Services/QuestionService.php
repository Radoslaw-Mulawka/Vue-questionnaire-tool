<?php


namespace App\Services;


use App\Http\Resources\QuestionResources;
use App\Laravue\Models\Campaign;
use App\Laravue\Models\Option;
use App\Laravue\Models\Question;
use App\Traits\ApiResponser;
use App\Utils\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class QuestionService
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

    public function store(Request $request)
    {
        $question = new Question();
        $questionAcceptedFields = ['campaignsId', 'type'];

        $data = '';
        foreach ($questionAcceptedFields as $key => $value) {
            $data = $this->validationHelper->getArrayIfKeyIsValid($request, $value);
        }

        /**
         * @var Campaign $campaign
         */
        $campaign = Campaign::find($data['campaignsId']);
        $this->validationHelper->canEdit($campaign);
        $this->validateQuestion($data);

        $userId = $campaign->getUserId();

        $questions = Question::where('campaigns_id', $data['campaignsId']);

        $question
            ->setUsersId($userId)
            ->setCampaignsId($data['campaignsId'])
            ->setOptionType($data['type'])
            ->setQuestion('Tutaj podaj treść swojego pytania')
            ->setExtendedDesc('Dodatkowa informacja dla użytkownika. Możesz ją pominąć')
            ->setOrder($questions->max('order') + 1)
            ->setRequire(0);

        $question->save();

        $options = Option::where([
            ['campaigns_id', $data['campaignsId']],
            ['questions_id', $question->getId()]
        ]);

        if ($data['type'] === 'radio' || $data['type'] === 'checkbox') {
            $option = new Option();
            $option
                ->setUsersId($userId)
                ->setCampaignsId($data['campaignsId'])
                ->setQuestionsId($question->getId())
                ->setLabel('Opcja numer ' . ($options->count() + 1))
                ->setOrder($options->max('order') + 1);

            $option->save();
        }

        return $this->respondSuccess(new QuestionResources($question), trans('app.question_add'));
    }

    public function update(Question $question, Request $request)
    {
        $accessFields = [
            "questionMainText" => "question",
            "questionAdditionalText" => "extended_desc",
            "required" => "require",
        ];

        if (count($request->all()) !== 1) {
            throw new ValidationException(null, $this->respondWithError(trans('app.too_many_fields'), Res::HTTP_BAD_REQUEST));
        }

        $fieldFromRequest = $request->keys()[0];

        $this->validationHelper->checkValidFieldFromAccessFields($fieldFromRequest, $accessFields);

        $originFieldToChange = $accessFields[$fieldFromRequest];

        $data = $this->validationHelper->getArrayIfKeyIsValid($request, $fieldFromRequest);

        /**
         * @var Campaign $campaign
         */
        $campaign = $question->campaign;

        $this->validationHelper->canEdit($campaign);

        $this->validateQuestion($data);

        $question->fill([$originFieldToChange => $data[$fieldFromRequest]]);

        $question->save();

        return $this->respondSuccess('', trans('app.question_edit'));

    }

    public function validateQuestion(array $data)
    {
        $validator = $this->validateRequest($data);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new ValidationException(null, $this->respondValidationError($errorMessage, ''));
        }

        return true;
    }

    private function validateRequest(array $data)
    {
        return Validator::make($data, [
            'questionMainText' => 'sometimes|required|max:1000',
            'questionAdditionalText' => 'nullable|max:1000',
            'required' => 'boolean',
            'type' => 'sometimes|required|exists:option_types,name'
        ]);
    }

    public function delete(Question $question)
    {
        /**
         * @var Campaign $campaign
         */
        $campaign = $question->campaign;
        $this->validationHelper->canEdit($campaign);

        $questionId = $question->id;
        $campaignsId = $question->getCampaignsId();
        Option::where([
            ['campaigns_id', $campaignsId],
            ['questions_id', $questionId]
        ])->delete();

        $question->delete();

        return $this->respondSuccess(null, trans('app.question_delete_all'));
    }

    public function copy(Question $question)
    {
        $campaign = Campaign::find($question->getCampaignsId());
        $this->validationHelper->canEdit($campaign);
        $newQuestion = $question->replicate();
        $newQuestion->push();

        foreach ($question->options as $option) {
            $newOption = new Option();
            $optionAttributes = $option->getAttributes();

            foreach ($optionAttributes as $attribute => $value) {
                if (!in_array($attribute, ['id', 'questions_id'])) {
                    $newOption->setAttribute($attribute, $value);
                }
            }

            $newOption->questions_id = $newQuestion->id;
            $newOption->save();
        }

        $newQuestion['options'] = $newQuestion->options;

        return $this->respondSuccess(new QuestionResources($newQuestion), '');
    }
}
