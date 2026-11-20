<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseApiController
{
    public function me()
    {
        return $this->success(new UserResource(Auth::user()));
    }
}
