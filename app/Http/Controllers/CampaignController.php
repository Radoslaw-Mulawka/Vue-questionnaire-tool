<?php


namespace App\Http\Controllers;

use App\Http\Resources\CampaignDetailsResources;
use App\Http\Resources\CampaignResources;
use App\Laravue\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;


class CampaignController extends Controller
{

    const ITEM_PER_PAGE = 15;

    /**
     * @var CampaignService
     */
    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * Display a listing of the campaigns resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {

        $searchParams = $request->all();
        $campaignQuery = Campaign::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($keyword)) {
            $campaignQuery->where('name', 'LIKE', '%' . $keyword . '%');
        }

        return CampaignResources::collection($campaignQuery->paginate($limit));
    }

    public function show(Campaign $campaign)
    {
        $filteredCampaignDetails = new CampaignDetailsResources($campaign);
        return $this->respondSuccess($filteredCampaignDetails, '');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        return $this->campaignService->create($request);
    }

    public function update(Campaign $campaign, Request $request)
    {
        return $this->campaignService->update($campaign, $request);
    }

    public function destroy(Campaign $campaign)
    {
        return $this->campaignService->deleteCampaign($campaign);
    }

    public function updateStatus(Campaign $campaign, Request $request)
    {
        return $this->campaignService->updateStatus($campaign, $request);
    }

    public function updateBanner(Campaign $campaign, Request $request)
    {
        return $this->campaignService->updateBanner($campaign, $request);
    }

    public function destroyBanner(Campaign $campaign)
    {
        return $this->campaignService->removeBanner($campaign);
    }
}

