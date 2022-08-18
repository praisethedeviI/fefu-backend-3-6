<?php

declare(strict_types=1);

namespace App\Sync;

use App\Enums\ProductAttributeType;
use http\Exception\RuntimeException;
use Throwable;

final class ExternalSyncClient
{
    private $syncUrl;

    public function __construct(string $syncUrl)
    {
        $this->syncUrl = $syncUrl;
    }

    /**
     * @throws ExternalSyncFailureException
     */
    private function makeRequest(string $route): array
    {
        $syncRouteUrl = "{$this->syncUrl}{$route}.json";
        $method = "GET";

        $curlOptions = [
            CURLOPT_URL => $syncRouteUrl,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => false,
            CURLOPT_CONNECTTIMEOUT_MS => 10 * 1000,
            CURLOPT_TIMEOUT_MS => 5 * 60 * 1000,
        ];

        $handle = curl_init();
        curl_setopt_array($handle, $curlOptions);
        $curlResponse = curl_exec($handle);

        if ($curlResponse === false) {
            throw new RuntimeException('Curl execution failed');
        }
//        $headerSize = (int)curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $httpCode = (int)curl_getinfo($handle, CURLINFO_HTTP_CODE);
//        $body = substr($curlResponse, $headerSize);
        $body = $curlResponse;

        if ($httpCode !== 200) {
            throw new ExternalSyncFailureException($httpCode, $body, 'Invalid response code', null);
        }

        try {
            $parsedBody = json_decode($body, true, 512  , JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw new ExternalSyncFailureException($httpCode, $body, 'Invalid response code', $exception);
        }

        return $parsedBody;
    }

    /**
     * @return ExternalProductAttribute[]
     * @throws ExternalSyncFailureException
     */
    public function getAllProductAttributes(): array
    {
        $data = $this->makeRequest('/attributes/replace')['items'];

        $attributes = [];
        foreach ($data as $item) {
            $attribute = new ExternalProductAttribute();
            $attribute->externalId = $item['external_id'];
            $attribute->name = $item['name'];
            $attribute->type = ProductAttributeType::keyToValue($item['type']);

            $attributes[] = $attribute;
        }

        return $attributes;
    }

    /**
     * @return ExternalProductCategory[]
     * @throws ExternalSyncFailureException
     */
    public function getAllProductCategories(): array
    {
        $data = $this->makeRequest('/categories/replace')['items'];

        $categories = [];
        foreach ($data as $item) {
            $category = new ExternalProductCategory();
            $category->externalId = $item['external_id'];
            $category->parentExternalId = $item['parent_external_id'];
            $category->name = $item['name'];

            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @throws ExternalSyncFailureException
     */
    public function getUpdatedProducts(int $updateToken): ExternalProductsBatch
    {
        $data = $this->makeRequest("/products/update_{$updateToken}");

        $products = [];
        foreach ($data['items'] as $item) {
            $product = new ExternalProduct();
            $product->externalId = $item['external_id'];
            $product->externalProductCategoryId = $item['category_external_id'];
            $product->name = $item['name'];
            $product->price = (int)$item['price'];
            $product->description = $item['description'];

            foreach ($item['attribute_values'] as $itemAttributeValue) {
                $attributeValue = new ExternalProductAttributeValue();
                $attributeValue->value = $itemAttributeValue['value'];
                $attributeValue->externalProductAttributeId = $itemAttributeValue['attribute_external_id'];

                $product->attributeValues[] = $attributeValue;
            }

            $products[] = $product;
        }

        $productsBatch = new ExternalProductsBatch();
        $productsBatch->products = $products;
        $productsBatch->newUpdateToken = (int)$data['update_token'];

        return $productsBatch;
    }
}
