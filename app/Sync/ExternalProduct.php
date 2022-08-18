<?php

declare(strict_types=1);

namespace App\Sync;

final class ExternalProduct extends ExternalEntity
{
    public string $externalProductCategoryId;
    public string $name;
    public float $price;
    public string $description;
    /** @var ExternalProductAttributeValue[] */
    public array $attributeValues;

    public bool $isDeleted;
}
