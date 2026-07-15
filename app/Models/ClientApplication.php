<?php

namespace App\Models;

use Laravel\Passport\Client;

class ClientApplication extends Client
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'secret',
        'provider',
        'redirect_uris',
        'grant_types',
        'revoked',
        'description',
        'status',
    ];

    /**
     * Determine if the client should skip the authorization prompt.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $scopes
     * @return bool
     */
    public function skipsAuthorization(\Illuminate\Contracts\Auth\Authenticatable $user, array $scopes): bool
    {
        // For a seamless SSO experience, we can skip the authorization prompt
        // for all active internal clients. 
        return $this->status === 'active';
    }
}
