<?php

namespace App\Console\Commands;

use App\Services\Provider\LegacyProviderSyncService;
use Illuminate\Console\Command;

class SyncProvidersFromLegacy extends Command
{
    protected $signature = 'providers:sync-from-legacy';

    protected $description = 'Sync Category/CardService/Results into Provider module tables';

    public function handle(LegacyProviderSyncService $sync): int
    {
        $this->info('Syncing provider network from legacy tables...');
        $result = $sync->syncAll();
        $this->info("Synced {$result['categories']} categories, {$result['providers']} providers, {$result['visits']} visits.");

        return self::SUCCESS;
    }
}
