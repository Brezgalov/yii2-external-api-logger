<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\Storage;

use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use Brezgalov\ExtApiLogger\v2\DataStorage\Adapters\ExtApiLoggerAdapter;
use Brezgalov\ExtApiLogger\v2\DataStorage\Exceptions\LogStorageException;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

class LogsStorageAdapter extends AbstractApiCallLogsStorage
{
    private ILogsStorage $extApiLogsStorage;

    public function __construct(ILogsStorage $extApiLogsStorage)
    {
        $this->extApiLogsStorage = $extApiLogsStorage;
    }

    public function storeSingleLog(IApiCallLog $log): void
    {
        $adapter = new ExtApiLoggerAdapter($log);

        if(!$this->extApiLogsStorage->storeLog($adapter)) {
            throw new LogStorageException("Не удается сохранить лог запроса апи [{$log->getMethod()}] {$log->getUrl()}");
        }
    }
}
