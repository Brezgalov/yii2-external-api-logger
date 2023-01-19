<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiRequestLogException;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\BaseLogTestCase;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiRequestLogException
 */
class ApiRequestLogExceptionTest extends BaseLogTestCase
{
    private ApiRequestLogException $requestException;

    protected function prepare(): void
    {
        $this->prepareRequestDummyData();
    }

    protected function do(): void
    {
        $this->requestException = new ApiRequestLogException(
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
            )
        );
    }

    protected function validate(): void
    {
        $this->validateRequestLog($this->requestException);
    }
}
