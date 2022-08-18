<?php

declare(strict_types=1);

namespace App\Sync;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

final class ExternalSyncFailureException extends Exception
{
    private int $httpCode;
    private string $body;

    public function __construct(int $httpCode, string $body, ?string $message, ?Throwable $previous)
    {
        parent::__construct($message ?? $previous->getMessage() ?? '', 0, $previous);
        $this->httpCode = $httpCode;
        $this->body = $body;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
