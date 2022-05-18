<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginFormRequest;
use App\Http\Requests\Api\RegisterFormRequest;
use App\Models\User;
use App\OpenApi\Parameters\Auth\LoginParameters;
use App\OpenApi\Parameters\Auth\RegisterParameters;
use App\OpenApi\Responses\Auth\UnauthenticatedResponse;
use App\OpenApi\Responses\Auth\TokenSuccessResponse;
use App\OpenApi\Responses\Auth\LogoutSuccessResponse;
use App\OpenApi\Responses\Auth\ValidationFailedResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class AuthController extends Controller
{
    /**
     * Login.
     *
     * @param LoginFormRequest $request
     * @return JsonResponse
     */
    #[OpenApi\Operation(tags: ['auth'], method: 'POST')]
    #[OpenApi\Response(factory: TokenSuccessResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ValidationFailedResponse::class, statusCode: 422)]
    #[OpenApi\Parameters(factory: LoginParameters::class)]
    public function login(LoginFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (Auth::attempt($data, true))
        {
            $user = Auth::user();
            $token = $user->createToken(request()->userAgent())->plainTextToken;
            return response()->json(['token' => $token], 200);
        }
        return response()->json(['errors' => ['' => 'The provided credentials are incorrect.']], 422);
    }

    /**
     * Logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[OpenApi\Operation(tags: ['auth'], method: 'POST')]
    #[OpenApi\Response(factory: LogoutSuccessResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnauthenticatedResponse::class, statusCode: 401)]
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Register.
     *
     * @param RegisterFormRequest $request
     * @return JsonResponse
     */
    #[OpenApi\Operation(tags: ['auth'], method: 'POST')]
    #[OpenApi\Response(factory: TokenSuccessResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ValidationFailedResponse::class, statusCode: 422)]
    #[OpenApi\Parameters(factory: RegisterParameters::class)]
    public function register(RegisterFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if ($user !== null) {
            $user->updateFromRequest($data);
        } else {
            $user = User::createFromRequest($data);

        }

        $token = $user->createToken(request()->userAgent())->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
