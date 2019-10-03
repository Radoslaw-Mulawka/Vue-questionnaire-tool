<?php

namespace App\Observers;

use App\Laravue\Models\Campaign;
use App\Laravue\Models\CampaignPlaces;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Auth;

class CampaignObserver
{
    /**
     * Handle the campaign "updated" event.
     *
     * @param Campaign $campaign
     * @return void
     */
    public function updated(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the campaign "created" event.
     *
     * @param Campaign $campaign
     * @return void
     * @throws \Exception
     */
    public function created(Campaign $campaign)
    {
        /** @var User $user */
        $user = User::find(Auth::id());
        /** @var CampaignPlaces $campaignPlace */
        $campaignPlace = new CampaignPlaces();
        $campaignPlace->setUsersId($user->id);

        $place = $user->places;
        $campaignPlace->setPlacesId($place->id);
        $campaignPlace->setCampaignsId($campaign->getId());
        $code = substr(bin2hex(random_bytes(5)), 5);
        $campaignPlace->setShortcode($code);
        $campaignPlace->setLabelName($place->name);
        $campaignPlace->save();
    }

    /**
     * Handle the campaign "deleted" event.
     *
     * @param Campaign $campaign
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the campaign "restored" event.
     *
     * @param Campaign $campaign
     * @return void
     */
    public function restored(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the campaign "force deleted" event.
     *
     * @param Campaign $campaign
     * @return void
     */
    public function forceDeleted(Campaign $campaign)
    {
        //
    }
}
