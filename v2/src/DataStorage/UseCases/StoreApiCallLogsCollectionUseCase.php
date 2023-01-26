<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\UseCases;

use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;

class StoreApiCallLogsCollectionUseCase implements IStoreApiCallLogsCollectionUseCase
{
    private IStoreApiCallLogUseCase $storeUseCase;

    public function __construct(ILogsStorage $extApiLogsStorage)
    {
        $this->storeUseCase = new StoreApiCallLogUseCase($extApiLogsStorage);
    }

    public function storeLog(IApiCallLogsCollection $logsCollection): void
    {
        foreach ($logsCollection as $log) {
            $this->storeUseCase->storeLog($log);
        }
    }
}
