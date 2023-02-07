<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\IApiCallLogsStorage;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

abstract class AbstractSendAndLogApiRequestCommand extends AbstractSendApiRequestCommand
{
    private IApiCallLogsStorage $logsStorage;

    public function __construct(IApiCallLogsStorage $logsStorage)
    {
        $this->logsStorage = $logsStorage;

        parent::__construct();
    }

    public function sendApiRequest(): AbstractSendAndLogApiRequestCommand
    {
        try {
            $this->sendApiRequest();

            $this->storeResponse(
                $this->getApiCallLog()
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
}
