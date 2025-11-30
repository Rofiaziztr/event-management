<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
            'login' => ['required', 'string', function ($attribute, $value, $fail) {
                // Check if it's a valid email or a valid username (NIP format)
                if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^[0-9]{9,18}$/', $value)) {
                    $fail('Field login harus berupa alamat email yang valid atau NIP (9-18 digit).');
                }
            }],
            'password' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Accept both 'email' and 'login' for compatibility with tests and forms
        if (! $this->has('login') && $this->has('email')) {
            $this->merge(['login' => $this->input('email')]);
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->string('login');
        // debug info
        \Illuminate\Support\Facades\Log::debug('LoginRequest.authenticate: login value', ['login' => $login, 'input' => $this->all()]);

        // Determine if login is email or NIP
        $credentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $this->string('password')]
            : ['nip' => $login, 'password' => $this->string('password')];

        Log::info('Attempting authentication', ['credentials' => array_key_exists('email', $credentials) ? ['email' => $credentials['email']] : ['nip' => $credentials['nip']]]);
        // temporarily throw to inspect value
        // throw new \Exception('Attempting auth with: ' . json_encode($credentials));
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
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
            'login' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string('login')) . '|' . $this->ip());
    }
}
