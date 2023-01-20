<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Throwable;

class ApiCallLogsCollectionThrowable extends \Exception implements IApiCallLogsCollection, Throwable
{
    private IApiCallLogsCollection $logsCollection;

    public function __construct(
        IApiCallLogsCollection $logsCollection,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->logsCollection = $logsCollection;
    }

    public function next()
    {
        $this->logsCollection->next();
    }

    public function key()
    {
        return $this->logsCollection->key();
    }

    public function valid()
    {
        return $this->logsCollection->valid();
    }

    public function rewind()
    {
        $this->logsCollection->rewind();
    }

    public function current(): IApiCallLog
    {
        return $this->logsCollection->current();
    }
}
