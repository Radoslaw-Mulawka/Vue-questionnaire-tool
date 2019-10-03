<?php


namespace App\Http\Resources;


use App\Laravue\Models\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResources extends JsonResource
{
    public function toArray($request)
    {
        /**
         * @var Option $this
         */
        return [
            "id" => $this->id,
            "optionText" => $this->getLabel()
        ];
    }
}
