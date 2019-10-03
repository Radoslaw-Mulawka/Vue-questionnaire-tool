<?php


namespace App\Http\Resources;


use App\Laravue\Models\Campaign;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CampaignDetailsResources extends JsonResource
{

    public function toArray($request)
    {
        /**
         * @var Campaign $this
         */

        return [
            'id' => $this->id,
            'dateFrom' => $this->getDateFrom(),
            'dateTo' => $this->getDateTo(),
            'name' => $this->getName(),
            'banner' => is_null($this->getLogo()) ? null : Storage::url($this->getLogo()),
            'enterText' => $this->getIntroText(),
            'endText' => $this->getEndingText(),
            'status' => $this->getStatus(),
            'questionsList' => QuestionResources::collection($this->questions),
        ];
    }
}

