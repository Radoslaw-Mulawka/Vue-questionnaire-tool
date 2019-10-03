<?php


namespace App\Http\Resources;


use App\Laravue\Models\Campaign;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResources extends JsonResource
{

    public function toArray($request)
    {
        /**
         * @var Campaign $this
         */
        return [
            'id' => $this->id,
            'name' => $this->getName(),
            'status' => $this->getStatus(),
            'date_from' => $this->getDateFrom(),
            'date_to' => $this->getDateTo(),
        ];

    }


}

