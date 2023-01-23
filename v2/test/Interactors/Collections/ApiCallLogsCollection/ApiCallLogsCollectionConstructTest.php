<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Collections\ApiCallLogsCollection;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Collections\BaseApiCallLogsCollectionTest;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection
 */
class ApiCallLogsCollectionConstructTest extends BaseApiCallLogsCollectionTest
{
    protected function makeCollection(): IApiCallLogsCollection
    {
        return new ApiCallLogsCollection($this->getExpectedLogsArray());
    }
}
