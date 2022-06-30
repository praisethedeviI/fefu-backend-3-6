<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseUpdateProfileFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\OpenApi\Parameters\UpdateUserInfoParameters;
use App\OpenApi\Responses\Auth\UnauthenticatedResponse;
use App\OpenApi\Responses\UserProfileResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Illuminate\Support\Facades\Auth;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ProfileController extends Controller
{
    /**
     * Return current user info.
     *
     * @return UserResource
     */
    #[OpenApi\Operation(tags: ['profile'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[OpenApi\Response(factory: UserProfileResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnauthenticatedResponse::class, statusCode: 401)]
    public function show(): UserResource
    {
        return UserResource::make(Auth::user());
    }

    /**
     * Update email and nickname of user.
     *
     * @param BaseUpdateProfileFormRequest $request
     * @return UserResource
     */
    #[OpenApi\Operation(tags: ['profile'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[OpenApi\Response(factory: UserProfileResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnauthenticatedResponse::class, statusCode: 401)]
    #[OpenApi\Parameters(factory: UpdateUserInfoParameters::class)]
    public function update(BaseUpdateProfileFormRequest $request): UserResource
    {
        $data = $request->validated();

        /** @var User $user */
        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return UserResource::make($user);
    }
}
