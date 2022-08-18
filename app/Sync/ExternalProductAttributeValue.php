<?php

declare(strict_types=1);

namespace App\Sync;

final class ExternalProductAttributeValue
{
    public string $externalProductAttributeId;
    public string|int|bool $value;
}
