<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AboutPageController;
use App\Mail\ContactAutoReply;
use App\Mail\ContactReceived;
use App\Mail\SubscriberWelcome;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\Subscriber;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about', ['aboutPage' => AboutPageController::getAboutPage()]);
    }
    public function contact(): View { return view('pages.contact'); }
    public function offers(): View  { return view('pages.offers'); }
    public function landing(): View { return view('pages.landing'); }
    public function privacyPolicy(): View { return view('pages.privacy-policy'); }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|max:255']);

        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'subscribe')) {
            return response()->json(['message' => 'Xác minh bảo mật thất bại. Vui lòng thử lại.'], 422);
        }

        if (Subscriber::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email này đã được đăng ký.'], 409);
        }

        Subscriber::create(['email' => $request->email]);

        try {
            Mail::to($request->email)->send(new SubscriberWelcome());
        } catch (\Exception) {
            // Mail failure must not break the subscription
        }

        return response()->json(['message' => 'Đăng ký thành công! Cảm ơn bạn.']);
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|max:255',
            'msg'    => 'required|string|max:2000',
            'source' => 'nullable|string|in:contact_page,home_extra_service',
        ]);

        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'contact')) {
            return back()->withInput()->with('error', 'Xác minh bảo mật thất bại. Vui lòng thử lại.');
        }

        ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->msg,
            'source'  => $request->input('source', 'contact_page'),
        ]);

        try {
            $adminEmail = SiteSetting::get('email', config('mail.from.address'));
            Mail::to($adminEmail)->send(new ContactReceived(
                senderName:  $request->name,
                senderEmail: $request->email,
                messageText: $request->msg,
                source:      $request->input('source', 'contact_page'),
            ));
            Mail::to($request->email)->send(new ContactAutoReply(
                senderName:  $request->name,
                messageText: $request->msg,
            ));
        } catch (\Exception) {
            // Mail failure must not break contact submission
        }

        return back()
            ->withInput($request->only('source'))
            ->with('success', 'Cảm ơn bạn! Thông tin đã được gửi, chúng tôi sẽ liên hệ lại sớm.');
    }
}
