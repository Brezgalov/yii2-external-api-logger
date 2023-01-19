<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\BaseLogTestCase;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog
 */
class ApiResponseLogTest extends BaseLogTestCase
{
    private ApiResponseLog $responseLog;

    protected function prepare(): void
    {
        $this->prepareResponseDummyData();
    }

    protected function do(): void
    {
        $this->responseLog = new ApiResponseLog(
            $this->responseStatusCode,
            $this->responseContent,
            $this->responseTime
        );
    }

    protected function validate(): void
    {
        $this->validateResponseLog($this->responseLog);
    }
}
