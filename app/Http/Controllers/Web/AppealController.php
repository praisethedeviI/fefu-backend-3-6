<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseAppealFormRequest;
use App\Mail\AppealCreated;
use App\Models\Appeal;
use App\Models\Settings;
use App\Sanitizers\PhoneSanitizer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Mail;

class AppealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function form(): Factory|View|Application
    {
        return view('appeal', ['success' => session('success', false)]);
    }

    /**
     * Send form.
     *
     * @param BaseAppealFormRequest $request
     * @return Application|Factory|View
     */
    public function send(BaseAppealFormRequest $request): Redirector|Application|RedirectResponse
    {
        $data = $request->validated();

        $appeal = new Appeal();
        $appeal->name = $data['name'];
        $appeal->phone = PhoneSanitizer::sanitize($data['phone']);
        $appeal->email = $data['email'];
        $appeal->message = $data['message'];
        $appeal->save();

        Mail::to(app(Settings::class)->admin_email)->queue(new AppealCreated($appeal));

        return redirect(route('appeal.form'))->with(['success' => true]);
    }
}
