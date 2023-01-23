<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Collections;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;

abstract class BaseApiCallLogsCollectionTest extends BaseTestCase
{
    protected IApiCallLogsCollection $collection;

    protected array $arrayFetched = [];
    protected array $arrayConverted;
    protected mixed $keyStart;
    protected mixed $keyRewind;

    protected function prepare(): void
    {
        $this->collection = $this->makeCollection();
    }

    protected abstract function makeCollection(): IApiCallLogsCollection;

    protected function getExpectedLogsArray(): array
    {
        return [
            $this->makeTestLog1(),
            $this->makeTestLog2(),
        ];
    }

    protected function makeTestLog1(): IApiCallLog
    {
        return new ApiCallLog(
            new ApiRequestLog(
                'POST',
                'http://url-1.com',
                [],
                'log1',
                null,
                null,
                null,
                null,
                123,
                100
            ),
            new ApiResponseLog(
                '200',
                'OK',
                101
            )
        );
    }

    protected function makeTestLog2(): IApiCallLog
    {
        return new ApiCallLog(
            new ApiRequestLog(
                'GET',
                'http://url-2.com',
                [],
                'log2',
                null,
                null,
                null,
                null,
                123,
                99
            ),
            new ApiResponseLog(
                '200',
                '[{"test": true}, {"test": true}]',
                101
            )
        );
    }

    protected function do(): void
    {
        $this->keyStart = $this->collection->key();

        foreach ($this->collection as $item) {
            $this->arrayFetched[] = $item;
        }

        $this->collection->rewind();
        $this->keyRewind = $this->collection->key();

        $this->arrayConverted = $this->collection->toArray();
    }

    protected function validate(): void
    {
        $this->validateLogsArray($this->getExpectedLogsArray(), $this->arrayFetched);
        $this->validateLogsArray($this->getExpectedLogsArray(), $this->arrayConverted);
        $this->assertEquals($this->keyStart, $this->keyRewind);
    }

    protected function validateLogsArray(array $logsExpected, array $logsGot)
    {
        $this->assertCount(count($logsExpected), $logsGot);
        foreach ($logsExpected as $key => $logExpected) {
            $logGot = $logsGot[$key];

            $this->assertInstanceOf(IApiCallLog::class, $logExpected);
            $this->assertInstanceOf(IApiCallLog::class, $logGot);

            $this->validateCallLogsEqual($logExpected, $logGot);
        }
    }

    protected function validateCallLogsEqual(IApiCallLog $expected, IApiCallLog $got): void
    {
        $this->assertEquals($expected->getMethod(), $got->getMethod());
        $this->assertEquals($expected->getUrl(), $got->getUrl());
        $this->assertEquals($expected->getInput(), $got->getInput());
        $this->assertEquals($expected->getActivityId(), $got->getActivityId());
        $this->assertEquals($expected->getRequestId(), $got->getRequestId());
        $this->assertEquals($expected->getRequestGroup(), $got->getRequestGroup());
        $this->assertEquals($expected->getActionName(), $got->getActionName());
        $this->assertEquals($expected->getControllerName(), $got->getControllerName());
        $this->assertEquals($expected->getUserAuthorizedId(), $got->getUserAuthorizedId());
        $this->assertEquals($expected->getRequestTime(), $got->getRequestTime());
        $this->assertEquals($expected->getResponseStatusCode(), $got->getResponseStatusCode());
        $this->assertEquals($expected->getResponseContent(), $got->getResponseContent());
        $this->assertEquals($expected->getResponseTime(), $got->getResponseTime());
    }
}
