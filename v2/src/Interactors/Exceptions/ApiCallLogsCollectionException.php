<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\ApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Exception;
use Throwable;

class ApiCallLogsCollectionException extends Exception implements IApiCallLogsCollectionThrowable
{
    private ApiCallLogsCollection $logsCollection;

    /**
     * Use this method to combine logs together when you already collected some logs
     * and eventually receive an ApiCalLogThrowable
     */
    public static function makeFromLogsCollectionAndLogThrowable(
        IApiCallLogsCollection $apiCallLogsCollection,
        IApiCallLogThrowable|IApiCallLogsCollectionThrowable $throwable
    ): ApiCallLogsCollectionException
    {
        $selfInstance = new self(
            $apiCallLogsCollection,
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable
        );

        if ($throwable instanceof IApiCallLogThrowable) {
            $selfInstance->logsCollection->storeApiLog($throwable);
        } elseif ($throwable instanceof IApiCallLogsCollectionThrowable) {
            foreach ($throwable as $apiCallLog) {
                $selfInstance->logsCollection->storeApiLog($apiCallLog);
            }
        }

        return $selfInstance;
    }

    public function __construct(
        IApiCallLogsCollection $logsCollection,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);

        $this->logsCollection = new ApiCallLogsCollection(
            $logsCollection->toArray()
        );
    }

    public function next(): void
    {
        $this->logsCollection->next();
    }

    public function key(): int
    {
        return $this->logsCollection->key();
    }

    public function valid(): bool
    {
        return $this->logsCollection->valid();
    }

    public function rewind(): void
    {
        $this->logsCollection->rewind();
    }

    public function current(): IApiCallLog
    {
        return $this->logsCollection->current();
    }

    public function toArray(): array
    {
        return $this->logsCollection->toArray();
    }
}
