<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;

class DummySendApiRequestCommand extends AbstractSendApiRequestCommand
{
    private mixed $dummyCallBack;
    private string $requestMethod;
    private string $requestUrl;
    private array $requestInput;

    public function __construct(
        callable $dummyCallBack,
        string $requestMethod,
        string $requestUrl,
        array $requestInput,
        string $activityId,
        string $requestId,
        string $requestGroup,
        string $actionName,
        string $controllerName,
        int $userAuthorizedId
    )
    {
        parent::__construct();

        $this->dummyCallBack = $dummyCallBack;
        $this->requestMethod = $requestMethod;
        $this->requestUrl = $requestUrl;
        $this->requestInput = $requestInput;

        $this->actionName = $actionName;
        $this->controllerName = $controllerName;
        $this->requestId = $requestId;
        $this->requestGroup = $requestGroup;
        $this->activityId = $activityId;
        $this->userAuthorizedId = $userAuthorizedId;
    }

    protected function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    protected function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    protected function getRequestInput(): array
    {
        return $this->requestInput;
    }

    protected function processApiRequest(): IApiResponseLog
    {
        return call_user_func($this->dummyCallBack);
    }

}
