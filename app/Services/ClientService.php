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

    public function getAllPaginated(int $perPage = 15, string $search = null)
    {
        return $this->clientRepository->getPaginated($perPage, $search);
    }

    public function createClient(array $data)
    {
        $redirects = preg_split('/[\r\n,]+/', $data['redirect']);
        $redirects = array_values(array_filter(array_map('trim', $redirects)));
        if (empty($redirects)) {
            $redirects = [$data['redirect']];
        }

        // Use Passport's repository to create the client and generate secret
        $client = $this->passportClientRepository->createAuthorizationCodeGrantClient(
            $data['name'],
            $redirects,
            true // confidential
        );

        // Update custom fields
        $client->description = $data['description'] ?? null;
        $client->status = $data['status'] ?? 'active';
        $client->save();

        return $client;
    }

    public function updateClient($id, array $data)
    {
        $client = $this->clientRepository->findById($id);
        
        $redirects = preg_split('/[\r\n,]+/', $data['redirect']);
        $redirects = array_values(array_filter(array_map('trim', $redirects)));
        if (empty($redirects)) {
            $redirects = [$data['redirect']];
        }

        $client->name = $data['name'];
        $client->redirect_uris = $redirects;
        $client->description = $data['description'] ?? null;
        $client->status = $data['status'] ?? 'active';
        $client->save();

        return $client;
    }

    public function deleteClient($id)
    {
        $client = $this->clientRepository->findById($id);
        $name = $client->name;
        
        $this->clientRepository->delete($id);
        
        return true;
    }

    public function generateSecret($id)
    {
        $client = $this->clientRepository->findById($id);
        $plainSecret = \Illuminate\Support\Str::random(40);
        $client->secret = $plainSecret;
        $client->save();

        AuditLogger::log(auth()->id(), 'Client Secret Generated', 'Generated secret for client application: ' . $client->name);
        
        $client->plainSecret = $plainSecret;
        return $client;
    }

    public function regenerateSecret($id)
    {
        $client = $this->clientRepository->findById($id);
        $plainSecret = \Illuminate\Support\Str::random(40);
        $client->secret = $plainSecret;
        $client->save();

        AuditLogger::log(auth()->id(), 'Client Secret Regenerated', 'Regenerated secret for client application: ' . $client->name);
        
        $client->plainSecret = $plainSecret;
        return $client;
    }

    public function findById($id)
    {
        return $this->clientRepository->findById($id);
    }
}
