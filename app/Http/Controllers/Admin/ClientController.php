<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClientService;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $clients = $this->clientService->getAllPaginated(15, $search);
        return view('admin.clients.index', compact('clients', 'search'));
    }

    public function generateSecret($id)
    {
        $client = $this->clientService->generateSecret($id);
        session()->flash('raw_secret', $client->plainSecret);
        session()->flash('plain_secret', $client->plainSecret);
        session()->flash('new_client_name', $client->name);
        return redirect()->route('admin.clients.index')->with('success', 'Secret berhasil digenerate.');
    }

    public function regenerateSecret($id)
    {
        $client = $this->clientService->regenerateSecret($id);
        session()->flash('raw_secret', $client->plainSecret);
        session()->flash('plain_secret', $client->plainSecret);
        session()->flash('new_client_name', $client->name);
        return redirect()->route('admin.clients.index')->with('success', 'Secret berhasil diperbarui.');
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(\App\Http\Requests\Admin\ClientStoreRequest $request)
    {
        $validated = $request->validated();
        if (empty($validated['status'])) {
            $validated['status'] = 'active';
        }
        $client = $this->clientService->createClient($validated);
        session()->flash('raw_secret', $client->plainSecret);
        session()->flash('plain_secret', $client->plainSecret);
        session()->flash('new_client_name', $client->name);
        return redirect()->route('admin.clients.index')->with('success', 'Aplikasi Klien <strong>' . e($client->name) . '</strong> berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $client = $this->clientService->findById($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(\App\Http\Requests\Admin\ClientUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->clientService->updateClient($id, $validated);
        return redirect()->route('admin.clients.index')->with('success', 'Aplikasi Klien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->clientService->deleteClient($id);
        return redirect()->route('admin.clients.index')->with('success', 'Aplikasi Klien berhasil dihapus.');
    }
}
