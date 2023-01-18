<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;

/**
 * @coversDefaultClass \Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog
 */
class ApiCallLogTest extends BaseTestCase
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
    private string $responseStatusCode;
    private string $responseContent;
    private int $responseTime;

    private ApiCallLog $callLog;

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
        $this->responseStatusCode = '200';
        $this->responseContent = 'content';
        $this->responseTime = time() + 12345;
    }

    protected function do(): void
    {
        $this->callLog = new ApiCallLog(
            new ApiRequestLog(
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
            ),
            new ApiResponseLog(
                $this->responseStatusCode,
                $this->responseContent,
                $this->responseTime
            )
        );
    }

    protected function validate(): void
    {
        $this->assertEquals($this->method, $this->callLog->getMethod());
        $this->assertEquals($this->url, $this->callLog->getUrl());
        $this->assertEquals($this->input, $this->callLog->getInput());
        $this->assertEquals($this->activityId, $this->callLog->getActivityId());
        $this->assertEquals($this->requestGroup, $this->callLog->getRequestGroup());
        $this->assertEquals($this->requestId, $this->callLog->getRequestId());
        $this->assertEquals($this->controllerName, $this->callLog->getControllerName());
        $this->assertEquals($this->actionName, $this->callLog->getActionName());
        $this->assertEquals($this->userAuthorizedId, $this->callLog->getUserAuthorizedId());
        $this->assertEquals($this->requestTime, $this->callLog->getRequestTime());
        $this->assertEquals($this->responseStatusCode, $this->callLog->getResponseStatusCode());
        $this->assertEquals($this->responseContent, $this->callLog->getResponseContent());
        $this->assertEquals($this->responseTime, $this->callLog->getResponseTime());
    }
}
