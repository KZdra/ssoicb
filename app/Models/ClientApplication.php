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
}
