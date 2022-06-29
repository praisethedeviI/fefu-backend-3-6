<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseUpdateProfileFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request): Factory|View|Application
    {
        return view('profile.index', ['user' => (new UserResource(Auth::user()))->toArray($request)]);
    }

    public function update(BaseUpdateProfileFormRequest $request): Redirector|Application|RedirectResponse
    {
        $data = $request->validated();

        /** @var User $user */
        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return redirect(route('profile.show'));
    }
}
