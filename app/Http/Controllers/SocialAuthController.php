<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported OAuth providers.
     */
    private const ALLOWED_PROVIDERS = ['discord', 'twitch'];

    // ──────────────────────────────────────────────────────────
    //  Step 1 – Redirect the user to the provider's OAuth page
    // ──────────────────────────────────────────────────────────

    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        // Twitch requires explicit scopes to get user profile
        if ($provider === 'twitch') {
            return Socialite::driver($provider)
                ->scopes(['openid'])
                ->redirect();
        }


        // Discord: request identify + email
        if ($provider === 'discord') {
            return Socialite::driver($provider)
                ->scopes(['identify', 'email'])
                ->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    // ──────────────────────────────────────────────────────────
    //  Step 2 – Handle the callback from the provider
    // ──────────────────────────────────────────────────────────

    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['oauth' => $e->getMessage()]);
        }

        // Find or create the local user record
        $user = User::findOrCreateFromProvider($provider, $socialUser);

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }

    // ──────────────────────────────────────────────────────────
    //  Logout
    // ──────────────────────────────────────────────────────────

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ──────────────────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────────────────

    private function validateProvider(string $provider): void
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, strict: true)) {
            abort(404, "Provider '{$provider}' is not supported.");
        }
    }
}
