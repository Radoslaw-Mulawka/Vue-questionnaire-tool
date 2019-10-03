<?php


namespace App\Services;


use App\Http\Resources\CampaignResources;
use App\Laravue\Models\Answer;
use App\Laravue\Models\Campaign;
use App\Laravue\Models\Option;
use App\Laravue\Models\Question;
use App\Laravue\Models\CampaignPlaces;
use App\Traits\ApiResponser;
use App\Utils\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;


class CampaignService
{
    use ApiResponser;

    const CREATED = 0;
    const STARTED = 1;
    const PAUSED = 2;
    const FINISHED = 3;

    const VALID_STATUSES = array(self::STARTED, self::PAUSED, self::FINISHED);

    /**
     * @var ValidationHelper
     */
    private $validationHelper;

    public function __construct(ValidationHelper $validationHelper)
    {
        $this->validationHelper = $validationHelper;
    }

    public function create(Request $request)
    {
        $data = $this->validationHelper->getArrayIfKeyIsValid($request, 'name');
        $validation = Validator::make($data, ['name' => 'required|min:3|max:1000']);

        if ($validation->fails()) {
            throw new ValidationException(null, $this->respondValidationError($validation->errors()->first(), ''));
        };

        $campaign = new Campaign();
        $campaign->setUserId(auth()->user()->getAuthIdentifier())
            ->setName($data['name'])
            ->setStatus(0);

        $campaign->save();

        return $this->respondSuccess(new CampaignResources($campaign), trans('app.campaign_add'));
    }

    public function update(Campaign $campaign, Request $request)
    {
        $accessFields = [
            "name" => "name",
            "enterText" => "intro_text",
            "endText" => "ending_text",
        ];

        if (count($request->all()) !== 1) {
            throw new ValidationException(null, $this->respondWithError(trans('app.too_many_fields'), Res::HTTP_BAD_REQUEST));
        }

        $fieldFromRequest = $request->keys()[0];

        $this->validationHelper->checkValidFieldFromAccessFields($fieldFromRequest, $accessFields);

        $originFieldToChange = $accessFields[$fieldFromRequest];

        $data = $this->validationHelper->getArrayIfKeyIsValid($request, $fieldFromRequest);

        $this->validationHelper->canEdit($campaign);

        $this->validateCampaign($data);

        $campaign->fill([$originFieldToChange => $data[$fieldFromRequest]]);

        $campaign->save();
        return $this->respondSuccess('', trans('app.campaign_edit'));
    }

    public function updateBanner(Campaign $campaign, Request $request)
    {
        $bannerAcceptedFields = ['_method', 'banner'];

        $data = '';
        foreach ($bannerAcceptedFields as $key => $value) {
            $data = $this->validationHelper->getArrayIfKeyIsValid($request, $value);
        }

        $this->validationHelper->canEdit($campaign);

        $this->validateCampaign($data);

        $campaign->setLogo($this->uploadImage($request->file('banner'), $campaign) ?: ' ');
        $bannerLink = Storage::url($campaign->getLogo());

        $campaign->save();
        return $this->respondSuccess([$bannerLink], trans('app.campaign_edit'));
    }

    private function uploadImage($file, $campaign, $width = 690, $height = 260)
    {
        $folderName = sha1('katalog' . $campaign->id);

        $filePath = $file->hashName();

        if (!Storage::disk('public')->exists($folderName)) {
            Storage::disk('public')->makeDirectory($folderName);
        }

        if (!empty($campaign->Logo) && Storage::disk('public')->exists($campaign->Logo)) {
            Storage::disk('public')->delete($campaign->Logo);
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

    private function deleteBannerFromDisk(string $path)
    {
        $diskFolder = 'public';
        if (Storage::disk($diskFolder)->exists($path)) {
            Storage::disk($diskFolder)->delete($path);
        }
    }

    public function updateStatus(Campaign $campaign, Request $request)
    {
        $data = $this->validationHelper->getArrayIfKeyIsValid($request, 'status');
        $this->validateStatus($campaign, $data);
        $this->updateValidStatus($campaign, $data['status']);
        $campaign->save();
        return $this->respondSuccess($campaign, '');
    }

    private function updateValidStatus(Campaign $campaign, int $requestStatus)
    {
        $campaignStatus = $campaign->getStatus();
        if ($campaignStatus === self::CREATED && $requestStatus === self::STARTED) {
            $campaign->setDateFrom(now());
        }
        if ($requestStatus === self::FINISHED) {
            $campaign->setDateTo(now());
        }
        $campaign->setStatus($requestStatus);
    }

    public function deleteCampaign(Campaign $campaign)
    {
        $campaignId = $campaign->id;
        Option::where('campaigns_id', $campaignId)->delete();
        Question::where('campaigns_id', $campaignId)->delete();
        Answer::where('campaigns_id', $campaignId)->delete();
        CampaignPlaces::where('campaigns_id', $campaignId)->delete();
        $campaign->delete();

        return $this->respondSuccess('', trans('app.campaign_delete'));
    }

    public function removeBanner(Campaign $campaign)
    {
        $this->validationHelper->canEdit($campaign);
        $path = $campaign->getLogo();
        if (!$path) {
            return $this->respondSuccessNoContent();
        }
        $this->deleteBannerFromDisk($path);
        $campaign->setLogo(null);
        $campaign->save();
        return $this->respondSuccess('', trans('app.banner_delete'));
    }

    private function validateStatus(Campaign $campaign, array $data)
    {
        $dataStatus = $data['status'];
        $campaignStatus = $campaign->getStatus();

        if (!in_array($dataStatus, self::VALID_STATUSES)) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.status_error'), ''));
        }
        if ($campaignStatus === self::FINISHED) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.campaign_finished'), ''));
        }
        if ($campaignStatus === self::CREATED && $dataStatus !== self::STARTED) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.status_campaign_not_started'), ''));
        }
        if ($campaign->questions()->count() === 0) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.campaign_change_status_no_questions'), ''));
        }
    }

    public function validateCampaign(array $data)
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
            'name' => 'sometimes|required|max:1000',
            'enterText' => 'nullable|max:1000',
            'endText' => 'nullable|max:1000',
            'banner' => 'nullable|image',
        ]);
    }

}
