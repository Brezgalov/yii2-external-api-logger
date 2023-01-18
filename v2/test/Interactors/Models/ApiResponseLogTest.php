<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;

/**
 * @coversDefaultClass \Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog
 */
class ApiResponseLogTest extends BaseTestCase
{
    private string $responseStatusCode;
    private string $responseContent;
    private int $responseTime;

    private ApiResponseLog $responseLog;

    protected function prepare(): void
    {
        $this->responseStatusCode = '200';
        $this->responseContent = 'content';
        $this->responseTime = time() + 12345;
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
        $this->assertEquals($this->responseStatusCode, $this->responseLog->getResponseStatusCode());
        $this->assertEquals($this->responseContent, $this->responseLog->getResponseContent());
        $this->assertEquals($this->responseTime, $this->responseLog->getResponseTime());
    }
}
