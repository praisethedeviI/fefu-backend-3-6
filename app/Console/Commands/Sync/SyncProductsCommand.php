<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Models\Settings;
use App\Sync\ExternalSyncClient;
use DB;
use Illuminate\Console\Command;

class SyncProductsCommand extends Command
{
    protected $signature = 'app:sync:update_products';

    public function handle(ExternalSyncClient $syncClient, Settings $settings): int
    {
        $externalProductBatch = $syncClient->getUpdatedProducts($settings->external_sync_products_update_token);

        var_dump($externalProductBatch);

        DB::transaction(function () use ($settings, $externalProductBatch) {
            //TODO
            $settings->external_sync_products_update_token = $externalProductBatch->newUpdateToken;
            $settings->save();
        });

        return 0;
    }

}
