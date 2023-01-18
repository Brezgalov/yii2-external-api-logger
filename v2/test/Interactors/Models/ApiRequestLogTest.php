<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;

/**
 * @coversDefaultClass \Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog
 */
class ApiRequestLogTest extends BaseTestCase
{
    private string $method;
    private string $url;
    private array $input;
    private string $activityId;
    private string $requestId;
    private string $requestGroup;
    private string $controllerName;
    private string $actionName;
    private int $userAuthorizedId;
    private int $requestTime;

    private ApiRequestLog $requestLog;

    protected function prepare(): void
    {
        $this->method = 'POST';
        $this->url = 'my-api.com/test-method';
        $this->input = [
            'test' => 1,
        ];
        $this->activityId = 'a-1-2-3';
        $this->requestId = 'my-request-1';
        $this->requestGroup = 'request-group';
        $this->controllerName = 'TestController';
        $this->actionName = 'actionName';
        $this->userAuthorizedId = 1;
        $this->requestTime = time() - 12345;
    }

    protected function do(): void
    {
        $this->requestLog = new ApiRequestLog(
            $this->method,
            $this->url,
            $this->input,
            $this->activityId,
            $this->requestGroup,
            $this->requestId,
            $this->controllerName,
            $this->actionName,
            $this->userAuthorizedId,
            $this->requestTime
        );
    }

    protected function validate(): void
    {
        $this->assertEquals($this->method, $this->requestLog->getMethod());
        $this->assertEquals($this->url, $this->requestLog->getUrl());
        $this->assertEquals($this->input, $this->requestLog->getInput());
        $this->assertEquals($this->activityId, $this->requestLog->getActivityId());
        $this->assertEquals($this->requestGroup, $this->requestLog->getRequestGroup());
        $this->assertEquals($this->requestId, $this->requestLog->getRequestId());
        $this->assertEquals($this->controllerName, $this->requestLog->getControllerName());
        $this->assertEquals($this->actionName, $this->requestLog->getActionName());
        $this->assertEquals($this->userAuthorizedId, $this->requestLog->getUserAuthorizedId());
        $this->assertEquals($this->requestTime, $this->requestLog->getRequestTime());
    }
}
