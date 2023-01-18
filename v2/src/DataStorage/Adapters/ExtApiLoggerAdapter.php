<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\Adapters;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\LogsStorage\IApiLogFullDto;

class ExtApiLoggerAdapter implements IApiLogFullDto
{
    private IApiCallLog $apiCallLog;

    public function __construct(IApiCallLog $apiCallLog)
    {
        $this->apiCallLog = $apiCallLog;
    }

    public function getMethod(): string
    {
        return $this->apiCallLog->getMethod();
    }

    public function getUrl(): string
    {
        return $this->apiCallLog->getUrl();
    }

    public function getInput(): ?array
    {
        return $this->apiCallLog->getInput();
    }

    public function getRequestTime(): int
    {
        return $this->apiCallLog->getRequestTime();
    }

    public function getActivityId(): ?string
    {
        return $this->apiCallLog->getActivityId();
    }

    public function getRequestGroup(): ?string
    {
        return $this->apiCallLog->getRequestGroup();
    }

    public function getRequestId(): ?string
    {
        return $this->apiCallLog->getRequestId();
    }

    public function getControllerName(): ?string
    {
        return $this->apiCallLog->getControllerName();
    }

    public function getActionName(): ?string
    {
        return $this->apiCallLog->getActionName();
    }

    public function getUserId(): ?int
    {
        return $this->apiCallLog->getUserAuthorizedId();
    }

    public function getStatusCode(): string
    {
        return $this->apiCallLog->getResponseStatusCode();
    }

    public function getResponseContent(): string
    {
        return $this->apiCallLog->getResponseContent();
    }

    public function getResponseTime(): int
    {
        return $this->apiCallLog->getResponseTime();
    }
}
