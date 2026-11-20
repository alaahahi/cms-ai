<?php

namespace App\Console\Commands;

use App\Services\Accounting\InvoiceService;
use Illuminate\Console\Command;

class SyncInvoicesFromLegacy extends Command
{
    protected $signature = 'invoices:sync-from-legacy';

    protected $description = 'Sync Order rows into Invoice module';

    public function handle(InvoiceService $invoices): int
    {
        $count = $invoices->syncOrdersToInvoices();
        $this->info("Synced {$count} invoices from orders.");

        return self::SUCCESS;
    }
}
