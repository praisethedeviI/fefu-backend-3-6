<?php

declare(strict_types=1);

namespace App\Sync;

final class ExternalProductsBatch
{
    /** @var ExternalProduct[] */
    public array $products;
    public int $newUpdateToken;
}
