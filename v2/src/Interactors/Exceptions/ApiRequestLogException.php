<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiRequestLog;
use Exception;
use Throwable;

class ApiRequestLogException extends Exception implements IApiRequestLogThrowable
{
    private IApiRequestLog $requestLog;

    public function __construct(IApiRequestLog $requestLog, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->requestLog = $requestLog;
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
}
