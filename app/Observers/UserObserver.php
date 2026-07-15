<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AuditLogger;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        AuditLogger::log(auth()->id(), 'User Created', 'Created user: ' . $user->email);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        AuditLogger::log(auth()->id(), 'User Updated', 'Updated user: ' . $user->email);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        AuditLogger::log(auth()->id(), 'User Deleted', 'Deleted user: ' . $user->email);
    }
}
