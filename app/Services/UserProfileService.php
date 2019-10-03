<?php


namespace App\Services;


use App\Laravue\Models\Answer;
use App\Laravue\Models\Campaign;
use App\Laravue\Models\CampaignPlaces;
use App\Laravue\Models\Option;
use App\Laravue\Models\Places;
use App\Laravue\Models\Question;
use App\Laravue\Models\User;
use App\Traits\ApiResponser;
use App\Utils\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class UserProfileService
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

    public function update(Request $request)
    {
        $user = auth()->user();
        $accessFields = [
            "firstName" => "first_name",
            "lastName" => "last_name",
            "companyName" => "company_name",
            "companyAddress" => "company_address",
        ];

        if (count($request->all()) !== 1) {
            throw new ValidationException(null, $this->respondWithError(trans('app.too_many_fields'), Res::HTTP_BAD_REQUEST));
        }

        $fieldFromRequest = $request->keys()[0];

        $this->validationHelper->checkValidFieldFromAccessFields($fieldFromRequest, $accessFields);

        $originFieldToChange = $accessFields[$fieldFromRequest];

        $data = $this->validationHelper->getArrayIfKeyIsValid($request, $fieldFromRequest);

        $this->validateProfileData($data);

        $user->fill([$originFieldToChange => $data[$fieldFromRequest]]);

        $user->save();
        return $this->respondSuccess('', trans('app.profile_update'));
    }

    public function updateBanner(Request $request)
    {
        $user = auth()->user();
        $logoAcceptedFields = ['_method', 'logo'];

        $data = '';
        foreach ($logoAcceptedFields as $key => $value) {
            $data = $this->validationHelper->getArrayIfKeyIsValid($request, $value);
        }

        $this->validateProfileData($data);

        $user->setLogo($this->uploadImage($request->file('logo'), $user) ?: ' ');
        $bannerLink = Storage::url($user->getLogo());

        $user->save();
        return $this->respondSuccess([$bannerLink], trans('app.profile_update'));
    }

    public function removeBanner()
    {
        /** @var User $user */
        $user = User::find(Auth::id());
        $path = $user->getLogo();
        if (!$path) {
            return $this->respondSuccessNoContent();
        }
        $this->deleteBannerFromDisk($path);
        $user->setLogo(null);
        $user->save();
        return $this->respondSuccess('', trans('app.banner_delete'));
    }

    public function deleteUser()
    {
        $user = auth()->user();
        $userId = $user->id;
        $campaignList = Campaign::where('users_id', $userId)->pluck('id')->toArray();
        Answer::whereIn('campaigns_id', $campaignList)->delete();
        Option::where('users_id', $userId)->delete();
        Question::where('users_id', $userId)->delete();
        Campaign::where('users_id', $userId)->delete();
        CampaignPlaces::where('users_id', $userId)->delete();
        Places::where('users_id', $userId)->delete();
        $user->delete();
        Auth::guard()->logout();
        return $this->respondSuccess(null, trans('app.profile_delete'));
    }

    private function uploadImage($file, $user, $width = 690, $height = 260)
    {
        $folderName = sha1('katalog' . $user->id);

        $filePath = $file->hashName();

        if (!Storage::disk('public')->exists($folderName)) {
            Storage::disk('public')->makeDirectory($folderName);
        }

        if (!empty($user->Logo) && Storage::disk('public')->exists($user->Logo)) {
            Storage::disk('public')->delete($user->Logo);
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

    public function validateProfileData(array $data)
    {
        $validator = $this->validateRequest($data);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new ValidationException(null, $this->respondValidationError($errorMessage, ''));
        }

        return true;
    }

    public function changePassword(Request $request)
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();
        $validator = $this->validatePasswordChange($data);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new ValidationException(null, $this->respondValidationError($errorMessage, ''));
        }
        /** @var User $user */
        $user = User::find(Auth::id());
        if (!Hash::check($request->oldPassword, $user->getAuthPassword())) {
            throw new ValidationException(null, $this->respondValidationError(trans('passwords.current_incorrect'), ''));
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return $this->respondSuccess(null, trans('passwords.change'));
    }

    private function deleteBannerFromDisk(string $path)
    {
        $diskFolder = 'public';
        if (Storage::disk($diskFolder)->exists($path)) {
            Storage::disk($diskFolder)->delete($path);
        }
    }

    private function validateRequest(array $data)
    {
        return Validator::make($data, [
            'firstName' => 'sometimes|required|max:1000',
            'lastName' => 'sometimes|required|max:1000',
            'companyName' => 'nullable|max:1000',
            'companyAddress' => 'nullable|max:1000',
            'logo' => 'nullable|image',
        ]);
    }

    private function validatePasswordChange(array $data)
    {
        return Validator::make($data, [
            'oldPassword' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
    }
}
