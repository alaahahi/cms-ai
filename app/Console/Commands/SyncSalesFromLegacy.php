<?php

namespace App\Console\Commands;

use App\Services\Sales\LegacySalesSyncService;
use Illuminate\Console\Command;

class SyncSalesFromLegacy extends Command
{
    protected $signature = 'sales:sync-from-legacy';

    protected $description = 'Sync sales reps and sales from legacy users/profiles';

    public function handle(LegacySalesSyncService $sync): int
    {
        $this->info('Syncing sales module from legacy data...');
        $result = $sync->syncAll();
        $this->info("Synced {$result['reps']} sales reps and {$result['sales']} sales.");

        return self::SUCCESS;
    }
}
