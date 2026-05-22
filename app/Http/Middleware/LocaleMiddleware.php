<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? config('app.locale');

        if (!in_array($locale, config('app.supported_locales', ['vi']))) {
            abort(404);
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // Remove {locale} from route parameters so controllers don't receive it
        // positionally (callAction spreads array_values, which would shift all params).
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
