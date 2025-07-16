<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->input('g-recaptcha-response');

        if (!$token) {
            throw ValidationException::withMessages([
                'email' => ['reCAPTCHA verification failed.'],
            ]);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false) || ($data['score'] ?? 0) < 0.5) {
            throw ValidationException::withMessages([
                'email' => ['reCAPTCHA verification failed.'],
            ]);
        }

        return $next($request);
    }
}
