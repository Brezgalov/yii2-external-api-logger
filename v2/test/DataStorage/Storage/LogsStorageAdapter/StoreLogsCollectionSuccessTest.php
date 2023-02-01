<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\DataStorage\Storage\LogsStorageAdapter;

use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\LogsStorageAdapter;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs\AllFineLogsStorage;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\DummyApiCallLog;
use Throwable;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\DataStorage\Storage\AbstractApiCallLogsStorage
 */
class StoreLogsCollectionSuccessTest extends BaseTestCase
{
    private IApiCallLogsCollection $apiCallLogsCollection;
    private LogsStorageAdapter $logsStorageAdapter;

    protected ?Throwable $ex = null;

    protected function prepare(): void
    {
        $this->apiCallLogsCollection = new ApiCallLogsCollection([
            new DummyApiCallLog(),
            new DummyApiCallLog(),
            new DummyApiCallLog(),
        ]);
        $this->logsStorageAdapter = new LogsStorageAdapter(
            new AllFineLogsStorage()
        );
    }

    protected function do(): void
    {
        try {
            $this->logsStorageAdapter->storeLogsCollection($this->apiCallLogsCollection);
        } catch (Throwable $ex) {
            $this->ex = $ex;
        }
    }

    protected function validate(): void
    {
        $this->assertNull($this->ex);
    }
}
