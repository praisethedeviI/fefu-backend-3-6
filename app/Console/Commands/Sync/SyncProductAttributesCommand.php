<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Sync\ExternalSyncClient;
use Illuminate\Console\Command;

class SyncProductAttributesCommand extends Command
{
    protected $signature = 'app:sync:replace_product_attributes';

    /**
     * @throws \App\Sync\ExternalSyncFailureException
     */
    public function handle(ExternalSyncClient $syncClient): int
    {
        $externalAttributes = $syncClient->getAllProductAttributes();

        var_dump($externalAttributes);



        return 0;
    }
}
