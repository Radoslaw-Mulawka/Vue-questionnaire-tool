<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'avatar' => 'http://i.pravatar.cc',
            'createdAt' => $this->created_at,
            'companyName' => $this->company_name,
            'companyAddress' => $this->company_address,
            'companyLogo' => $this->company_logo,
        ];
    }
}
