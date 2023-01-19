<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiCallLogException;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\BaseLogTestCase;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiCallLogException
 */
class ApiCallLogExceptionTest extends BaseLogTestCase
{
    private ApiCallLogException $callException;

    protected function do(): void
    {
        $this->callException = new ApiCallLogException(
            new ApiCallLog(
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
            )
        );
    }

    protected function validate(): void
    {
        $this->validateCallLog($this->callException);
    }
}
