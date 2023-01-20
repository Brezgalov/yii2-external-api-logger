<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Collections;

use Brezgalov\ExtApiLogger\v2\Interactors\Containers\IApiCallLogsCollectionStorage;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\PhpTypedCollection\AbstractTypedIterator;

class ApiCallLogsCollection extends AbstractTypedIterator implements IApiCallLogsCollection, IApiCallLogsCollectionStorage
{
    public function current(): IApiCallLog
    {
        return parent::current();
    }

    protected function validateItem($item): bool
    {
        return $item instanceof IApiCallLog;
    }

    public function storeApiLog(IApiCallLog $log): void
    {
        $this->addItem($log);
    }
}
