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
        // dd($clients);
        return view('admin.clients.index', compact('clients', 'search'));
    }

    public function generateSecret($id)
    {
        $this->clientService->generateSecret($id);
        return redirect()->route('admin.clients.index')->with('success', 'Secret generated successfully.');
    }

    public function regenerateSecret($id)
    {
        $this->clientService->regenerateSecret($id);
        return redirect()->route('admin.clients.index')->with('success', 'Secret regenerated successfully.');
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(\App\Http\Requests\Admin\ClientStoreRequest $request)
    {
        $validated = $request->validated();
        $this->clientService->createClient($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Client Application created successfully.');
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
        return redirect()->route('admin.clients.index')->with('success', 'Client Application updated successfully.');
    }

    public function destroy($id)
    {
        $this->clientService->deleteClient($id);
        return redirect()->route('admin.clients.index')->with('success', 'Client Application deleted successfully.');
    }
}
