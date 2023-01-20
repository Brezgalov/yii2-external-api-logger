<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;

abstract class BaseLogTestCase extends BaseTestCase
{
    protected string $requestMethod;
    protected string $requestUrl;
    protected array $requestInput;
    protected string $activityId;
    protected string $requestId;
    protected string $requestGroup;
    protected string $actionName;
    protected string $controllerName;
    protected int $userAuthorizedId;
    protected int $requestTime;
    protected string $responseStatusCode;
    protected string $responseContent;
    protected int $responseTime;

    protected function prepare(): void
    {
        $this->prepareRequestDummyData();
        $this->prepareResponseDummyData();
    }

    protected function prepareRequestDummyData()
    {
        $this->requestMethod = 'POST';
        $this->requestUrl = 'my-api.com/test-method';
        $this->requestInput = [
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

    protected function prepareResponseDummyData()
    {
        $this->responseStatusCode = '200';
        $this->responseContent = 'content';
        $this->responseTime = time() + 12345;
    }

    protected function validateCallLog(IApiCallLog $log): void
    {
        $this->validateRequestLog($log);
        $this->validateResponseLog($log);
    }

    protected function validateRequestLog(IApiRequestLog $log): void
    {
        $this->assertEquals($this->requestMethod, $log->getMethod());
        $this->assertEquals($this->requestUrl, $log->getUrl());
        $this->assertEquals($this->requestInput, $log->getInput());
        $this->assertEquals($this->activityId, $log->getActivityId());
        $this->assertEquals($this->requestId, $log->getRequestId());
        $this->assertEquals($this->requestGroup, $log->getRequestGroup());
        $this->assertEquals($this->actionName, $log->getActionName());
        $this->assertEquals($this->controllerName, $log->getControllerName());
        $this->assertEquals($this->userAuthorizedId, $log->getUserAuthorizedId());
        $this->assertEquals($this->requestTime, $log->getRequestTime());
    }

    protected function validateResponseLog(IApiResponseLog $log): void
    {
        $this->assertEquals($this->responseStatusCode, $log->getResponseStatusCode());
        $this->assertEquals($this->responseContent, $log->getResponseContent());
        $this->assertEquals($this->responseTime, $log->getResponseTime());
    }
}