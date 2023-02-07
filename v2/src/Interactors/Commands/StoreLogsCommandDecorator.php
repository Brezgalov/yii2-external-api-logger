<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\IApiCallLogsStorage;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Throwable;

/**
 * Use this decorator to save logs on sendApiRequest call
 */
class StoreLogsCommandDecorator implements ISendApiRequestCommand
{
    private IApiCallLogsStorage $logsStorage;
    private ISendApiRequestCommand $command;

    public function __construct(
        IApiCallLogsStorage $logsStorage,
        ISendApiRequestCommand $command
    ) {

        $this->logsStorage = $logsStorage;
        $this->command = $command;
    }

    public function sendApiRequest(): mixed
    {
        try {
            $response = $this->command->sendApiRequest();

            $this->storeRequestLog(
                $this->command->getApiCallLog()
            );

            return $response;
        } catch (Throwable $ex) {
            $this->storeRequestLog($ex);

            throw $ex;
        }
    }

    private function storeRequestLog(mixed $log): void
    {
        if ($log instanceof IApiCallLog) {
            $this->logsStorage->storeSingleLog($log);
        } elseif ($log instanceof IApiCallLogsCollection) {
            $this->logsStorage->storeLogsCollection($log);
        }
    }

    public function getApiCallLog(): ?IApiCallLog
    {
        return $this->command->getApiCallLog();
    }
}
