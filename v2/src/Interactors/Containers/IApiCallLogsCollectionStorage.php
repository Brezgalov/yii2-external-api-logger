<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Containers;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

interface IApiCallLogsCollectionStorage
{
    public function storeApiLog(IApiCallLog $log): void;
}
