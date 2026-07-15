<?php

namespace App\Observers;

use App\Models\ClientApplication;
use App\Services\AuditLogger;

class ClientApplicationObserver
{
    /**
     * Handle the ClientApplication "created" event.
     */
    public function created(ClientApplication $client): void
    {
        AuditLogger::log(auth()->id(), 'Client Created', 'Created client application: ' . $client->name);
    }

    /**
     * Handle the ClientApplication "updated" event.
     */
    public function updated(ClientApplication $client): void
    {
        // Ignore silent updates if we just regenerated secret etc, though secret generation should be logged too.
        AuditLogger::log(auth()->id(), 'Client Updated', 'Updated client application: ' . $client->name);
    }

    /**
     * Handle the ClientApplication "deleted" event.
     */
    public function deleted(ClientApplication $client): void
    {
        AuditLogger::log(auth()->id(), 'Client Deleted', 'Deleted client application: ' . $client->name);
    }
}
