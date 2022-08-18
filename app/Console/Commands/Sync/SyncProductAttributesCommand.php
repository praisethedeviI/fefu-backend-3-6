<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Models\ProductAttribute;
use App\Sync\ExternalSyncClient;
use App\Sync\ExternalSyncFailureException;
use DB;
use Illuminate\Console\Command;
use Throwable;

class SyncProductAttributesCommand extends Command
{
    protected $signature = 'app:sync:replace_product_attributes';

    /**
     * @throws ExternalSyncFailureException
     * @throws Throwable
     */
    public function handle(ExternalSyncClient $syncClient): int
    {
        $externalAttributes = $syncClient->getAllProductAttributes();

        DB::transaction(static function () use ($externalAttributes): void
        {
            ProductAttribute::query()->update(['is_present_in_external_sources' => false]);

            foreach ($externalAttributes as $i => $externalAttribute) {
                $attribute = ProductAttribute::query()->where('external_id', $externalAttribute->externalId)->first() ?? new ProductAttribute();
                $attribute->external_id = $externalAttribute->externalId;
                $attribute->is_present_in_external_sources = true;
                $attribute->name = $externalAttribute->name;
                $attribute->type = $externalAttribute->type;
                $attribute->sort_order = $i;
                $attribute->save();
            }
        });

        return 0;
    }
}
