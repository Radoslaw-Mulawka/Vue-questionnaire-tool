<?php

namespace App\Http\Resources;

use App\Laravue\Models\Campaign;
use App\Laravue\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceForAdmin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $campaign = Campaign::withoutGlobalScopes()->where('users_id', $this->id)->get();

        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'roles' => array_map(
                function ($role) {
                    return $role['name'];
                },
                $this->roles->toArray()
            ),
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->getAllPermissions()->toArray()
            ),
            'campaigns' => count($campaign)
        ];
    }
}
