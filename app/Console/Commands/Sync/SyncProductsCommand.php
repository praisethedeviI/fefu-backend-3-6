<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Models\Settings;
use App\Sync\ExternalProductsBatch;
use App\Sync\ExternalSyncClient;
use DB;
use Illuminate\Console\Command;

class SyncProductsCommand extends Command
{
    protected $signature = 'app:sync:update_products';

    public function handle(ExternalSyncClient $syncClient, Settings $settings): int
    {
        $externalProductBatch = $syncClient->getUpdatedProducts($settings->external_sync_products_update_token);

        DB::transaction(function () use ($settings, $externalProductBatch) {
            $this->importBatch($externalProductBatch, $settings->external_sync_products_update_token === 0);

            $settings->external_sync_products_update_token = $externalProductBatch->newUpdateToken;
            $settings->save();
        });

        return 0;
    }

    private function importBatch(ExternalProductsBatch $batch, bool $fresh): void
    {
        if ($fresh) {
            Product::query()->update(['is_present_in_external_sources' => false]);
        }

        $categoryExternalIdSet = [];
        $attributeExternalIdSet = [];
        $productExternalIdSet = [];

        foreach ($batch->products as $externalProduct) {
            $categoryExternalIdSet[$externalProduct->externalProductCategoryId] = true;
            $productExternalIdSet[$externalProduct->externalId] = true;

            foreach ($externalProduct->attributeValues as $externalProductAttributeValue) {
                $attributeExternalIdSet[$externalProductAttributeValue->externalProductAttributeId] = true;
            }
        }

        $categoryIdByExternalId = ProductCategory::query()
            ->whereIn('external_id', array_keys($categoryExternalIdSet))
            ->pluck('id', 'external_id');

        $attributeIdByExternalId = ProductAttribute::query()
            ->whereIn('external_id', array_keys($attributeExternalIdSet))
            ->pluck('id', 'external_id');

        $productIdByExternalId = Product::query()
            ->whereIn('external_id', array_keys($productExternalIdSet))
            ->get()
            ->keyBy('external_id');

        foreach ($batch->products as $externalProduct) {
            $product = $productIdByExternalId[$externalProduct->externalId] ?? new Product();
            $product->external_id = $externalProduct->externalId;
            $product->is_present_in_external_sources = !$externalProduct->isDeleted;
            $product->name = $externalProduct->name;
            $product->price = $externalProduct->price;
            $product->description = $externalProduct->description;
            $product->product_category_id = $categoryIdByExternalId[$externalProduct->externalProductCategoryId];
            $product->save();

            $product->attributeValues()->delete();

            foreach ($externalProduct->attributeValues as $externalProductAttributeValue) {
                $attributeId = $attributeIdByExternalId[$externalProductAttributeValue->externalProductAttributeId];

                $attributeValue = new ProductAttributeValue();
                $attributeValue->product_id = $product->id;
                $attributeValue->product_attribute_id = $attributeId;
                $attributeValue->value = $externalProductAttributeValue->value;
                $attributeValue->save();
            }
        }
    }

}
