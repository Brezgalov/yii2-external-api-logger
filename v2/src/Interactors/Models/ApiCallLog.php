<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Models;

class ApiCallLog implements IApiCallLog
{
    private IApiRequestLog $requestLog;
    private IApiResponseLog $responseLog;

    public function __construct(
        IApiRequestLog $requestLog,
        IApiResponseLog $responseLog,
    )
    {
        $this->requestLog = $requestLog;
        $this->responseLog = $responseLog;
    }

    public function getMethod(): string
    {
        return $this->requestLog->getMethod();
    }

    public function getUrl(): string
    {
        return $this->requestLog->getUrl();
    }

    public function getInput(): array
    {
        return $this->requestLog->getInput();
    }

    public function getRequestTime(): int
    {
        return $this->requestLog->getRequestTime();
    }

    public function getActivityId(): ?string
    {
        return $this->requestLog->getActivityId();
    }

    public function getRequestGroup(): ?string
    {
        return $this->requestLog->getRequestGroup();
    }

    public function getRequestId(): ?string
    {
        return $this->requestLog->getRequestId();
    }

    public function getControllerName(): ?string
    {
        return $this->requestLog->getControllerName();
    }

    public function getActionName(): ?string
    {
        return $this->requestLog->getActionName();
    }

    public function getUserAuthorizedId(): ?int
    {
        return $this->requestLog->getUserAuthorizedId();
    }

    public function getResponseStatusCode(): string
    {
        return $this->responseLog->getResponseStatusCode();
    }

    public function getResponseContent(): string
    {
        return $this->responseLog->getResponseContent();
    }

    public function getResponseTime(): int
    {
        return $this->responseLog->getResponseTime();
    }
}
