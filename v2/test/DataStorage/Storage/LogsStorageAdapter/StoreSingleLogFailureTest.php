<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\DataStorage\Storage\LogsStorageAdapter;

use Brezgalov\ExtApiLogger\v2\DataStorage\Exceptions\LogStorageException;
use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\LogsStorageAdapter;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs\AllGoneWrongLogsStorage;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\DummyApiCallLog;
use Throwable;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\DataStorage\Storage\LogsStorageAdapter
 */
class StoreSingleLogFailureTest extends BaseTestCase
{
    private IApiCallLog $apiCallLog;
    private LogsStorageAdapter $logsStorageAdapter;

    protected ?Throwable $ex = null;

    protected function prepare(): void
    {
        $this->apiCallLog = new DummyApiCallLog();
        $this->logsStorageAdapter = new LogsStorageAdapter(
            new AllGoneWrongLogsStorage()
        );
    }

    protected function do(): void
    {
        try {
            $this->logsStorageAdapter->storeSingleLog($this->apiCallLog);
        } catch (Throwable $ex) {
            $this->ex = $ex;
        }
    }

    protected function validate(): void
    {
        $this->assertInstanceOf(LogStorageException::class, $this->ex);
    }
}
