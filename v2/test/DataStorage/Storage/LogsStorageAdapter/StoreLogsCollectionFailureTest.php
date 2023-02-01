<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\DataStorage\Storage\LogsStorageAdapter;

use Brezgalov\ExtApiLogger\v2\DataStorage\Exceptions\LogStorageException;
use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\LogsStorageAdapter;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs\AllGoneWrongLogsStorage;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\DummyApiCallLog;
use Throwable;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\DataStorage\Storage\AbstractApiCallLogsStorage
 */
class StoreLogsCollectionFailureTest extends BaseTestCase
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
            new AllGoneWrongLogsStorage()
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
        $this->assertInstanceOf(LogStorageException::class, $this->ex);
    }
}
