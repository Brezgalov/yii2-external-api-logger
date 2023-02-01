<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\DataStorage\Storage\LogsStorageAdapter;

use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\LogsStorageAdapter;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs\AllFineLogsStorage;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\DummyApiCallLog;
use Throwable;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\DataStorage\Storage\LogsStorageAdapter
 */
class StoreSingleLogSuccessTest extends BaseTestCase
{
    private IApiCallLog $apiCallLog;
    private LogsStorageAdapter $logsStorageAdapter;

    protected ?Throwable $ex = null;

    protected function prepare(): void
    {
        $this->apiCallLog = new DummyApiCallLog();
        $this->logsStorageAdapter = new LogsStorageAdapter(
            new AllFineLogsStorage()
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
        $this->assertNull($this->ex);
    }
}
