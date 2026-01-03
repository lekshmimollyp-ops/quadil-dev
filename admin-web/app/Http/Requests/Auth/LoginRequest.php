<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        try {
            $response = \Illuminate\Support\Facades\Http::post(config('services.gateway.url') . '/auth/api/v1/login', [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($response->failed()) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => $response->json('message') ?? trans('auth.failed'),
                ]);
            }

            $data = $response->json();
            $remoteUser = $data['user'];
            $token = $data['access_token'];

            // Sync user locally
            $user = \App\Models\User::updateOrCreate(
                ['email' => $remoteUser['email']],
                [
                    'name' => $remoteUser['name'],
                    // We don't store the password, but Laravel's Authenticatable needs something.
                    // We can store a random string or the hashed password if we really wanted to, 
                    // but we'll use the remote service for authentication.
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                ]
            );

            // Log the user in locally
            Auth::login($user, $this->boolean('remember'));

            // Store the remote token in the session
            session(['remote_token' => $token]);

            RateLimiter::clear($this->throttleKey());

        } catch (\Exception $e) {
            if ($e instanceof ValidationException) throw $e;

            throw ValidationException::withMessages([
                'email' => 'Authentication service is currently unavailable. ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
