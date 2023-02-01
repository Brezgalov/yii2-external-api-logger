<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\Storage;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

abstract class AbstractApiCallLogsStorage implements IApiCallLogsStorage
{
    public function storeLogsCollection(IApiCallLogsCollection $logsCollection): void
    {
        $collection = clone $logsCollection;
        $collection->rewind();

        foreach ($collection as $apiCallLog) {
            $this->storeSingleLog($apiCallLog);
        }
    }

    public abstract function storeSingleLog(IApiCallLog $log): void;
}
