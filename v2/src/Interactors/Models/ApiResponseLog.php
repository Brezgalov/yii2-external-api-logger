<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Models;

class ApiResponseLog implements IApiResponseLog
{
    private string $responseStatusCode;
    private string $responseContent;
    private int $responseTime;

    public function __construct(
        string $responseStatusCode,
        string $responseContent,
        int $responseTime = null
    )
    {
        $this->responseStatusCode = $responseStatusCode;
        $this->responseContent = $responseContent;
        $this->responseTime = $responseTime ?: time();
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
