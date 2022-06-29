<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseLoginFormRequest;
use App\Http\Requests\BaseRegisterFormRequest;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{
    public function loginForm(): Factory|View|Application
    {
        return view('auth.login');
    }

    public function registerForm(): Factory|View|Application
    {
        return view('auth.register');
    }

    public function login(BaseLoginFormRequest $request): Redirector|Application|RedirectResponse
    {
        $data = $request->validated();

        if (Auth::attempt($data, true)) {
            $request->session()->regenerate();

            return redirect(route('profile.show'));
        }

        return back()->withErrors([
            'password' => 'Invalid email or password',
        ]);
    }

    public function logout(Request $request): Redirector|Application|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function register(BaseRegisterFormRequest $request): Redirector|Application|RedirectResponse
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

        Auth::login($user);
        $request->session()->regenerate();

        return redirect(route('profile.show'));
    }
}
