<?php

namespace App\Repositories;

use App\Models\ClientApplication;

class ClientRepository extends BaseRepository
{
    public function __construct(ClientApplication $client)
    {
        parent::__construct($client);
    }
}
