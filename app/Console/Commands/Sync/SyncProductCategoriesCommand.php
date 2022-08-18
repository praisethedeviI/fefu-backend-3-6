<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Sync\ExternalSyncClient;
use Illuminate\Console\Command;

class SyncProductCategoriesCommand extends Command
{
    protected $signature = 'app:sync:replace_product_categories';

    /**
     * @throws \App\Sync\ExternalSyncFailureException
     */
    public function handle(ExternalSyncClient $syncClient): int
    {
        $externalCategories = $syncClient->getAllProductCategories();

        var_dump($externalCategories);


        return 0;
    }
}
