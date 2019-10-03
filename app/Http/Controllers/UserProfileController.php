<?php


namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Laravue\Models\Campaign;
use App\Services\UserProfileService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * @var UserProfileService
     */
    private $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    public function show()
    {
        $user = auth()->user();
        return new UserProfileResource($user);
    }

    public function update(Request $request)
    {
        return $this->userProfileService->update($request);
    }

    public function updateBanner(Request $request)
    {
        return $this->userProfileService->updateBanner($request);
    }

    public function destroyBanner()
    {
        return $this->userProfileService->removeBanner();
    }

    public function changePassword(Request $request)
    {
        return $this->userProfileService->changePassword($request);
    }
    public function destroy()
    {
        return $this->userProfileService->deleteUser();
    }

}
