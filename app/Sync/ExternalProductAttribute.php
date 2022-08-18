<?php

declare(strict_types=1);

namespace App\Sync;

use App\Enums\ProductAttributeType;

final class ExternalProductAttribute extends ExternalEntity
{
    public string $name;
    /**
     * @var int
     * @see ProductAttributeType
     */
    public int $type;
}
