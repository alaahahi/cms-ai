<?php

namespace App\Console\Commands;

use App\Services\Subscription\LegacySubscriptionSyncService;
use Illuminate\Console\Command;

class SyncSubscriptionsFromLegacy extends Command
{
    protected $signature = 'subscriptions:sync-from-legacy';

    protected $description = 'Sync Card/Profile legacy data into Subscription module tables (parallel run)';

    public function handle(LegacySubscriptionSyncService $sync): int
    {
        $this->info('Syncing subscription module from legacy tables...');

        $result = $sync->syncAll();

        $this->info("Synced {$result['types']} subscription types and {$result['subscriptions']} subscriptions.");

        return self::SUCCESS;
    }
}
