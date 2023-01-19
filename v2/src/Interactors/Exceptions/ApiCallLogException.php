<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Exception;
use Throwable;

class ApiCallLogException extends Exception implements IApiCallLogThrowable
{
    private IApiCallLog $callLog;

    public function __construct(IApiCallLog $callLog, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->callLog = $callLog;
    }

    public function getMethod(): string
    {
        return $this->callLog->getMethod();
    }

    public function getUrl(): string
    {
        return $this->callLog->getUrl();
    }

    public function getInput(): array
    {
        return $this->callLog->getInput();
    }

    public function getRequestTime(): int
    {
        return $this->callLog->getRequestTime();
    }

    public function getActivityId(): ?string
    {
        return $this->callLog->getActivityId();
    }

    public function getRequestGroup(): ?string
    {
        return $this->callLog->getRequestGroup();
    }

    public function getRequestId(): ?string
    {
        return $this->callLog->getRequestId();
    }

    public function getControllerName(): ?string
    {
        return $this->callLog->getControllerName();
    }

    public function getActionName(): ?string
    {
        return $this->callLog->getActionName();
    }

    public function getUserAuthorizedId(): ?int
    {
        return $this->callLog->getUserAuthorizedId();
    }

    public function getResponseStatusCode(): string
    {
        return $this->callLog->getResponseStatusCode();
    }

    public function getResponseContent(): string
    {
        return $this->callLog->getResponseContent();
    }

    public function getResponseTime(): int
    {
        return $this->callLog->getResponseTime();
    }
}
