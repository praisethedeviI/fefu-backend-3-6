<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class OAuthController extends Controller
{
    private const ALLOWED_PROVIDERS = [
        'github',
        'discord',
    ];

    private function processOAuthException(Throwable $exception, string $provider): Factory|View|Application
    {
        Log::error($exception->getMessage());
        return view('auth.oauth_login_failed', ['provider' => $provider, 'error' => $exception->getMessage()]);
    }

    private function getValidatedProvider(string $provider): string
    {
        if (!in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            abort(404);
        }
        return $provider;
    }

    public function redirectToService(string $provider)
    {
        $provider = $this->getValidatedProvider($provider);
        try {
            $result = Socialite::driver($provider)
                ->scopes(config('services.' . $provider . '.scopes'))
                ->redirectUrl(route('oauth.login', ['provider' => $provider]))
                ->redirect();
        } catch (Throwable $exception) {
            $result = $this->processOAuthException($exception, $provider);
        }

        return $result;
    }

    public function login(Request $request, string $provider): Factory|View|Redirector|RedirectResponse|Application
    {
        $provider = $this->getValidatedProvider($provider);

        try {
            $oauthUser = Socialite::driver($provider)
                ->redirectUrl(route('oauth.login', ['provider' => $provider]))
                ->user();
        } catch (Throwable $exception) {
            return $this->processOAuthException($exception, $provider);
        }

        $dbUserByOauthId = User::where("{$provider}_id", $oauthUser->id)->first();
        $dbUserByOauthEmail = User::query()
            ->whereNull("{$provider}_id")
            ->whereNotNull('email')
            ->where('email', $oauthUser->email)
            ->first();
        $oauthUserData = [
            "{$provider}_id" => $oauthUser->getId(),
            "{$provider}_logged_in_at" => Carbon::now()
        ];
        if ($dbUserByOauthId !== null) {
            $dbUserByOauthId->update($oauthUserData);
            $appAuthUser = $dbUserByOauthId;
        } else {
            $oauthUserData = [
                "{$provider}_id" => $oauthUser->getId(),
                "{$provider}_logged_in_at" => Carbon::now(),
                "{$provider}_registered_at" => Carbon::now(),
            ];
            if ($dbUserByOauthEmail !== null) {
                $dbUserByOauthEmail->update($oauthUserData);
                $appAuthUser = $dbUserByOauthEmail;
            } else {
                $appAuthUser = User::create(array_merge([
                    'name' => $oauthUser->getName() ?? $oauthUser->getNickname(),
                    'email' => $oauthUser->getEmail(),

                ], $oauthUserData));
            }
        }

        Auth::login($appAuthUser);
        $request->session()->regenerate();

        return redirect(route('profile.show'));
    }
}
