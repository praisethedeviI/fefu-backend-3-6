<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Models\ProductCategory;
use App\Sync\ExternalSyncClient;
use App\Sync\ExternalSyncFailureException;
use DB;
use Illuminate\Console\Command;
use Throwable;

class SyncProductCategoriesCommand extends Command
{
    protected $signature = 'app:sync:replace_product_categories';

    /**
     * @throws ExternalSyncFailureException
     * @throws Throwable
     */
    public function handle(ExternalSyncClient $syncClient): int
    {
        $externalCategories = $syncClient->getAllProductCategories();

        DB::transaction(static function () use ($externalCategories): void
        {
            ProductCategory::query()->update(['is_present_in_external_sources' => false]);

            $categoryExternalIdSet = [];

            foreach ($externalCategories as $externalCategory) {
                $categoryExternalIdSet[] = $externalCategory->externalId;
            }

            $categoryByExternalId = ProductCategory::query()
                ->whereIn('external_id', $categoryExternalIdSet)
                ->get()
                ->keyBy('external_id');

            foreach ($externalCategories as $externalCategory) {
                $category = $categoryByExternalId[$externalCategory->externalId] ?? new ProductCategory();
                $category->external_id = $externalCategory->externalId;
                $category->is_present_in_external_sources = true;
                $category->name = $externalCategory->name;
                $category->parent_id = null;
                $category->save();

                $categoryByExternalId[$category->external_id] = $category;
            }

            foreach ($externalCategories as $externalCategory) {
                if ($externalCategory->parentExternalId !== null) {
                    $category = $categoryByExternalId[$externalCategory->externalId];
                    $category->parent_id = $categoryByExternalId[$externalCategory->parentExternalId]->id;
                    $category->save();
                }
            }
        });

        return 0;
    }
}
