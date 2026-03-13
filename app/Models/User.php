<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'name',
        'email',
        'avatar',
        'nickname',
    ];

    protected $hidden = [
        'provider_token',
        'provider_refresh_token',
    ];

    /**
     * Find or create a user from an OAuth provider response.
     */
    public static function findOrCreateFromProvider(string $provider, $socialUser): self
    {
        return self::updateOrCreate(
            [
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
            ],
            [
                'name'                  => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Unknown',
                'email'                 => $socialUser->getEmail(),
                'avatar'                => $socialUser->getAvatar(),
                'nickname'              => $socialUser->getNickname(),
                'provider_token'        => $socialUser->token,
                'provider_refresh_token'=> $socialUser->refreshToken,
            ]
        );
    }
}
