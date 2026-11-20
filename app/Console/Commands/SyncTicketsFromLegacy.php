<?php

namespace App\Console\Commands;

use App\Services\CustomerService\TicketService;
use Illuminate\Console\Command;

class SyncTicketsFromLegacy extends Command
{
    protected $signature = 'tickets:sync-from-legacy';

    protected $description = 'Sync PendingRequest rows into Ticket module';

    public function handle(TicketService $tickets): int
    {
        $count = $tickets->syncFromPendingRequests();
        $this->info("Synced {$count} tickets from pending requests.");

        return self::SUCCESS;
    }
}
