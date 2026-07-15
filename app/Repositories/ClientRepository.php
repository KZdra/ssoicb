<?php

namespace App\Repositories;

use App\Models\ClientApplication;

class ClientRepository extends BaseRepository
{
    public function __construct(ClientApplication $client)
    {
        parent::__construct($client);
    }

    public function getPaginated(int $perPage = 15, string $search = null)
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
