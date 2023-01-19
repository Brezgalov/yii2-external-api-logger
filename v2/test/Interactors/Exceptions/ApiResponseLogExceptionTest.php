<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiResponseLogException;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\BaseLogTestCase;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiResponseLogException
 */
class ApiResponseLogExceptionTest extends BaseLogTestCase
{
    private ApiResponseLogException $responseException;

    protected function prepare(): void
    {
        $this->prepareResponseDummyData();
    }

    protected function do(): void
    {
        $this->responseException = new ApiResponseLogException(
            $this->responseStatusCode,
            $this->responseContent,
            $this->responseTime
        );
    }

    protected function validate(): void
    {
        $this->validateResponseLog($this->responseException);
    }
}
