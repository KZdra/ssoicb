<?php

namespace App\Services;

use App\Repositories\ClientRepository;
use Laravel\Passport\ClientRepository as PassportClientRepository;

class ClientService extends BaseService
{
    protected $clientRepository;
    protected $passportClientRepository;

    public function __construct(ClientRepository $clientRepository, PassportClientRepository $passportClientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->passportClientRepository = $passportClientRepository;
    }

    public function getAllPaginated(int $perPage = 15)
    {
        return $this->clientRepository->getPaginated($perPage);
    }

    public function createClient(array $data)
    {
        // Use Passport's repository to create the client and generate secret
        $client = $this->passportClientRepository->create(
            null,
            $data['name'],
            $data['redirect'],
            false,
            false
        );

        // Update custom fields
        $client->description = $data['description'] ?? null;
        $client->status = $data['status'] ?? 'active';
        $client->save();

        AuditLogger::log(auth()->id(), 'Client Created', 'Created client application: ' . $client->name);

        return $client;
    }

    public function updateClient($id, array $data)
    {
        $client = $this->clientRepository->findById($id);
        
        $client->name = $data['name'];
        $client->redirect = $data['redirect'];
        $client->description = $data['description'] ?? null;
        $client->status = $data['status'] ?? 'active';
        $client->save();

        AuditLogger::log(auth()->id(), 'Client Updated', 'Updated client application: ' . $client->name);

        return $client;
    }

    public function deleteClient($id)
    {
        $client = $this->clientRepository->findById($id);
        $name = $client->name;
        
        $this->clientRepository->delete($id);
        
        AuditLogger::log(auth()->id(), 'Client Deleted', 'Deleted client application: ' . $name);
        
        return true;
    }

    public function findById($id)
    {
        return $this->clientRepository->findById($id);
    }
}
