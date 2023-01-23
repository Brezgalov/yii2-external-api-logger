<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Exceptions\ApiCallLogsCollectionThrowable;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiCallLogException;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiCallLogsCollectionException;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Collections\BaseApiCallLogsCollectionTest;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiCallLogsCollectionException
 */
class ApiCallLogsCollectionThrowableMakeFromApiLogCollectionTest extends BaseApiCallLogsCollectionTest
{
    protected function makeCollection(): IApiCallLogsCollection
    {
        $collection = new ApiCallLogsCollection([
            $this->makeTestLog1(),
        ]);

        $logsCollectionThrowable = new ApiCallLogsCollectionException(
            new ApiCallLogsCollection([
                $this->makeTestLog2()
            ])
        );

        return ApiCallLogsCollectionException::makeFromLogsCollectionAndLogThrowable(
            $collection,
            $logsCollectionThrowable
        );
    }
}
