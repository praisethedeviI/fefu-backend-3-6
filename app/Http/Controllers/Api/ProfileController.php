<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseUpdateProfileFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    /**
     * @return UserResource
     */
    public function show(): UserResource
    {
        return UserResource::make(Auth::user());
    }

    /**
     * @param BaseUpdateProfileFormRequest $request
     * @return UserResource
     */
    public function update(BaseUpdateProfileFormRequest $request)
    {
        $data = $request->validated();

        /** @var User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return $this->show();
    }
}
