<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Collections\ApiCallLogsCollection;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Collections\BaseApiCallLogsCollectionTest;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection
 */
class ApiCallLogsCollectionStoreLogTest extends BaseApiCallLogsCollectionTest
{
    protected function makeCollection(): IApiCallLogsCollection
    {
        return new ApiCallLogsCollection([]);
    }

    protected function do(): void
    {
        if ($this->collection instanceof ApiCallLogsCollection) {
            foreach ($this->getExpectedLogsArray() as $log) {
                $this->collection->storeApiLog($log);
            }
        }

        parent::do();
    }

    protected function validate(): void
    {
        $this->assertInstanceOf(ApiCallLogsCollection::class, $this->collection);

        parent::validate();
    }
}
