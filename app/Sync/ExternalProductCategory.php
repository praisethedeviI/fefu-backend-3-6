<?php

declare(strict_types=1);

namespace App\Sync;

final class ExternalProductCategory extends ExternalEntity
{
    public ?string $parentExternalId;
    public string $name;
}
