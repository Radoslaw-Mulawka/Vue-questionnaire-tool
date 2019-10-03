<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'roles' => array_map(function ($role) {
                return $role['name'];
            }, $this->roles->toArray()),
            'permissions' => array_map(function ($permission) {
                return $permission['name'];
            }, $this->getAllPermissions()->toArray()),
            'avatar' => 'http://i.pravatar.cc',
            'createdAt' => $this->created_at,
            'companyName' => $this->company_name,
            'companyAddress' => $this->company_address,
            'companyLogo' => is_null($this->logo) ? null : Storage::url($this->logo),
        ];
    }
}
