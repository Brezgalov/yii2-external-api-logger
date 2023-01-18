<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Models;

class ApiRequestLog implements IApiRequestLog
{
    private string $method;
    private string $url;
    private array $input;
    private ?string $activityId;
    private ?string $requestGroup;
    private ?string $requestId;
    private ?string $controllerName;
    private ?string $actionName;
    private ?int $userAuthorizedId;
    private int $requestTime;

    public function __construct(
        string $method,
        string $url,
        array $input = [],
        string $activityId = null,
        string $requestGroup = null,
        string $requestId = null,
        string $controllerName = null,
        string $actionName = null,
        int $userAuthorizedId = null,
        int $requestTime = null
    )
    {
        $this->method = $method;
        $this->url = $url;
        $this->input = $input;
        $this->activityId = $activityId;
        $this->requestGroup = $requestGroup;
        $this->requestId = $requestId;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->userAuthorizedId = $userAuthorizedId;
        $this->requestTime = $requestTime ?: time();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function getRequestTime(): int
    {
        return $this->requestTime;
    }

    public function getActivityId(): ?string
    {
        return $this->activityId;
    }

    public function getRequestGroup(): ?string
    {
        return $this->requestGroup;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getControllerName(): ?string
    {
        return $this->controllerName;
    }

    public function getActionName(): ?string
    {
        return $this->actionName;
    }

    public function getUserAuthorizedId(): ?int
    {
        return $this->userAuthorizedId;
    }
}
