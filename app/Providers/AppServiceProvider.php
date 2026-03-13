<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Twitch\TwitchExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Event::listen(SocialiteWasCalled::class, DiscordExtendSocialite::class);
        \Event::listen(SocialiteWasCalled::class, TwitchExtendSocialite::class);
    }
}