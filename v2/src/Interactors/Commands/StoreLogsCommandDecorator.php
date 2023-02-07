<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\IApiCallLogsStorage;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

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

    public function sendApiRequest(): ISendApiRequestCommand
    {
        try {
            $this->command->sendApiRequest();

            $this->storeResponse(
                $this->command->getApiCallLog()
            );

            return $this;
        } catch (Throwable $ex) {
            $this->storeResponse($ex);

            throw $ex;
        }
    }

    private function storeResponse(mixed $response): void
    {
        if ($response instanceof IApiCallLog) {
            $this->logsStorage->storeSingleLog($response);
        } elseif ($response instanceof IApiCallLogsCollection) {
            $this->logsStorage->storeLogsCollection($response);
        }
    }

    public function getApiCallLog(): ?IApiCallLog
    {
        return $this->command->getApiCallLog();
    }
}
