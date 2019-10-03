<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OptionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $userID = auth()->user()->getAuthIdentifier();
        $builder->where('users_id', $userID);
    }
}
