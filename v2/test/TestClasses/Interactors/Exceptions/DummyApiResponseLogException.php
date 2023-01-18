<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;
use Throwable;

class DummyApiResponseLogException extends \Exception implements IApiResponseLog
{
    private string $responseStatusCode;
    private string $responseContent;
    private ?int $responseTime;

    public function __construct(
        string $responseStatusCode,
        string $responseContent,
        int $responseTime = null,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->responseStatusCode = $responseStatusCode;
        $this->responseContent = $responseContent;
        $this->responseTime = $responseTime;
    }

    public function getResponseStatusCode(): string
    {
        return $this->responseStatusCode;
    }

    public function getResponseContent(): string
    {
        return $this->responseContent;
    }

    public function getResponseTime(): int
    {
        return $this->responseTime;
    }
}
