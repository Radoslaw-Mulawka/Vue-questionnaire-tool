<?php


namespace App\Http\Resources;

use App\Laravue\Models\Question;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResources extends JsonResource
{

    public function toArray($request)
    {
        /**
         * @var Question $this
         */
        return [
            "id" => $this->id,
            "campaignsId" => $this->getCampaignsId(),
            "type" => $this->getOptionType(),
            "questionMainText" => $this->getQuestion(),
            "questionAdditionalText" => $this->getExtendedDesc(),
            "required" => $this->getRequire(),
            "options" => OptionResources::collection($this->options),
        ];
    }
}
