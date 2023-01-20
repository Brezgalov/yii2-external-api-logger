<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\BaseLogTestCase;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog
 */
class ApiCallLogTest extends BaseLogTestCase
{
    private ApiCallLog $callLog;

    protected function do(): void
    {
        $this->callLog = new ApiCallLog(
            new ApiRequestLog(
                $this->requestMethod,
                $this->requestUrl,
                $this->requestInput,
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
        $this->validateCallLog($this->callLog);
    }
}